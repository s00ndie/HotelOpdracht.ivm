DROP DATABASE IF EXISTS hotelopdracht;

CREATE DATABASE IF NOT EXISTS hotelopdracht;
USE hotelopdracht;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NULL UNIQUE,
    type VARCHAR(50) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('available', 'booked') NOT NULL DEFAULT 'available',
    booking_email VARCHAR(100) NULL
);

CREATE TABLE IF NOT EXISTS room_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    filename VARCHAR(255) NULL,
    path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);


INSERT INTO admin (name, email, password) VALUES
('admin', 'admin@example.com', '$2y$10$RxYdlxiwOEEa98YO60keXuTxyUYS9LheqGeefh/lArSVknlLK5YFW'); -- password is 'admin'

INSERT INTO rooms (room_number, type, description, price, status) VALUES
('101', 'Comfort Kamer', 'Onze Comfort Kamer biedt een serene ontsnapping met alle basisvoorzieningen die u nodig heeft. Geniet van een comfortabel bed, een moderne badkamer en een prachtig uitzicht op de stad. Perfect voor een kort verblijf of een zakenreis.', 89.00, 'available'),
('102', 'Deluxe Kamer', 'Voor degenen die net dat beetje extra comfort willen, is onze Deluxe Kamer de perfecte keuze. Deze kamers zijn ruimer en beschikken over luxere voorzieningen, zoals een zithoek en een Nespresso-apparaat. De ideale plek om te ontspannen na een dag vol ontdekkingen in Alkmaar.', 119.00, 'available'),
('103', 'Junior Suite', 'Onze Junior Suites bieden een ultieme combinatie van ruimte en luxe. Met een aparte woonkamer, een ruime badkamer en een balkon met een adembenemend uitzicht, is deze kamer perfect voor een romantisch uitje of een speciale gelegenheid.', 159.00, 'available'),
('104', 'Familie Suite', 'Speciaal ontworpen voor gezinnen, biedt onze Familie Suite voldoende ruimte en comfort voor iedereen. Deze suites beschikken over twee aparte slaapkamers, een ruime woonkamer en extra voorzieningen zoals een kitchenette en speelhoek voor de kinderen. De perfecte thuisbasis voor een onvergetelijke familievakantie.', 199.00, 'available'),
('105', 'Bruidssuite', 'Onze Bruidssuite is de ultieme romantische ontsnapping voor pasgetrouwde stellen. Deze luxueuze suite biedt een ruime slaapkamer met een kingsize bed, een stijlvolle woonkamer en een eigen balkon met een prachtig uitzicht op Alkmaar. Geniet van extra''s zoals een bubbelbad, rozenblaadjes op het bed en een fles champagne om uw speciale gelegenheid te vieren in stijl.', 249.00, 'available');

INSERT INTO room_photos (room_id, filename, path) VALUES
((SELECT id FROM rooms WHERE room_number = '101'), 'comfort-kamer.jpg', 'images/rooms/comfort-kamer.jpg'),
((SELECT id FROM rooms WHERE room_number = '102'), 'deluxe-kamer.jpg', 'images/rooms/deluxe-kamer.jpg'),
((SELECT id FROM rooms WHERE room_number = '103'), 'junior-suite.jpg', 'images/rooms/junior-suite.jpg'),
((SELECT id FROM rooms WHERE room_number = '104'), 'familie-suite.jpg', 'images/rooms/familie-suite.jpg'),
((SELECT id FROM rooms WHERE room_number = '105'), 'bruidssuite.jpg', 'images/rooms/bruidssuite.jpg');