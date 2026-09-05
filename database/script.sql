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

