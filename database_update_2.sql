-- Add exams and exam_results tables

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `exams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `date` date NOT NULL,
  `max_marks` int NOT NULL DEFAULT 100,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `exam_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int NOT NULL,
  `student_id` int NOT NULL,
  `marks_obtained` int NOT NULL,
  `comments` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exam_student` (`exam_id`,`student_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_results_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
