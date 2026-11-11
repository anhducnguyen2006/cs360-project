<?php
session_start();
include("util.php");

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}
$op = $_GET['op'] ?? 'home';
$student = getStudent($db, $_SESSION['uid'] ?? 0);

// Handle submitted forms before displaying the page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkForm();
    switch ($op) {
        case 'login':
            $_SESSION['message'] = loginStudent($db, $_POST);
            break;
        case 'post':
            $_SESSION['message'] = postEvaluation($db, $student['user_id'] ?? 0, $_POST);
            break;
        case 'logout':
            unset($_SESSION['uid']);
            break;
    }
    header('Location: dashboard.php?op=' . ($op === 'logout' ? 'home' : 'student'));
    exit();
}
?>
<!DOCTYPE html>
<HTML lang="en">

<HEAD>
    <META charset="UTF-8">
    <META name="viewport" content="width=device-width, initial-scale=1">
    <TITLE>ClassExp</TITLE>
    <LINK rel="stylesheet" href="style.css">
</HEAD>

<BODY>
    <DIV class="container">
        <H1>ClassExp</H1>
        <DIV class="menu">
            <A href="?op=home">Home</A>
            <A href="?op=student">Student dashboard</A>
            <?php if ($student)
                showLogoutForm(); ?>
        </DIV>
        <DIV class="main">
            <?php
            if (!empty($_SESSION['message'])) {
                print '<P class="message">' . h($_SESSION['message']) . '</P>';
                unset($_SESSION['message']);
            }
            switch ($op) {
                case 'student':
                    if ($student) {
                        showStudentDashboard($db, $student);
                    } else {
                        showLoginForm();
                    }
                    break;
                default:
                    showLandingPage();
                    break;
            }
            ?>
        </DIV>
    </DIV>
</BODY>

</HTML>