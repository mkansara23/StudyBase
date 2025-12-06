# StudyBase

# README — StudyBase System

## Overview
StudyBase is a web-based platform designed to help university students create, join, and search for study groups associated with their courses. The system consists of two major components: (1) a MySQL relational database containing normalized tables for users, courses, locations, sections, study groups, and enrollment data, and (2) a set of HTML user interfaces that will later be connected to a PHP backend for full database interaction. This README describes how to install the database, load sample data, and navigate the web interface.

## 1. System Requirements
To run the StudyBase system, you will need:
- MySQL Server (8.0+ recommended)
- MySQL Workbench or terminal client
- A local web server such as XAMPP (recommended), WAMP, or MAMP
- A modern web browser (Chrome, Firefox, Edge, Safari)

## 2. Installation Instructions

### Step 1 — Start Your MySQL Server
Launch MySQL through XAMPP, WAMP, MAMP, or a standalone installation.

### Step 2 — Create the Database and Tables
1. Open MySQL Workbench or your terminal client.  
2. Navigate to the directory containing the project files.  
3. Run the following:

SOURCE create.sql;

This will drop old tables if they exist, create all seven normalized tables, and establish primary key and foreign key constraints.

### Step 3 — Load Sample Data
Once the tables are created, execute:

SOURCE load.sql;

This loads demo data from the CSV files:
- Users.csv
- Courses.csv
- Location.csv
- Sections.csv
- Study_Groups.csv
- Study_Group_Participant.csv
- Enrolled_Courses.csv

Your database is now fully set up and ready for use.

## 3. Running the Web Interface

### Step 1 — Start Your Web Server
If using XAMPP:
1. Open the XAMPP Control Panel  
2. Start **Apache**  
3. Start **MySQL**

### Step 2 — Place the HTML Files
Move all HTML files into your server’s document root:

- XAMPP: htdocs/StudyBase/  
- WAMP: www/StudyBase/  
- MAMP: htdocs/StudyBase/

(If you are only testing static pages, you may also open the files directly in your browser.)

### Step 3 — Open the Application
Navigate to:

http://localhost/StudyBase/index.html

This will load the StudyBase home page.

## 4. How to Use the System

### Register a New User
Open `register_user.html` and enter a user ID, name, major, and email. In the final version, this page will insert a record into the Users table.

### Create a Study Group
Open `create_study_group.html` and enter details such as host ID, course ID, day of the week, meeting times, and location. This corresponds to inserting a record into the Study_Groups table.

### Join a Study Group
Open `join_study_group.html` and submit a participant ID and group ID. This will insert into Study_Group_Participant.

### Enroll in a Course
Open `enroll_course.html` and enter a course ID and user ID. This corresponds to inserting into Enrolled_Courses.

### Search for Study Groups
Open `search_study_groups.html` and filter by course ID or day of the week. Results appear in `results_study_groups.html`.

All forms will be connected to a PHP backend in Phase 4 to perform actual SQL operations.

## 5. File Structure

StudyBase/
│
├── create.sql  
├── load.sql  
├── Users.csv  
├── Courses.csv  
├── Location.csv  
├── Sections.csv  
├── Study_Groups.csv  
├── Study_Group_Participant.csv  
├── Enrolled_Courses.csv  
│
├── index.html  
├── register_user.html  
├── create_study_group.html  
├── join_study_group.html  
├── enroll_course.html  
├── search_study_groups.html  
├── results_study_groups.html  
│
└── README.md
