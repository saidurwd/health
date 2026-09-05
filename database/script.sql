# Patient module
ALTER TABLE os_patient ADD INDEX idx_category_new (category_new);
ALTER TABLE os_patient ADD INDEX idx_created_on (created_on);
ALTER TABLE os_patient ADD INDEX idx_name (name);
ALTER TABLE os_patient ADD INDEX idx_pat_id (pat_id);

ALTER TABLE os_invoice_parent ADD INDEX idx_patient (patient);
ALTER TABLE os_invoice_parent ADD INDEX idx_invoice_date (invoice_date);

ALTER TABLE os_patient_prescription ADD INDEX idx_patient (patient);
ALTER TABLE os_patient_prescription ADD INDEX idx_diagnosis (diagnosis);

ALTER TABLE os_invoice ADD INDEX idx_parent (parent);

# ------

-- Invoice module indexes
ALTER TABLE os_invoice_parent ADD INDEX idx_invoice_number (invoice_number);
ALTER TABLE os_invoice_parent ADD INDEX idx_payment_status (payment_status);
ALTER TABLE os_invoice_parent ADD INDEX idx_patient_category_new (patient_category_new);
ALTER TABLE os_invoice_parent ADD INDEX idx_created_on (created_on);
ALTER TABLE os_invoice ADD INDEX idx_servicetype (servicetype);
ALTER TABLE os_invoice ADD INDEX idx_service (service);
ALTER TABLE os_invoice ADD INDEX idx_batch (batch);

-- Cache table column type fix (applied earlier)
ALTER TABLE os_cache MODIFY COLUMN value LONGBLOB NULL;

-- Performance indexes
ALTER TABLE os_visitor ADD INDEX idx_server_time (server_time);
ALTER TABLE os_invoice ADD INDEX idx_parent_created_on (parent, created_on);

# --------------------------------
-- Data integrity
ALTER TABLE os_invoice ADD CONSTRAINT fk_invoice_parent
  FOREIGN KEY (parent) REFERENCES os_invoice_parent(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_invoice ADD CONSTRAINT fk_invoice_product
  FOREIGN KEY (item) REFERENCES os_product(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_invoice ADD CONSTRAINT fk_invoice_store
  FOREIGN KEY (store) REFERENCES os_store(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_invoice ADD CONSTRAINT fk_invoice_batch
  FOREIGN KEY (batch) REFERENCES os_batch(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_invoice ADD CONSTRAINT fk_invoice_service
  FOREIGN KEY (service) REFERENCES os_service(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_invoice_parent ADD CONSTRAINT fk_invoice_parent_patient
  FOREIGN KEY (patient) REFERENCES os_patient(id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE os_stock_summary ADD UNIQUE KEY uk_store_item_batch (store, item, batch);

-- Performance indexes
ALTER TABLE os_invoice ADD INDEX idx_parent_item (parent, item);
ALTER TABLE os_invoice ADD INDEX idx_parent_servicetype (parent, servicetype);
ALTER TABLE os_invoice_parent ADD INDEX idx_patient_status (patient, status);
ALTER TABLE os_patient_prescription ADD INDEX idx_created_on_sex (created_on, sex);
ALTER TABLE os_user ADD UNIQUE KEY uk_username (username);
ALTER TABLE os_user ADD UNIQUE KEY uk_email (email);
ALTER TABLE os_invoice_parent ADD UNIQUE KEY uk_invoice_number (invoice_number);

-- Additional performance indexes for invoice optimization
ALTER TABLE os_invoice ADD INDEX idx_parent_created_by (parent, created_by);
ALTER TABLE os_stock_summary ADD INDEX idx_store_item_batch_qty (store, item, batch, quantity);
ALTER TABLE os_invoice_parent ADD INDEX idx_patient_status_date (patient, status, invoice_date DESC);
ALTER TABLE os_purchase_receive ADD INDEX idx_item_store_batch (item, store, batch);
ALTER TABLE os_purchase_receive_parent ADD INDEX idx_status_id (status, id);

-- Backup table enhancements for professional backup system
ALTER TABLE os_backup ADD COLUMN file_path VARCHAR(500) NULL AFTER attachment;
ALTER TABLE os_backup ADD COLUMN file_size BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER file_path;
ALTER TABLE os_backup ADD COLUMN checksum VARCHAR(32) NULL AFTER file_size;
ALTER TABLE os_backup ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT 'gzip' AFTER checksum;
ALTER TABLE os_backup ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'success' AFTER type;
ALTER TABLE os_backup ADD COLUMN duration VARCHAR(50) NULL AFTER status;
ALTER TABLE os_backup ADD COLUMN tables_count INT UNSIGNED NOT NULL DEFAULT 0 AFTER duration;
ALTER TABLE os_backup MODIFY COLUMN attachment VARCHAR(250) NOT NULL;


