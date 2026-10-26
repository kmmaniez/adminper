USE administrasi_perkara;

CREATE TABLE IF NOT EXISTS case_documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  case_id INT NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE
);

ALTER TABLE payments
  ADD COLUMN proof_original_name VARCHAR(255) NULL AFTER method,
  ADD COLUMN proof_path VARCHAR(255) NULL AFTER proof_original_name,
  ADD COLUMN proof_mime_type VARCHAR(100) NULL AFTER proof_path;
