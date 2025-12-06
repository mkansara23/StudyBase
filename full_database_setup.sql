DROP DATABASE IF EXISTS studybase;
CREATE DATABASE studybase;
USE studybase;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT,
    major VARCHAR(30) DEFAULT NULL,
    user_name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL DEFAULT '12345',
    CONSTRAINT PK_Users PRIMARY KEY (user_id)
);

CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT,
    course_code VARCHAR(10) NOT NULL,
    course_name VARCHAR(30) NOT NULL,
    CONSTRAINT PK_Courses PRIMARY KEY (course_id)
);

CREATE TABLE Sections (
    section_id INT AUTO_INCREMENT,
    section_code VARCHAR(10) NOT NULL,
    professor VARCHAR(30) NOT NULL,
    semester CHAR(4) NOT NULL,
    section_of INT NOT NULL,
    CONSTRAINT PK_Sections PRIMARY KEY (section_id),
    CONSTRAINT FK_Sections_Course FOREIGN KEY (section_of) 
        REFERENCES Courses(course_id) 
        ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE Location (
    location_id INT AUTO_INCREMENT,
    location_code VARCHAR(10) NOT NULL,
    building VARCHAR(30) NOT NULL,
    room VARCHAR(30) NOT NULL,
    CONSTRAINT PK_Location PRIMARY KEY (location_id)
);

CREATE TABLE Study_Groups (
    group_id INT AUTO_INCREMENT,
    description VARCHAR(1024) DEFAULT NULL,
    max_participants SMALLINT NOT NULL DEFAULT 10,
    current_participants SMALLINT NOT NULL DEFAULT 0,
    host_id INT NOT NULL,
    course_id INT DEFAULT NULL,
    section_id INT DEFAULT NULL,
    location_id INT NOT NULL,
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
    CONSTRAINT FK_StudyGroups_Section FOREIGN KEY (section_id) 
        REFERENCES Sections(section_id) 
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT FK_StudyGroups_Location FOREIGN KEY (location_id) 
        REFERENCES Location(location_id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE Study_Group_Participant (
    participant_id INT NOT NULL,
    group_id INT NOT NULL,
    CONSTRAINT PK_StudyGroupParticipant PRIMARY KEY (participant_id, group_id),
    CONSTRAINT FK_Participant_User FOREIGN KEY (participant_id) 
        REFERENCES Users(user_id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_Participant_Group FOREIGN KEY (group_id) 
        REFERENCES Study_Groups(group_id) 
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Enrolled_Courses (
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT PK_EnrolledCourses PRIMARY KEY (course_id, user_id),
    CONSTRAINT FK_Enrolled_Course FOREIGN KEY (course_id) 
        REFERENCES Courses(course_id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_Enrolled_User FOREIGN KEY (user_id) 
        REFERENCES Users(user_id) 
        ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO Users (major, user_name, email, password) VALUES 
('Computer Science', 'John Doe', 'john@utdallas.edu', 'password123'),
('Biology', 'Jane Smith', 'jane@utdallas.edu', 'bio123'),
('Mathematics', 'Alice Jones', 'alice@utdallas.edu', 'math456');

INSERT INTO Users (major, user_name, email, password) VALUES
('CS', 'Hack Test 1', 'test1@univ.edu', 'password123'),
('IS', 'Hack Test 2', 'test2@univ.edu', 'xyz');

INSERT INTO Courses (course_code, course_name) VALUES 
('CS3377', 'Operating Systems'),
('BIOL1301', 'Intro to Biology'),
('MATH2413', 'Calculus I');

INSERT INTO Sections (section_code, professor, semester, section_of) VALUES 
('S001', 'Dr. Brown', 'FA25', 1),
('S002', 'Dr. White', 'FA25', 2),
('S003', 'Dr. Green', 'FA25', 3);

INSERT INTO Location (location_code, building, room) VALUES 
('L001', 'Founders', '1.102'),
('L002', 'Engineering', '2.305'),
('L003', 'Science', '3.101');

INSERT INTO Study_Groups (description, max_participants, current_participants, host_id, course_id, section_id, location_id, start_time, end_time, day, repeat_flag) VALUES 
('OS group study', 10, 5, 1, 1, 1, 1, '10:00:00', '12:00:00', 'Monday', 1),
('Bio review group', 8, 4, 2, 2, 2, 2, '13:00:00', '15:00:00', 'Tuesday', 0),
('Calculus prep', 6, 3, 3, 3, 3, 3, '09:00:00', '11:00:00', 'Wednesday', 1);

INSERT INTO Enrolled_Courses (course_id, user_id) VALUES 
(1, 1),
(2, 2),
(3, 3),
(1, 2);

INSERT INTO Study_Group_Participant (participant_id, group_id) VALUES 
(1, 2),
(2, 1),
(3, 1),
(2, 3);
