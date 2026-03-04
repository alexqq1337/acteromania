-- SQL pentru crearea tabelelor `reviews` și `pending_reviews`
-- Rulați acest fișier în phpMyAdmin sau prin MySQL CLI

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100),
  `email` VARCHAR(150) NOT NULL,
  `rating` TINYINT NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `approved` TINYINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS `pending_reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100),
  `email` VARCHAR(150) NOT NULL,
  `rating` TINYINT NOT NULL,
  `message` TEXT NOT NULL,
  `otp` VARCHAR(6) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME NOT NULL
);
