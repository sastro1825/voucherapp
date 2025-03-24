CREATE DATABASE voucher2;
USE voucher2;

-- Tabel Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    PASSWORD VARCHAR(255) NOT NULL,
    ROLE ENUM('admin', 'merchant') NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel Settings
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(255) NOT NULL UNIQUE,
    VALUE TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel Vouchers
CREATE TABLE vouchers (
    id VARCHAR(255) PRIMARY KEY, -- VCH + timestamp + random
    company_name VARCHAR(255) NOT NULL,
    VALUE VARCHAR(255) NOT NULL,
    created_date DATE NOT NULL,
    expiration_date DATE NOT NULL,
    STATUS ENUM('Active', 'Redeemed', 'Expired') NOT NULL DEFAULT 'Active',
    redeemed_by VARCHAR(255) NULL,
    redeemed_at TIMESTAMP NULL,
    send_status ENUM('Sending', 'Sent') NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel Redeemed Vouchers
CREATE TABLE redeemed_vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_id VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    redeemed_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

DROP DATABASE voucher2;
CREATE DATABASE voucher2;

USE voucher2;
SELECT * FROM migrations;