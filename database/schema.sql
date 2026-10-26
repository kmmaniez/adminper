CREATE DATABASE IF NOT EXISTS administrasi_perkara
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE administrasi_perkara;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS case_documents;
DROP TABLE IF EXISTS cases;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS case_categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'pengacara') NOT NULL DEFAULT 'pengacara',
  phone VARCHAR(30) NULL,
  address TEXT NULL,
  specialization VARCHAR(120) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE case_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  saw_score INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  nik VARCHAR(40) NULL,
  phone VARCHAR(30) NULL,
  address TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  case_number VARCHAR(80) NOT NULL UNIQUE,
  title VARCHAR(180) NOT NULL,
  client_id INT NOT NULL,
  lawyer_id INT NULL,
  category_id INT NOT NULL,
  hearing_date DATE NULL,
  document_status ENUM('sangat lengkap','lengkap','cukup lengkap','kurang lengkap','tidak lengkap') NOT NULL DEFAULT 'cukup lengkap',
  administration_status ENUM('belum diproses','berkas sudah didaftarkan','menunggu jadwal sidang','sidang sedang berlangsung','perkara selesai') NOT NULL DEFAULT 'belum diproses',
  total_fee DECIMAL(14,2) NOT NULL DEFAULT 0,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
  FOREIGN KEY (lawyer_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (category_id) REFERENCES case_categories(id) ON DELETE RESTRICT
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  case_id INT NOT NULL,
  payment_date DATE NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  method VARCHAR(80) NOT NULL DEFAULT 'Transfer',
  proof_original_name VARCHAR(255) NULL,
  proof_path VARCHAR(255) NULL,
  proof_mime_type VARCHAR(100) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE
);

CREATE TABLE case_documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  case_id INT NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role, phone, address, specialization) VALUES
('Administrator Rotama', 'admin@rotama.test', '$2y$10$vTQf1h4lx/E541mnKS4pd.XNuj.fjeXjI9X8A8EQQLkL6.aV2HYOi', 'admin', '081234567890', 'Jakarta', 'Manajemen Perkara'),
('Bima Pratama, S.H.', 'pengacara@rotama.test', '$2y$10$vTQf1h4lx/E541mnKS4pd.XNuj.fjeXjI9X8A8EQQLkL6.aV2HYOi', 'pengacara', '081298765432', 'Jakarta', 'Pidana dan Perdata');

INSERT INTO case_categories (name, saw_score) VALUES
('pidana', 5),
('perdata', 4),
('PHI', 3),
('Tata Usaha Negara', 2),
('konsultasi hukum', 1);

INSERT INTO clients (name, nik, phone, address) VALUES
('Andi Saputra', '3171010101900001', '081111111111', 'Jakarta Selatan'),
('Siti Rahma', '3171010202920002', '082222222222', 'Bekasi');

INSERT INTO cases (case_number, title, client_id, lawyer_id, category_id, hearing_date, document_status, administration_status, total_fee, notes) VALUES
('PKR-001/2026', 'Pendampingan Perkara Pidana', 1, 2, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'lengkap', 'menunggu jadwal sidang', 15000000, 'Prioritas karena sidang dekat.'),
('PKR-002/2026', 'Gugatan Wanprestasi', 2, 2, 2, DATE_ADD(CURDATE(), INTERVAL 12 DAY), 'cukup lengkap', 'berkas sudah didaftarkan', 12000000, 'Menunggu tambahan bukti transfer.');

INSERT INTO payments (case_id, payment_date, amount, method, notes) VALUES
(1, CURDATE(), 8000000, 'Transfer', 'DP awal'),
(2, CURDATE(), 3000000, 'Tunai', 'Pembayaran pertama');
