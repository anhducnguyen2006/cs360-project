-- ClassExp database backup for Project 2
-- MySQL-compatible schema and sample data.
-- Replace the database name below if your assigned database uses a different name.

CREATE DATABASE IF NOT EXISTS class_exp;
USE class_exp;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS moderation_log;
DROP TABLE IF EXISTS professor_evaluations;
DROP TABLE IF EXISTS student_evaluations;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS sections;
DROP TABLE IF EXISTS terms;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'professor', 'admin') NOT NULL,
    class_year YEAR NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE = InnoDB;

CREATE TABLE courses (
    course_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(15) NOT NULL UNIQUE,
    course_name VARCHAR(120) NOT NULL,
    department VARCHAR(80) NOT NULL,
    description TEXT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE terms (
    term_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    term_name VARCHAR(40) NOT NULL UNIQUE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
) ENGINE = InnoDB;

CREATE TABLE sections (
    section_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    term_id INT UNSIGNED NOT NULL,
    section_number VARCHAR(10) NOT NULL,
    schedule VARCHAR(80) NOT NULL,
    room VARCHAR(40) NOT NULL,
    professor_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_sections_course
        FOREIGN KEY (course_id) REFERENCES courses(course_id),
    CONSTRAINT fk_sections_term
        FOREIGN KEY (term_id) REFERENCES terms(term_id),
    CONSTRAINT fk_sections_professor
        FOREIGN KEY (professor_id) REFERENCES users(user_id),
    CONSTRAINT uq_section_term
        UNIQUE (course_id, term_id, section_number)
) ENGINE = InnoDB;

CREATE TABLE enrollments (
    enrollment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NOT NULL,
    status ENUM('enrolled', 'completed', 'withdrawn') NOT NULL DEFAULT 'enrolled',
    completed_at DATE NULL,
    CONSTRAINT fk_enrollments_student
        FOREIGN KEY (student_id) REFERENCES users(user_id),
    CONSTRAINT fk_enrollments_section
        FOREIGN KEY (section_id) REFERENCES sections(section_id),
    CONSTRAINT uq_student_section
        UNIQUE (student_id, section_id)
) ENGINE = InnoDB;

CREATE TABLE student_evaluations (
    evaluation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NOT NULL,
    teaching_clarity TINYINT UNSIGNED NOT NULL,
    workload_rating TINYINT UNSIGNED NOT NULL,
    grading_fairness TINYINT UNSIGNED NOT NULL,
    overall_rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT fk_student_evaluations_student
        FOREIGN KEY (student_id) REFERENCES users(user_id),
    CONSTRAINT fk_student_evaluations_section
        FOREIGN KEY (section_id) REFERENCES sections(section_id),
    CONSTRAINT uq_student_evaluation
        UNIQUE (student_id, section_id),
    CONSTRAINT chk_student_clarity CHECK (teaching_clarity BETWEEN 1 AND 5),
    CONSTRAINT chk_student_workload CHECK (workload_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_student_grading CHECK (grading_fairness BETWEEN 1 AND 5),
    CONSTRAINT chk_student_overall CHECK (overall_rating BETWEEN 1 AND 5)
) ENGINE = InnoDB;

CREATE TABLE professor_evaluations (
    evaluation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    professor_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NOT NULL,
    participation_rating TINYINT UNSIGNED NOT NULL,
    preparedness_rating TINYINT UNSIGNED NOT NULL,
    teamwork_rating TINYINT UNSIGNED NOT NULL,
    professionalism_rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT fk_professor_evaluations_professor
        FOREIGN KEY (professor_id) REFERENCES users(user_id),
    CONSTRAINT fk_professor_evaluations_student
        FOREIGN KEY (student_id) REFERENCES users(user_id),
    CONSTRAINT fk_professor_evaluations_section
        FOREIGN KEY (section_id) REFERENCES sections(section_id),
    CONSTRAINT uq_professor_student_section
        UNIQUE (professor_id, student_id, section_id),
    CONSTRAINT chk_participation CHECK (participation_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_preparedness CHECK (preparedness_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_teamwork CHECK (teamwork_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_professionalism CHECK (professionalism_rating BETWEEN 1 AND 5)
) ENGINE = InnoDB;

CREATE TABLE moderation_log (
    log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    evaluation_type ENUM('student', 'professor') NOT NULL,
    evaluation_id INT UNSIGNED NOT NULL,
    action ENUM('hidden', 'restored', 'deleted') NOT NULL,
    reason VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_moderation_admin
        FOREIGN KEY (admin_id) REFERENCES users(user_id)
) ENGINE = InnoDB;

INSERT INTO users
    (user_id, first_name, last_name, email, password_hash, role, class_year)
VALUES
    (1, 'Alex', 'Chen', 'alex.chen@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'student', 2027),
    (2, 'Jordan', 'Lee', 'jordan.lee@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'student', 2026),
    (3, 'Morgan', 'Patel', 'morgan.patel@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'student', 2028),
    (4, 'Taylor', 'Brooks', 'taylor.brooks@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'student', 2027),
    (5, 'Riley', 'Nguyen', 'riley.nguyen@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'student', 2026),
    (6, 'Emily', 'Smith', 'emily.smith@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'professor', NULL),
    (7, 'Daniel', 'Johnson', 'daniel.johnson@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'professor', NULL),
    (8, 'Sophia', 'Williams', 'sophia.williams@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'professor', NULL),
    (9, 'Avery', 'Davis', 'avery.davis@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'admin', NULL),
    (10, 'Casey', 'Wilson', 'casey.wilson@example.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9n1e4q8J3K4V5m2u', 'admin', NULL);

INSERT INTO courses
    (course_id, course_code, course_name, department, description)
VALUES
    (1, 'CS111', 'Introduction to Computer Science', 'Computer Science', 'Introduction to programming and computational thinking.'),
    (2, 'CS220', 'Multivariable Calculus', 'Mathematics', 'Functions of several variables, derivatives, and integration.'),
    (3, 'CS240', 'Data Structures', 'Computer Science', 'Lists, trees, graphs, searching, and algorithm analysis.'),
    (4, 'BIO110', 'General Biology', 'Biology', 'Foundations of cellular and molecular biology.'),
    (5, 'HIST205', 'Modern World History', 'History', 'Major political, social, and cultural developments since 1800.'),
    (6, 'ECON201', 'Principles of Economics', 'Economics', 'Basic principles of microeconomics and macroeconomics.'),
    (7, 'ENG150', 'Academic Writing', 'English', 'Research, argumentation, revision, and clear academic writing.'),
    (8, 'PHYS120', 'Introductory Physics', 'Physics', 'Mechanics, energy, momentum, and introductory laboratory work.');

INSERT INTO terms
    (term_id, term_name, start_date, end_date)
VALUES
    (1, 'Fall 2025', '2025-08-25', '2025-12-12'),
    (2, 'Spring 2026', '2026-01-20', '2026-05-08'),
    (3, 'Summer 2026', '2026-05-18', '2026-07-10'),
    (4, 'Fall 2026', '2026-08-24', '2026-12-11'),
    (5, 'Winter 2027', '2027-01-04', '2027-01-22'),
    (6, 'Spring 2027', '2027-01-25', '2027-05-14'),
    (7, 'Summer 2027', '2027-05-24', '2027-07-16'),
    (8, 'Fall 2027', '2027-08-23', '2027-12-10');

INSERT INTO sections
    (section_id, course_id, term_id, section_number, schedule, room, professor_id)
VALUES
    (1, 1, 4, '01', 'MWF 9:00-9:50 AM', 'Science 101', 6),
    (2, 2, 4, '01', 'TTh 10:00-11:20 AM', 'Science 202', 7),
    (3, 3, 4, '01', 'MWF 11:00-11:50 AM', 'Science 105', 6),
    (4, 4, 4, '01', 'TTh 1:00-2:20 PM', 'Science 110', 8),
    (5, 5, 4, '01', 'MWF 1:00-1:50 PM', 'Glatfelter 201', 7),
    (6, 6, 4, '01', 'TTh 2:30-3:50 PM', 'Glatfelter 105', 8),
    (7, 7, 4, '01', 'MWF 2:00-2:50 PM', 'CUB 210', 8),
    (8, 8, 4, '01', 'TTh 9:00-10:20 AM', 'Science 115', 7);

INSERT INTO enrollments
    (enrollment_id, student_id, section_id, status, completed_at)
VALUES
    (1, 1, 1, 'completed', '2026-12-11'),
    (2, 2, 1, 'completed', '2026-12-11'),
    (3, 3, 2, 'completed', '2026-12-11'),
    (4, 4, 3, 'completed', '2026-12-11'),
    (5, 5, 4, 'completed', '2026-12-11'),
    (6, 1, 5, 'completed', '2026-12-11'),
    (7, 2, 6, 'completed', '2026-12-11'),
    (8, 3, 7, 'completed', '2026-12-11'),
    (9, 4, 8, 'completed', '2026-12-11'),
    (10, 5, 3, 'completed', '2026-12-11');

INSERT INTO student_evaluations
    (evaluation_id, student_id, section_id, teaching_clarity, workload_rating, grading_fairness, overall_rating, comment)
VALUES
    (1, 1, 1, 5, 4, 5, 5, 'Clear explanations and useful programming assignments.'),
    (2, 2, 1, 4, 4, 4, 4, 'The projects were challenging but prepared me for exams.'),
    (3, 3, 2, 5, 5, 4, 4, 'Difficult material, but lectures and examples were organized.'),
    (4, 4, 3, 5, 4, 5, 5, 'Excellent demonstrations and fair grading standards.'),
    (5, 5, 4, 4, 3, 4, 4, 'Labs were engaging and the workload was manageable.'),
    (6, 1, 5, 4, 3, 5, 4, 'Interesting readings and thoughtful class discussions.'),
    (7, 2, 6, 3, 4, 3, 3, 'The course was useful, but some assignments needed clearer instructions.'),
    (8, 3, 7, 5, 3, 5, 5, 'Helpful feedback and strong support during the writing process.');

INSERT INTO professor_evaluations
    (evaluation_id, professor_id, student_id, section_id, participation_rating, preparedness_rating, teamwork_rating, professionalism_rating, comment)
VALUES
    (1, 6, 1, 1, 5, 5, 5, 5, 'Consistently prepared and contributed constructive ideas.'),
    (2, 6, 2, 1, 4, 4, 5, 4, 'Completed work on time and collaborated well with peers.'),
    (3, 7, 3, 2, 4, 5, 4, 5, 'Prepared for class and asked thoughtful questions.'),
    (4, 6, 4, 3, 4, 4, 4, 4, 'Reliable group member who made steady progress.'),
    (5, 8, 5, 4, 5, 4, 5, 5, 'Strong laboratory participation and professional communication.'),
    (6, 7, 2, 6, 3, 4, 4, 4, 'Improved participation throughout the term.'),
    (7, 8, 3, 7, 5, 5, 5, 5, 'Excellent preparation and respectful peer feedback.'),
    (8, 7, 4, 8, 4, 4, 4, 5, 'Dependable student who handled lab work responsibly.');

INSERT INTO moderation_log
    (log_id, admin_id, evaluation_type, evaluation_id, action, reason)
VALUES
    (1, 9, 'student', 1, 'restored', 'Review confirmed that the comment followed community guidelines.'),
    (2, 9, 'student', 2, 'hidden', 'Temporary review requested after a user report.'),
    (3, 10, 'student', 3, 'restored', 'Comment was reviewed and restored.'),
    (4, 10, 'student', 4, 'hidden', 'Comment included content requiring administrator review.'),
    (5, 9, 'professor', 1, 'restored', 'Evaluation was reviewed and approved.'),
    (6, 10, 'professor', 2, 'hidden', 'Evaluation was temporarily hidden pending verification.'),
    (7, 9, 'student', 5, 'deleted', 'Evaluation was removed at the author''s request.');
