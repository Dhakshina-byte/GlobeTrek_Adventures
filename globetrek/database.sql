
CREATE DATABASE IF NOT EXISTS globetrek_db;
USE globetrek_db;

-- ---------- Users (customers, staff, admins) ----------
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('customer','staff','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------- Packages (picture stored directly in the database) ----------
CREATE TABLE packages (
  package_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  destination VARCHAR(150) NOT NULL,
  description TEXT,
  activities TEXT,
  price DECIMAL(10,2) NOT NULL,
  duration_days INT NOT NULL,
  duration_nights INT NOT NULL,
  image LONGBLOB NULL,
  image_type VARCHAR(50) NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ---------- Bookings ----------
CREATE TABLE bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  package_id INT NOT NULL,
  travel_date DATE NOT NULL,
  travelers INT NOT NULL,
  special_requests TEXT,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  payment_status ENUM('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (package_id) REFERENCES packages(package_id) ON DELETE CASCADE
);

-- ---------- Payments (simulated) ----------
CREATE TABLE payments (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(50) NOT NULL,
  card_holder VARCHAR(100),
  card_last4 VARCHAR(4),
  paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);

-- ---------- Customer queries / inquiries ----------
CREATE TABLE queries (
  query_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  reply TEXT NULL,
  replied_by INT NULL,
  status ENUM('open','answered') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
  FOREIGN KEY (replied_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ---------- Default administrator account ----------
-- Email:    admin@globetrek.com
-- Password: Admin@123   (please change after first login)
INSERT INTO users (name, email, password, phone, role) VALUES
('System Admin', 'admin@globetrek.com',
 '$2y$10$7crkK6HEiYvBxxZ.P9CpE.gl6c9o8loB70aRDkXpclwTVq3hf2p0q',
 '0770000000', 'admin');
