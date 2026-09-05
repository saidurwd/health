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


