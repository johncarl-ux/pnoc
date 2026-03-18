-- PNOC Document Monitoring Database Schema
-- MySQL 8+

CREATE DATABASE IF NOT EXISTS pnoc_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE pnoc_db;

CREATE TABLE IF NOT EXISTS staff_records (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  document_number VARCHAR(100) NOT NULL,
  copy_number VARCHAR(100) NOT NULL,
  copy_holder VARCHAR(150) NOT NULL,
  document_title VARCHAR(255) NOT NULL,
  issuance_date DATE NOT NULL,
  revision_number INT UNSIGNED NOT NULL DEFAULT 0,
  retrieval_date DATE NULL,
  retrieved_revision VARCHAR(100) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_document_number (document_number),
  INDEX idx_copy_holder (copy_holder),
  INDEX idx_issuance_date (issuance_date),
  INDEX idx_retrieval_date (retrieval_date),
  INDEX idx_revision_number (revision_number),
  INDEX idx_doc_copy (document_number, copy_number)
);

-- Optional API-friendly view (camelCase keys used by frontend)
CREATE OR REPLACE VIEW staff_records_api AS
SELECT
  id,
  document_number AS documentNumber,
  copy_number AS copyNumber,
  copy_holder AS copyHolder,
  document_title AS documentTitle,
  issuance_date AS issuanceDate,
  revision_number AS revisionNumber,
  retrieval_date AS retrievalDate,
  retrieved_revision AS retrievedRevision,
  created_at AS createdAt,
  updated_at AS updatedAt
FROM staff_records;

-- Optional starter row
-- INSERT INTO staff_records (
--   document_number, copy_number, copy_holder, document_title,
--   issuance_date, revision_number, retrieval_date, retrieved_revision
-- ) VALUES (
--   'SEC-01', 'CC No. 3', 'Juan Dela Cruz', 'Control Procedure',
--   '2026-03-06', 0, NULL, NULL
-- );

-- =====================================================
-- Inventory Management Schema
-- =====================================================

CREATE TABLE IF NOT EXISTS inventory_items (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  item_id VARCHAR(30) NOT NULL,
  item_group ENUM('BENTACO', 'IOT') NOT NULL,
  item_no INT UNSIGNED NULL,
  property_number VARCHAR(120) NOT NULL,
  asset_number VARCHAR(120) NOT NULL,
  item_description VARCHAR(255) NOT NULL,
  building VARCHAR(120) NULL,
  room VARCHAR(120) NULL,
  department VARCHAR(120) NULL,
  storage_area VARCHAR(120) NULL,
  item_location VARCHAR(255) NULL,
  assigned_employee VARCHAR(150) NULL,
  acquisition_cost DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  reference_number VARCHAR(120) NULL,
  item_status ENUM('Usable', 'Under Maintenance', 'Retired') NOT NULL DEFAULT 'Usable',
  date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_inventory_item_id (item_id),
  INDEX idx_item_group (item_group),
  INDEX idx_item_no (item_no),
  INDEX idx_property_number (property_number),
  INDEX idx_asset_number (asset_number),
  INDEX idx_item_status (item_status),
  INDEX idx_group_property (item_group, property_number),
  INDEX idx_group_asset (item_group, asset_number)
);

CREATE TABLE IF NOT EXISTS inventory_item_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  inventory_item_id BIGINT UNSIGNED NOT NULL,
  event_type VARCHAR(40) NOT NULL,
  event_title VARCHAR(150) NOT NULL,
  event_details TEXT NULL,
  event_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_inventory_item_id (inventory_item_id),
  INDEX idx_event_type (event_type),
  INDEX idx_event_at (event_at),
  CONSTRAINT fk_inventory_item_history_item
    FOREIGN KEY (inventory_item_id)
    REFERENCES inventory_items (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Optional API-friendly view (camelCase keys used by frontend)
CREATE OR REPLACE VIEW inventory_items_api AS
SELECT
  id,
  item_id AS itemId,
  item_group AS itemGroup,
  item_no AS itemNo,
  property_number AS propertyNumber,
  asset_number AS assetNumber,
  item_description AS itemDescription,
  building,
  room,
  department,
  storage_area AS storageArea,
  item_location AS itemLocation,
  assigned_employee AS assignedEmployee,
  acquisition_cost AS acquisitionCost,
  reference_number AS referenceNumber,
  item_status AS itemStatus,
  -- allocation fields removed (handled separately if needed)
  date_added AS dateAdded,
  last_updated AS lastUpdated,
  created_at AS createdAt,
  updated_at AS updatedAt
FROM inventory_items;
