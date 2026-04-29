-- Run these statements in order in DreamHost phpMyAdmin.
-- 1) If you have not created the database yet, create it in the DreamHost panel.
--    DreamHost shared hosting often requires database creation through the portal,
--    not phpMyAdmin. Use the same database name in includes/db_config.php.
-- 2) After selecting that database in phpMyAdmin, run the table + seed SQL below.
--    This replaces the older demo users table, so export old rows first if needed.

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    home_address VARCHAR(255) NOT NULL,
    home_phone VARCHAR(32) NOT NULL,
    cell_phone VARCHAR(32) NOT NULL,
    joined DATE NOT NULL,
    plan VARCHAR(64) NOT NULL DEFAULT 'Starter',
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email),
    KEY users_name_index (last_name, first_name),
    KEY users_home_phone_index (home_phone),
    KEY users_cell_phone_index (cell_phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (first_name, last_name, email, home_address, home_phone, cell_phone, joined, plan) VALUES
    ('Mary', 'Smith', 'mary.smith@riftmind.gg', '1200 Baron Lane, Los Angeles, CA 90012', '213-555-0101', '310-555-2101', '2025-01-15', 'Pro'),
    ('John', 'Wang', 'john.wang@riftmind.gg', '842 Nexus Drive, Irvine, CA 92618', '949-555-0102', '949-555-2102', '2025-02-01', 'Starter'),
    ('Alex', 'Bington', 'alex.bington@riftmind.gg', '55 Mid Lane Court, Pasadena, CA 91101', '626-555-0103', '626-555-2103', '2025-02-10', 'Pro'),
    ('Serena', 'Patel', 'serena.patel@riftmind.gg', '410 Vision Way, San Diego, CA 92101', '619-555-0104', '858-555-2104', '2025-02-18', 'Elite'),
    ('Darius', 'Coleman', 'darius.coleman@riftmind.gg', '77 Jungle Path, Oakland, CA 94607', '510-555-0105', '510-555-2105', '2025-03-03', 'Starter'),
    ('Mina', 'Kwon', 'mina.kwon@riftmind.gg', '901 Dragon Pit Road, San Jose, CA 95112', '408-555-0106', '408-555-2106', '2025-03-12', 'Pro'),
    ('Ethan', 'Garcia', 'ethan.garcia@riftmind.gg', '314 Herald Street, Fresno, CA 93721', '559-555-0107', '559-555-2107', '2025-03-21', 'Starter'),
    ('Nora', 'Lee', 'nora.lee@riftmind.gg', '26 Rift Avenue, Sacramento, CA 95814', '916-555-0108', '916-555-2108', '2025-04-02', 'Elite'),
    ('Caleb', 'Johnson', 'caleb.johnson@riftmind.gg', '503 Recall Circle, Long Beach, CA 90802', '562-555-0109', '562-555-2109', '2025-04-14', 'Pro'),
    ('Aisha', 'Morgan', 'aisha.morgan@riftmind.gg', '188 Ward Plaza, Anaheim, CA 92805', '714-555-0110', '714-555-2110', '2025-04-27', 'Starter'),
    ('Leo', 'Martinez', 'leo.martinez@riftmind.gg', '63 Gank Trail, Santa Ana, CA 92701', '657-555-0111', '657-555-2111', '2025-05-09', 'Pro'),
    ('Priya', 'Nair', 'priya.nair@riftmind.gg', '720 Macro Map Blvd, Glendale, CA 91203', '818-555-0112', '818-555-2112', '2025-05-19', 'Elite'),
    ('Owen', 'Brooks', 'owen.brooks@riftmind.gg', '9 Minion Wave Lane, Burbank, CA 91502', '747-555-0113', '747-555-2113', '2025-06-01', 'Starter'),
    ('Grace', 'Chen', 'grace.chen@riftmind.gg', '441 Item Build Street, Torrance, CA 90501', '424-555-0114', '424-555-2114', '2025-06-16', 'Pro'),
    ('Mateo', 'Rivera', 'mateo.rivera@riftmind.gg', '228 Objective Row, Pomona, CA 91766', '909-555-0115', '909-555-2115', '2025-06-25', 'Starter'),
    ('Hannah', 'Wilson', 'hannah.wilson@riftmind.gg', '600 Cooldown Court, Riverside, CA 92501', '951-555-0116', '951-555-2116', '2025-07-08', 'Elite'),
    ('Samir', 'Haddad', 'samir.haddad@riftmind.gg', '19 Skillshot Terrace, Culver City, CA 90232', '310-555-0117', '424-555-2117', '2025-07-22', 'Pro'),
    ('Ivy', 'Thompson', 'ivy.thompson@riftmind.gg', '812 Coach Review Road, Santa Monica, CA 90401', '310-555-0118', '323-555-2118', '2025-08-04', 'Starter'),
    ('Noah', 'Kim', 'noah.kim@riftmind.gg', '93 Blue Buff Drive, Garden Grove, CA 92840', '714-555-0119', '714-555-2119', '2025-08-17', 'Pro'),
    ('Elena', 'Rossi', 'elena.rossi@riftmind.gg', '37 Teamfight Loop, Berkeley, CA 94704', '510-555-0120', '415-555-2120', '2025-09-01', 'Elite');
