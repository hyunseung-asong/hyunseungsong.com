-- Run this on your company's MySQL database (phpMyAdmin or mysql CLI).
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    joined DATE NOT NULL,
    plan VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample rows (optional); adjust or remove after your own data is loaded.
INSERT INTO users (name, email, joined, plan) VALUES
    ('Mary Smith', 'mary.smith@example.com', '2025-01-15', 'Pro'),
    ('John Wang', 'john.wang@example.com', '2025-02-01', 'Starter'),
    ('Alex Bington', 'alex.bington@example.com', '2025-02-10', 'Pro');
