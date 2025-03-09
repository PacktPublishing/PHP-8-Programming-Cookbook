CREATE TABLE IF NOT EXISTS cars
(
    Make
    VARCHAR
(
    100
),Model VARCHAR
(
    100
), Year SMALLINT UNSIGNED,Price MEDIUMINT UNSIGNED,Mileage MEDIUMINT UNSIGNED,`Condition` ENUM
(
    'New',
    'Used'
),Description TEXT);

INSERT INTO cars (Make, Model, Year, Price, Mileage, `Condition`, Description)
VALUES ('Mercedes-Benz', 'CLA', 2025, 38000, 15000, 'New', 'Compact luxury car with modern design'),
       ('Toyota', 'Corolla', 2020, 20000, 30000, 'Used', 'Highly reliable and fuel-efficient compact car'),
       ('Honda', 'Civic', 2022, 22000, 20000, 'New', 'Reliable compact car'),
       ('BMW', '5 Series', 2023, 45000, 12000, 'New', 'Luxury sedan with 5 seats'),
       ('Mercedes-Benz', 'S Class', 2010, 18000, 85000, 'Used', 'Full-sized sedan with 5 seats');
