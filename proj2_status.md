# ClassExp Project 2 Status

## Project Overview

ClassExp is a web-based class evaluation platform. Students post ratings and
comments about completed classes. Professor evaluations and administrator
management are planned for later phases of the group proposal.

Group members: Andy, Nhan, John.

The current design and project setup follow the class instructions and the
professor's PHP example. This is the week 1 checkpoint. Future design and
functionality updates will be made during weeks 2 and 3.

Landing page: [ClassExp](https://cs.gettysburg.edu/~nguynh01/cs360/proj2/dashboard_completed.php?op=home).
Main repository: [cs360-project](https://github.com/anhducnguyen2006/cs360-project).
The repository owner must confirm instructor access for `skimmn@gmail.com`.

## Work Completed So Far

### Planning and design

- Selected ClassExp and defined student, professor, and administrator user types.
- Identified the evaluation and administrative workflows in the group proposal.
- Kept the checkpoint focused on the landing page, student dashboard, and
  creating student ratings/comments.
- Used basic CSS; future layout and functionality changes will follow class
  feedback and the project schedule.

### Reference implementation review

- Followed the professor's procedural PHP organization.
- Kept PDO connection setup in `db_connect.php` and all application functions in
  one `util.php`.
- Used `dashboard_completed.php` for shared navigation and `switch ($op)` routing
  between the landing and student views.
- Added prepared SQL statements, escaped output, password/session checks, and
  basic form validation.

### Database planning

- Retained eight tables: users, courses, terms, sections, enrollments,
  student evaluations, professor evaluations, and moderation history.
- Included schema and 7-10 sample rows per table in
  [`db_backup_proj2.sql`](./db_backup_proj2.sql).
- Retained primary keys, foreign keys, unique constraints, and rating checks.
- The group has created all eight tables in the assigned school database.
  Final data counts and a matching school-database export still need confirmation.

### Current coding status

- Landing page and student sign-in/dashboard are deployed on the school server.
- Students can post four ratings and a comment for a completed, unreviewed class.
- Posting checks enrollment, past completion/term dates, ratings from 1 to 5,
  a nonempty comment, and one evaluation per student per section.
- All three PHP files pass syntax checks. The reduced flow passed 18 local
  HTTP/database checks. Live correct/incorrect-password handling and logout were
  verified. A successful live posting still needs final database verification.
- Sample classes with future completion or term end dates do not appear in the
  posting form; test data must satisfy the completed-class checks.

Search, review history, editing/deletion, signup, professor/admin pages, graphs,
and AI summaries are not implemented in this checkpoint.

## Work Planned for the Second Week of November

- Andy: add student evaluation editing and deletion.
- Nhan: refine the landing/dashboard layout and add course browsing/search.
- John: add course rating displays and evaluation history, and verify database
  data and backup consistency.
- All: test the deployed student workflow and address instructor feedback.

## Work Planned for the Third Week of November

- Andy: complete student workflow testing and error handling.
- Nhan: implement the professor dashboard and professor evaluations of students.
- John: implement administrator course/section management and moderation.
- All: review remaining proposal features, including graphs and summaries, and
  prepare the final demonstration and submission.

## Assignment Plan

| Assignment | Responsible member |
|---|---|
| Student dashboard and student evaluation functions | Andy |
| Landing page, simple CSS, and deployment | Nhan |
| Database schema, sample data, and connection setup | John |
| Professor dashboard and professor evaluation functions | Nhan |
| Administrator course/section management and moderation | John |
| Integration testing, backup verification, and submission review | All members |

## Proposed File Responsibilities

The three PHP files and CSS are in `myProfessorsWebsiteFormattingReference/`.
This folder now contains the actual ClassExp application.

| File | Responsibility |
|---|---|
| `dashboard_completed.php` | Landing/student views, navigation, sessions, and operation routing |
| `db_connect.php` | PDO connection to the assigned school MySQL database |
| `util.php` | All application functions, including forms, login, and posting evaluations |
| `style.css` | Basic page, navigation, and form formatting |
| `db_backup_proj2.sql` | Database schema and sample data at the repository root |
| `README.txt` | Required submission summary, links, plans, assignments, and meeting preferences |

## Project 2 Minimum Completion Target

1. Landing page: implemented and deployed.
2. Student dashboard: implemented and deployed.
3. Concrete functionality: posting student ratings and comments implemented and
   locally tested; final live save verification pending.
4. MySQL schema and sample data: SQL backup included; school tables created.
5. Remaining work and assignments: documented above.
6. README and meeting preferences: included locally for submission.

Before submission, verify a live evaluation row, export the current school
database as `db_backup_proj2.sql`, push the current project files, confirm
instructor repository access, and have one group member run `cvssubmit` to
`cs360/proj2/` according to the course instructions. Keep actual database
passwords out of the GitHub submission.

## Meeting Preferences

All group members can attend these 30-minute slots, highest to lowest preference:

1. Monday 5:45-6:15 PM.
2. Wednesday 5:15-5:45 PM.
3. Tuesday 5:30-6:00 PM.
