CREATE SCHEMA ezrent;

CREATE TABLE ezrent.users
(
    userid INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    userpass VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin TINYINT(1) DEFAULT 0;

);

CREATE TABLE ezrent.items
(
    id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    available INT(11) NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO ezrent.items (name, description, available) VALUES
('Smartphone', 'A modern smartphone with a 6.7-inch display, Built-in NFC Feature, and 5G connectivity.', 10),
('GoPro', 'A compact, waterproof GoPro action camera for adventure enthusiasts, capable of 4K recording.', 4),
('Drone', 'A lightweight drone with 4K camera and auto-pilot features, ideal for aerial photography and videography.', 2),
('Headphones', 'Noise-cancelling over-ear headphones with 40 hours of battery life, ideal for music and gaming.', 15),

-- Untuk reset AUTO_INCREMENT jadi 1 kalo isi tabel dihapus
-- ALTER TABLE [nama_tabel] AUTO_INCREMENT = 1;
-- Tidak direkomendasikan GPT karena pelaksanaanya manual (mending Trigger)

-- Buat akun Admin untuk keperluan tambah dan hapus barang
INSERT INTO users (username, userpass, is_admin) VALUES ('admin', PASSWORD('password'), 1);