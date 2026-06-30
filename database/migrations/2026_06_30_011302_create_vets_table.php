<?php

//Migration: create_vets_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists vets (
                id smallint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                clinic_id smallint UNSIGNED NOT NULL,
                name VARCHAR(50) NOT NULL,
                specialty VARCHAR(255) NOT NULL,
                FOREIGN KEY (clinic_id) REFERENCES clinics(id)
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS vets';
    }
};