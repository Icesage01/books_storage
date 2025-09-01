CREATE DATABASE IF NOT EXISTS books_storage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE books_storage;

CREATE USER IF NOT EXISTS 'books_user'@'%' IDENTIFIED BY 'books_password';
GRANT ALL PRIVILEGES ON books_storage.* TO 'books_user'@'%';
FLUSH PRIVILEGES;