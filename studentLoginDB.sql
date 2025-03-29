-- Create the database.
CREATE DATABASE IF NOT EXISTS StudentLoginSystem;
USE StudentLoginSystem;

-- Create the Classes table. This should include full, class names, and class IDs.
CREATE TABLE Classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL UNIQUE
);

-- Create the Students table.
CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL
);

-- Create the Logins table to track student logins.
CREATE TABLE Logins (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    logout_time DATETIME NULL,
    FOREIGN KEY (student_id) REFERENCES Students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES Classes(class_id) ON DELETE CASCADE
);