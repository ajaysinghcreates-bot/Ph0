-- Add subjects and teacher_subjects tables

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `teacher_subjects` (
  `teacher_id` int NOT NULL,
  `subject_id` int NOT NULL,
  PRIMARY KEY (`teacher_id`, `subject_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
