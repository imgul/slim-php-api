-- DB Schema

CREATE DATABASE IF NOT EXISTS easecloud_api;

CREATE USER IF NOT EXISTS 'easeAdmin.123'@'localhost' IDENTIFIED BY '.Hys^#&Oghks!GppwBb)0O';
-- GRANT ALL PRIVILEGES ON *.* TO 'easeAdmin.123'@'localhost';
GRANT ALL PRIVILEGES ON easecloud_api.* TO 'easeAdmin.123'@'localhost';
FLUSH PRIVILEGES;
USE easecloud_api;


-- Create tables

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,

    role VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    deleted_at DATETIME NULL,
    deleted_by INT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS contact_forms (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    action VARCHAR(255) NOT NULL UNIQUE,
    host VARCHAR(255) NOT NULL,
    generated_by INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    deleted_by INT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (generated_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS cf_labels (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    color VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    deleted_by INT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS cf_inbox_types (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    color VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    deleted_by INT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS cf_submissions (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    user_id INT NULL,
    ip VARCHAR(255) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    request_uri VARCHAR(255) NOT NULL,
    http_host VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    comments TEXT NULL,
    comments_at DATETIME NULL,
    comments_by INT NULL,

    cf_inbox_type_id INT NULL,
    contact_form_id INT NULL,
    cf_label_id INT NULL,
    read_at DATETIME NULL,
    deleted_at DATETIME NULL,
    starred_at DATETIME NULL,
    archived_at DATETIME NULL,
    spam_score INT NULL,
    spam_reported_at DATETIME NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (comments_by) REFERENCES users(id),
    FOREIGN KEY (cf_inbox_type_id) REFERENCES cf_inbox_types(id),
    FOREIGN KEY (contact_form_id) REFERENCES contact_forms(id),
    FOREIGN KEY (cf_label_id) REFERENCES cf_labels(id)
);

-- Seeding the Database

INSERT INTO users(
    name,
    email,
    password
) VALUES (
    'Admin',
    'admin@mail.com',
    'password'
);

INSERT INTO cf_inbox_types( name )
VALUES
    ('default'),
    ('marketing'),
    ('support'),
    ('sales'),
    ('billing'),
    ('other');
