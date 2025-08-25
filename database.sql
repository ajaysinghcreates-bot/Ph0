
-- Adminer 4.8.1 MySQL 8.0.38 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `academic_year`;
CREATE TABLE `academic_year` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `class_id` int NOT NULL,
  `status` enum('Present','Absent','Late') NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `teacher_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `failed_logins`;
CREATE TABLE `failed_logins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `fees`;
CREATE TABLE `fees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `class_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Paid','Unpaid','Partially Paid') NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `fees_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `class_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff','Viewer') NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `first_name`, `last_name`, `is_active`) VALUES
(1,	'admin',	'$2y$10$g.g4g7C.yH.5x1Z2f.N3f.L.Zz.3.U2I9.B2.k/L5.d/4.E.1m1i.',	'Admin',	'admin@example.com',	'Admin',	'User',	1);

-- 2024-08-01 12:00:00
