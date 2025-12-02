-- Based on Task C PDF
DROP DATABASE IF EXISTS studybase;
CREATE DATABASE studybase;
USE studybase;

-- 1. USERS
CREATE TABLE Users (
    user_id CHAR(10) NOT NULL,
    major VARCHAR(30) DEFAULT NULL,
    user_name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    CONSTRAINT PK_Users PRIMARY KEY (user_id)
);

-- 2. COURSES
CREATE TABLE Courses (
    course_id VARCHAR(10) NOT NULL,
    course_name VARCHAR(30) NOT NULL,
    CONSTRAINT PK_Courses PRIMARY KEY (course_id)
);

-- 3. SECTIONS
CREATE TABLE Sections (
    section_id VARCHAR(10) NOT NULL,
    professor VARCHAR(30) NOT NULL,
    semester CHAR(4) NOT NULL,
    section_of VARCHAR(10) NOT NULL,
    CONSTRAINT PK_Sections PRIMARY KEY (section_id, section_of),
    CONSTRAINT FK_Sections_Course FOREIGN KEY (section_of) 
        REFERENCES Courses(course_id) 
        ON UPDATE RESTRICT ON DELETE RESTRICT
);

-- 4. LOCATION
CREATE TABLE Location (
    location_id CHAR(10) NOT NULL,
    building VARCHAR(30) NOT NULL,
    room VARCHAR(30) NOT NULL,
    CONSTRAINT PK_Location PRIMARY KEY (location_id)
);

-- 5. STUDY_GROUPS
CREATE TABLE Study_Groups (
    group_id CHAR(10) NOT NULL,
    description VARCHAR(1024) DEFAULT NULL,
    max_participants SMALLINT NOT NULL DEFAULT 10,
    current_participants SMALLINT NOT NULL DEFAULT 0,
    host_id CHAR(10) NOT NULL,
    course_id CHAR(10) DEFAULT NULL,
    location_id CHAR(10) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    day ENUM('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
    repeat_flag BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT PK_StudyGroups PRIMARY KEY (group_id),
    CONSTRAINT FK_StudyGroups_Host FOREIGN KEY (host_id) 
        REFERENCES Users(user_id) 
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT FK_StudyGroups_Course FOREIGN KEY (course_id) 
        REFERENCES Courses(course_id) 
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT FK_StudyGroups_Location FOREIGN KEY (location_id) 
        REFERENCES Location(location_id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- 6. STUDY_GROUP_PARTICIPANT
CREATE TABLE Study_Group_Participant (
    participant_id CHAR(10) NOT NULL,
    group_id CHAR(10) NOT NULL,
    CONSTRAINT PK_StudyGroupParticipant PRIMARY KEY (participant_id, group_id),
    CONSTRAINT FK_Participant_User FOREIGN KEY (participant_id) 
        REFERENCES Users(user_id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_Participant_Group FOREIGN KEY (group_id) 
        REFERENCES Study_Groups(group_id) 
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 7. ENROLLED_COURSES
CREATE TABLE Enrolled_Courses (
    course_id VARCHAR(10) NOT NULL,
    user_id CHAR(10) NOT NULL,
    CONSTRAINT PK_EnrolledCourses PRIMARY KEY (course_id, user_id),
    CONSTRAINT FK_Enrolled_Course FOREIGN KEY (course_id) 
        REFERENCES Courses(course_id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_Enrolled_User FOREIGN KEY (user_id) 
        REFERENCES Users(user_id) 
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- INSERT DUMMY DATA (From Task C CSVs)

-- Users
INSERT INTO Users (user_id, major, user_name, email) VALUES 
('U000000001', 'Computer Science', 'John Doe', 'john@utdallas.edu'),
('U000000002', 'Biology', 'Jane Smith', 'jane@utdallas.edu'),
('U000000003', 'Mathematics', 'Alice Jones', 'alice@utdallas.edu');

-- Courses
INSERT INTO Courses (course_id, course_name) VALUES 
('CS3377', 'Operating Systems'),
('BIOL1301', 'Intro to Biology'),
('MATH2413', 'Calculus I');

-- Sections
INSERT INTO Sections (section_id, professor, semester, section_of) VALUES 
('S001', 'Dr. Brown', 'FA25', 'CS3377'),
('S002', 'Dr. White', 'FA25', 'BIOL1301'),
('S003', 'Dr. Green', 'FA25', 'MATH2413');

-- Location
INSERT INTO Location (location_id, building, room) VALUES 
('L001', 'Founders', '1.102'),
('L002', 'Engineering', '2.305'),
('L003', 'Science', '3.101');

-- Study_Groups
INSERT INTO Study_Groups (group_id, description, max_participants, current_participants, host_id, course_id, location_id, start_time, end_time, day, repeat_flag) VALUES 
('G0001', 'OS group study', 10, 5, 'U000000001', 'CS3377', 'L001', '10:00:00', '12:00:00', 'Monday', 1),
('G0002', 'Bio review group', 8, 4, 'U000000002', 'BIOL1301', 'L002', '13:00:00', '15:00:00', 'Tuesday', 0),
('G0003', 'Calculus prep', 6, 3, 'U000000003', 'MATH2413', 'L003', '09:00:00', '11:00:00', 'Wednesday', 1);

-- Enrolled_Courses
INSERT INTO Enrolled_Courses (course_id, user_id) VALUES 
('CS3377', 'U000000001'),
('BIOL1301', 'U000000002'),
('MATH2413', 'U000000003'),
('CS3377', 'U000000002');

-- Study_Group_Participant
INSERT INTO Study_Group_Participant (participant_id, group_id) VALUES 
('U000000001', 'G0002'),
('U000000002', 'G0001'),
('U000000003', 'G0001'),
('U000000002', 'G0003');

