<?php
include_once("db_connect.php");

function h($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function checkForm()
{
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        http_response_code(403);
        exit('Please reload the page and submit the form again.');
    }
}

function showLandingPage()
{
    ?>
    <H2>Real Experience. Better Choices.</H2>
    <P>ClassExp is a class evaluation platform. Students share ratings and
        comments about classes they have completed.</P>
    <P>Rate teaching clarity, workload, grading fairness, and your overall
        experience to help future students choose their classes.</P>
    <P><A href="?op=student">Go to the student dashboard</A></P>
    <?php
}

function getStudent($db, $uid)
{
    if (!$db || !$uid)
        return false;
    $sql = $db->prepare("SELECT user_id, first_name FROM users WHERE user_id=? AND role='student' AND is_active=1");
    $sql->execute([$uid]);
    return $sql->fetch();
}

function showLoginForm()
{
    ?>
    <H2>Student sign in</H2>
    <FORM method="POST" action="?op=login">
        <INPUT type="hidden" name="token" value="<?php print h($_SESSION['token']); ?>">
        <LABEL for="email">Email</LABEL>
        <INPUT id="email" type="email" name="email" autocomplete="username" required>
        <LABEL for="password">Password</LABEL>
        <INPUT id="password" type="password" name="password" autocomplete="current-password" required>
        <BUTTON type="submit">Sign in</BUTTON>
    </FORM>
    <?php
}

function loginStudent($db, $input)
{
    if (!$db)
        return 'Student sign-in is temporarily unavailable.';
    $sql = $db->prepare("SELECT user_id, password_hash FROM users WHERE email=? AND role='student' AND is_active=1");
    $sql->execute([trim($input['email'] ?? '')]);
    $student = $sql->fetch();
    if (!$student || !password_verify($input['password'] ?? '', $student['password_hash'])) {
        return 'Email or password is incorrect.';
    }
    session_regenerate_id(true);
    $_SESSION['uid'] = $student['user_id'];
    return 'Signed in successfully.';
}

function showLogoutForm()
{
    ?>
    <FORM method="POST" action="?op=logout">
        <INPUT type="hidden" name="token" value="<?php print h($_SESSION['token']); ?>">
        <BUTTON type="submit">Sign out</BUTTON>
    </FORM>
    <?php
}

function showStudentDashboard($db, $student)
{
    // Only completed sections that this student has not already reviewed.
    $sql = $db->prepare("SELECT s.section_id, c.course_code, s.section_number, t.term_name
                        FROM enrollments n JOIN sections s ON n.section_id=s.section_id
                        JOIN courses c ON s.course_id=c.course_id JOIN terms t ON s.term_id=t.term_id
                        LEFT JOIN student_evaluations e ON e.student_id=n.student_id AND e.section_id=n.section_id
                        WHERE n.student_id=? AND n.status='completed' AND n.completed_at<=CURRENT_DATE
                          AND t.end_date<=CURRENT_DATE AND e.evaluation_id IS NULL
                        ORDER BY c.course_code");
    $sql->execute([$student['user_id']]);
    $classes = $sql->fetchAll();
    print '<H2>Student dashboard</H2><P>Welcome, ' . h($student['first_name']) . '.</P>';
    print '<H3>Post ratings and a comment</H3>';
    if (!$classes) {
        print '<P>You have no completed classes waiting for an evaluation.</P>';
        return;
    }
    ?>
    <FORM method="POST" action="?op=post">
        <INPUT type="hidden" name="token" value="<?php print h($_SESSION['token']); ?>">
        <LABEL for="section_id">Completed class</LABEL>
        <SELECT id="section_id" name="section_id" required>
            <OPTION value="">Choose a class</OPTION>
            <?php foreach ($classes as $class) { ?>
                <OPTION value="<?php print (int) $class['section_id']; ?>">
                    <?php print h($class['course_code'] . ' / ' . $class['section_number'] . ' / ' . $class['term_name']); ?>
                </OPTION>
            <?php } ?>
        </SELECT>
        <P>Ratings: 1 = poor, 5 = excellent. For workload: 1 = light, 5 = heavy.</P>
        <?php
        foreach ([
            'teaching_clarity' => 'Teaching clarity',
            'workload_rating' => 'Workload',
            'grading_fairness' => 'Grading fairness',
            'overall_rating' => 'Overall experience'
        ] as $field => $label) {
            print '<LABEL for="' . $field . '">' . $label . '</LABEL>';
            print '<SELECT id="' . $field . '" name="' . $field . '" required><OPTION value="">Choose a rating</OPTION>';
            for ($rating = 1; $rating <= 5; $rating++) {
                print '<OPTION value="' . $rating . '">' . $rating . '</OPTION>';
            }
            print '</SELECT>';
        }
        ?>
        <LABEL for="comment">Comment</LABEL>
        <TEXTAREA id="comment" name="comment" rows="4" maxlength="2000" required></TEXTAREA>
        <BUTTON type="submit">Post evaluation</BUTTON>
    </FORM>
    <?php
}

function postEvaluation($db, $uid, $input)
{
    if (!$db || !$uid)
        return 'Please sign in as a student before posting.';
    $section = filter_var($input['section_id'] ?? '', FILTER_VALIDATE_INT);
    $ratings = [];
    foreach (['teaching_clarity', 'workload_rating', 'grading_fairness', 'overall_rating'] as $field) {
        $rating = filter_var($input[$field] ?? '', FILTER_VALIDATE_INT);
        if ($rating === false || $rating < 1 || $rating > 5)
            return 'Ratings must be whole numbers from 1 to 5.';
        $ratings[] = $rating;
    }
    $comment = trim($input['comment'] ?? '');
    if ($comment === '' || mb_strlen($comment) > 2000)
        return 'Enter a comment of 1 to 2,000 characters.';

    // Check enrollment on the server rather than trusting the selected class.
    $sql = $db->prepare("SELECT n.section_id FROM enrollments n
                        JOIN sections s ON n.section_id=s.section_id JOIN terms t ON s.term_id=t.term_id
                        WHERE n.student_id=? AND n.section_id=? AND n.status='completed'
                          AND n.completed_at<=CURRENT_DATE AND t.end_date<=CURRENT_DATE");
    $sql->execute([$uid, $section]);
    if (!$sql->fetch())
        return 'You can only evaluate a class you have completed.';

    $sql = $db->prepare('INSERT INTO student_evaluations
                        (student_id, section_id, teaching_clarity, workload_rating, grading_fairness, overall_rating, comment)
                        VALUES (?, ?, ?, ?, ?, ?, ?)');
    try {
        $sql->execute(array_merge([$uid, $section], $ratings, [$comment]));
        return 'Your ratings and comment were posted successfully.';
    } catch (PDOException $e) {
        if ($e->getCode() === '23000')
            return 'You have already evaluated this class.';
        error_log($e->getMessage());
        return 'The evaluation could not be saved. Please try again.';
    }
}
