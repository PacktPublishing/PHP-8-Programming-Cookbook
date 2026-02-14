CREATE TABLE appointment (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title CHAR(16),
    location VARCHAR(255),
    contact_info VARCHAR(255),
    start_date_and_time DATETIME,
    end_date_and_time DATETIME
);
