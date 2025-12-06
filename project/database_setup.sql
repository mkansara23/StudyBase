CREATE DATABASE IF NOT EXISTS studybase;
USE studybase;

CREATE TABLE IF NOT EXISTS Users (
    user_id VARCHAR(10) PRIMARY KEY,
    user_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    major VARCHAR(50),
    password VARCHAR(100) NOT NULL
);

-- Insert sample users for testing
INSERT INTO Users (user_id, user_name, email, major, password) VALUES
('EID5001', 'John Smith', 'john.smith@university.edu', 'Computer Science', 'password123'),
('EID5002', 'Jane Doe', 'jane.doe@university.edu', 'Information Systems', 'xyz');


