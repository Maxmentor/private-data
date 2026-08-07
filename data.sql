

-- Users Table (Admin & Members)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    mobile VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin Insert (username: admin, pass: 1234)
INSERT INTO users (username, password, role) VALUES ('admin', '1234', 'admin');

-- Documents Table
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    doc_name VARCHAR(100),
    doc_no VARCHAR(100),
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Document Photos (For multiple uploads)
CREATE TABLE document_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doc_id INT,
    photo_path VARCHAR(255)
);

-- Account Categories
CREATE TABLE account_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Accounts Table
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    email VARCHAR(100),
    mobile VARCHAR(15),
    recovery_mail VARCHAR(100),
    backup_code TEXT,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);