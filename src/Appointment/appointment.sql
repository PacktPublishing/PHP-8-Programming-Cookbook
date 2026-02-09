CREATE TABLE appointment (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    location_id INT(10) UNSIGNED NOT NULL,
    start_date_and_time DATETIME NOT NULL,
    end_date_and_time DATETIME NOT NULL,
    FOREIGN KEY (location_id) REFERENCES location(id)
);
