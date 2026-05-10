-- Run this once in phpMyAdmin after selecting your existing RiftMind database.
-- This file is non-destructive: it does not drop or recreate the users table.

CREATE TABLE IF NOT EXISTS service_visits (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    company_id VARCHAR(16) NOT NULL,
    service_slug VARCHAR(100) NOT NULL,
    user_email VARCHAR(255) NULL,
    visited_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY service_visits_service_index (service_slug),
    KEY service_visits_user_index (user_email),
    KEY service_visits_company_index (company_id),
    KEY service_visits_visited_index (visited_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS service_reviews (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    company_id VARCHAR(16) NOT NULL,
    service_slug VARCHAR(100) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    review_text TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY service_reviews_service_index (service_slug),
    KEY service_reviews_user_index (user_email),
    KEY service_reviews_company_index (company_id),
    KEY service_reviews_rating_index (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
