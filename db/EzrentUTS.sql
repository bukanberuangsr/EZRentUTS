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

-- AUTO_INCREMENT Reset to 1 if needed (for deleted table items)
-- ALTER TABLE [nama_tabel] AUTO_INCREMENT = 1;