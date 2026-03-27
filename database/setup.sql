-- SkillSync Database Schema (Normalized)
-- Run this in phpMyAdmin or MySQL CLI

-- Create Database
CREATE DATABASE IF NOT EXISTS skillsync;
USE skillsync;

-- ============================================
-- STUDENTS TABLE
-- ============================================
CREATE TABLE students (
    id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    program VARCHAR(100),
    year_level INT,
    gpa DECIMAL(3,2),
    preferred_track VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- STUDENT SKILLS TABLE
-- ============================================
CREATE TABLE student_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    skill VARCHAR(100) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- STUDENT SUBJECTS TABLE
-- ============================================
CREATE TABLE student_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- INTERNSHIPS TABLE
-- ============================================
CREATE TABLE internships (
    id VARCHAR(20) PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    company VARCHAR(150),
    department VARCHAR(150),
    location VARCHAR(100),
    mode VARCHAR(50),
    track VARCHAR(100),
    min_gpa DECIMAL(3,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- INTERNSHIP SKILLS TABLE
-- ============================================
CREATE TABLE internship_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id VARCHAR(20) NOT NULL,
    skill VARCHAR(100) NOT NULL,
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    INDEX idx_internship_id (internship_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- INTERNSHIP SUBJECTS TABLE
-- ============================================
CREATE TABLE internship_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id VARCHAR(20) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    INDEX idx_internship_id (internship_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEMO STUDENTS
-- ============================================
INSERT INTO students (id, name, program, year_level, gpa, preferred_track) VALUES
('2021-001', 'Alyssa Mae Tan', 'BS Information Technology', 4, 1.68, 'Web Development'),
('2021-014', 'John Carlo Reyes', 'BS Information Technology', 4, 1.95, 'Data and QA'),
('2021-026', 'Mikaela Joy Dela Cruz', 'BS Information Technology', 3, 1.75, 'Systems and Support');

-- ============================================
-- INSERT STUDENT SKILLS
-- ============================================
-- Alyssa Mae Tan (2021-001) Skills
INSERT INTO student_skills (student_id, skill) VALUES
('2021-001', 'PHP'),
('2021-001', 'JavaScript'),
('2021-001', 'Bootstrap'),
('2021-001', 'REST API'),
('2021-001', 'MySQL');

-- John Carlo Reyes (2021-014) Skills
INSERT INTO student_skills (student_id, skill) VALUES
('2021-014', 'SQL'),
('2021-014', 'Manual Testing'),
('2021-014', 'Python'),
('2021-014', 'Documentation');

-- Mikaela Joy Dela Cruz (2021-026) Skills
INSERT INTO student_skills (student_id, skill) VALUES
('2021-026', 'Networking'),
('2021-026', 'Technical Support'),
('2021-026', 'Linux'),
('2021-026', 'Documentation');

-- ============================================
-- INSERT STUDENT COMPLETED SUBJECTS
-- ============================================
-- Alyssa Mae Tan (2021-001) Subjects
INSERT INTO student_subjects (student_id, subject) VALUES
('2021-001', 'Web Systems'),
('2021-001', 'Database Systems'),
('2021-001', 'Systems Analysis'),
('2021-001', 'Information Assurance');

-- John Carlo Reyes (2021-014) Subjects
INSERT INTO student_subjects (student_id, subject) VALUES
('2021-014', 'Database Systems'),
('2021-014', 'Software Engineering'),
('2021-014', 'Human Computer Interaction');

-- Mikaela Joy Dela Cruz (2021-026) Subjects
INSERT INTO student_subjects (student_id, subject) VALUES
('2021-026', 'Networking 1'),
('2021-026', 'Operating Systems'),
('2021-026', 'Information Assurance');

-- ============================================
-- INSERT DEMO INTERNSHIPS
-- ============================================
INSERT INTO internships (id, title, company, department, location, mode, track, min_gpa) VALUES
('INT-100', 'Junior Web Development Intern', 'Tacloban Digital Solutions', 'BSIT Internship Office', 'Tacloban City', 'Hybrid', 'Web Development', 2.0),
('INT-101', 'QA and Documentation Intern', 'Eastern Tech Labs', 'ICT Office', 'Ormoc City', 'On-site', 'Data and QA', 2.25),
('INT-102', 'IT Support Intern', 'Visayas CampusNet', 'External Campuses Coordination', 'Baybay City', 'On-site', 'Systems and Support', 2.5),
('INT-103', 'API Integration Intern', 'Leyte Software House', 'University Systems Team', 'Tacloban City', 'Remote', 'Web Development', 1.9);

-- ============================================
-- INSERT INTERNSHIP REQUIRED SKILLS
-- ============================================
-- INT-100 Required Skills
INSERT INTO internship_skills (internship_id, skill) VALUES
('INT-100', 'PHP'),
('INT-100', 'JavaScript'),
('INT-100', 'MySQL');

-- INT-101 Required Skills
INSERT INTO internship_skills (internship_id, skill) VALUES
('INT-101', 'Manual Testing'),
('INT-101', 'Documentation'),
('INT-101', 'SQL');

-- INT-102 Required Skills
INSERT INTO internship_skills (internship_id, skill) VALUES
('INT-102', 'Networking'),
('INT-102', 'Technical Support'),
('INT-102', 'Linux');

-- INT-103 Required Skills
INSERT INTO internship_skills (internship_id, skill) VALUES
('INT-103', 'REST API'),
('INT-103', 'PHP'),
('INT-103', 'JavaScript');

-- ============================================
-- INSERT INTERNSHIP PREFERRED SUBJECTS
-- ============================================
-- INT-100 Preferred Subjects
INSERT INTO internship_subjects (internship_id, subject) VALUES
('INT-100', 'Web Systems'),
('INT-100', 'Database Systems'),
('INT-100', 'Systems Analysis');

-- INT-101 Preferred Subjects
INSERT INTO internship_subjects (internship_id, subject) VALUES
('INT-101', 'Software Engineering'),
('INT-101', 'Human Computer Interaction'),
('INT-101', 'Database Systems');

-- INT-102 Preferred Subjects
INSERT INTO internship_subjects (internship_id, subject) VALUES
('INT-102', 'Networking 1'),
('INT-102', 'Operating Systems'),
('INT-102', 'Information Assurance');

-- INT-103 Preferred Subjects
INSERT INTO internship_subjects (internship_id, subject) VALUES
('INT-103', 'Web Systems'),
('INT-103', 'Information Assurance'),
('INT-103', 'Database Systems');

-- ============================================
-- CREATE INDEXES FOR PERFORMANCE
-- ============================================
CREATE INDEX idx_students_track ON students(preferred_track);
CREATE INDEX idx_students_program ON students(program);
CREATE INDEX idx_internships_track ON internships(track);
CREATE INDEX idx_skill ON student_skills(skill);
CREATE INDEX idx_internship_skill ON internship_skills(skill);

-- ============================================
-- VERIFICATION QUERIES (uncomment to check)
-- ============================================
-- SELECT * FROM students;
-- SELECT * FROM student_skills WHERE student_id = '2021-001';
-- SELECT * FROM internships;
-- SELECT * FROM internship_skills WHERE internship_id = 'INT-100';
