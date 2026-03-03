-- PNOC Staff Document Compiler - Full Database Schema
-- Target: MySQL / MariaDB (XAMPP)

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS pnoc_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE pnoc_db;

-- Rebuild table to guarantee clean and consistent structure.
DROP TABLE IF EXISTS staff_documents;

CREATE TABLE staff_documents (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  document_number VARCHAR(120) NOT NULL,
  copy_number VARCHAR(120) NOT NULL,
  copy_holder VARCHAR(150) NOT NULL,
  document_title VARCHAR(255) NOT NULL,
  issuance_date DATE NULL,
  revision_number INT NOT NULL DEFAULT 0,
  retrieval_date DATE NULL,
  retrieved_revision VARCHAR(120) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_document_copy (document_number, copy_number),
  KEY idx_document_number (document_number),
  KEY idx_copy_holder (copy_holder),
  KEY idx_issuance_date (issuance_date),
  KEY idx_retrieval_date (retrieval_date),
  CONSTRAINT chk_revision_number_non_negative CHECK (revision_number >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data
INSERT INTO staff_documents (
  document_number,
  copy_number,
  copy_holder,
  document_title,
  issuance_date,
  revision_number,
  retrieval_date,
  retrieved_revision
)
VALUES
  ('SEC-01', 'CC No. 14', 'Sample Holder', 'Safety and Environment Manual', '2026-03-03', 1, '2026-03-03', '1'),
  ('HR-02', 'CC No. 07', 'Personnel Office', 'Employee Handbook', '2026-02-20', 3, NULL, NULL),
  ('OPS-10', 'CC No. 21', 'Operations Team', 'Operational Readiness Procedure', '2026-01-15', 2, '2026-02-10', '1');
