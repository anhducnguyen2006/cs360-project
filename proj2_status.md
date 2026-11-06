# ClassExp Project 2 Status

## Project Overview

ClassExp is a web-based class evaluation platform. Students can review courses they have completed, while professors can evaluate students from sections they have taught. The system aggregates ratings for workload, teaching clarity, grading fairness, and overall experience so that students can make more informed enrollment choices.

The project will use PHP, MySQL, HTML, CSS, and JavaScript. The database backup is provided in [`db_backup_proj2.sql`](./db_backup_proj2.sql).

## Work Completed So Far

### Planning and design

- Selected **ClassExp** as the project idea.
- Defined the student, professor, and administrator user types.
- Identified student and professor evaluation workflows.
- Identified administrator workflows for courses, sections, instructors, and content moderation.
- Designed the landing page concept shown in the ClassExp mockup:
  - ClassExp branding.
  - Login and signup buttons.
  - Course/student search bar.
  - Example course and student evaluation cards.
  - “Real Experience. Better Choices.” hero message.

### Reference implementation review

- Reviewed the provided PHP reference implementation.
- Confirmed the planned organization:
  - A database connection file.
  - Reusable utility files.
  - Dashboard files that route operations and display shared navigation.
  - A switch-based `op` parameter for dashboard actions.
- Identified improvements needed for ClassExp:
  - Prepared statements for all database queries.
  - Input validation for ratings and form fields.
  - Output escaping for user-submitted comments.
  - Authentication and role checks.
  - Authorization checks before updating or deleting evaluations.

### Database planning

- Designed tables for:
  - Users.
  - Courses.
  - Academic terms.
  - Course sections.
  - Student enrollments.
  - Student evaluations.
  - Professor evaluations.
  - Moderation history.
- Added sample schema and data to [`db_backup_proj2.sql`](./db_backup_proj2.sql).
- Included 7–10 sample rows in each table for testing.
- Added primary keys, foreign keys, unique constraints, rating checks, and evaluation moderation flags.

### Current coding status

The ClassExp application pages and database tables have not yet been connected to the MySQL server. The current coding work is limited to the project design, the database backup draft, and review of the provided PHP organization reference. The existing reference files are not ClassExp functionality and should not be counted as completed project features.

## Work Planned for the Second Week of November

- Create the ClassExp database tables in the assigned MySQL database.
- Import and verify [`db_backup_proj2.sql`](./db_backup_proj2.sql).
- Verify that every table contains the required sample data.
- Build the public landing page.
- Build the first student dashboard page.
- Add shared navigation and session handling.
- Implement course and professor search.
- Display course information and average student ratings.
- Implement the first student evaluation submission workflow.
- Test basic insert, select, update, and delete operations.

## Work Planned for the Third Week of November

- Complete student evaluation editing and deletion.
- Implement the professor dashboard.
- Implement professor evaluations of students.
- Implement administrator course, term, section, and professor management.
- Add administrator moderation of inappropriate evaluations.
- Add rating summary cards and graphs.
- Add validation, authorization, and error messages across all forms.
- Improve responsive styling and connect the JavaScript interactions.
- Test valid submissions, invalid ratings, unauthorized actions, and empty search results.
- Prepare the final demonstration and submission materials.

## Assignment Plan

Replace the placeholder member labels below with the actual group member names before submitting.

| Assignment | Responsible member |
|---|---|
| Main dashboard layout, navigation, and page integration | Member 1 |
| Landing page and responsive CSS | Member 2 |
| Student search, course details, and rating summaries | Member 3 |
| Student evaluation create, update, and delete functions | Member 1 |
| Professor dashboard and professor evaluation functions | Member 2 |
| Administrator course, section, and professor management | Member 3 |
| Database schema, sample data, backup verification, and integration testing | All members, coordinated by Member 1 |

## Proposed File Responsibilities

Each member should work mainly in their assigned utility file so that the shared dashboard remains small.

| File | Responsibility |
|---|---|
| `db_connect.php` | Create the PDO connection to the assigned MySQL database. |
| `util/auth_util.php` | Sessions, login checks, roles, password verification, and authorization. |
| `util/course_util.php` | Course search, section lookup, professor lookup, and course summaries. |
| `util/evaluation_util.php` | Student/professor evaluation CRUD operations and rating calculations. |
| `util/admin_util.php` | Administrative CRUD operations and moderation. |
| `util/display_util.php` | Shared forms, cards, tables, navigation, and messages. |
| `index.php` | Public ClassExp landing page. |
| `student_dashboard.php` | Student navigation and student operations. |
| `professor_dashboard.php` | Professor navigation and professor operations. |
| `admin_dashboard.php` | Administrator navigation and administrative operations. |

## Project 2 Minimum Completion Target

For the immediate submission, the application should have:

1. A working landing page.
2. At least one working user dashboard.
3. Course search or course display.
4. At least one concrete evaluation operation.
5. A MySQL schema with sample data.
6. A backup SQL file containing both schema and data.
7. Clear assignments and a schedule for the remaining implementation work.
