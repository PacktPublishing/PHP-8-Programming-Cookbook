CREATE TABLE location (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    contact_name VARCHAR(64),
    location_name VARCHAR(64) NOT NULL,
    addr1 VARCHAR(64) NOT NULL,
    addr2 VARCHAR(64),
    city VARCHAR(64) NOT NULL,
    state_province VARCHAR(64),
    postal_code VARCHAR(16) NOT NULL,
    country CHAR(2) NOT NULL
);
