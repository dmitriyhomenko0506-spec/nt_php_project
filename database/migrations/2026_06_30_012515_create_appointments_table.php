<?php

//Migration: create_appointments_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists appointments (
                id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                clinic_id smallint UNSIGNED NOT NULL,
                vet_id smallint UNSIGNED NOT NULL,
                pet_id int UNSIGNED NOT NULL,
                scheduled_for DATETIME NOT NULL,
                status VARCHAR(20),
                
                UNIQUE KEY unique_appointment (vet_id, scheduled_for),

                FOREIGN KEY (clinic_id) REFERENCES clinics(id),
                FOREIGN KEY (vet_id) REFERENCES vets(id),
                FOREIGN KEY (pet_id) REFERENCES pets(id)
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS appointments';
    }
};