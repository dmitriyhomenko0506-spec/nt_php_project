<?php

//Migration: create_pets_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists pets (
                id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                owner_id int UNSIGNED NOT NULL,
                name VARCHAR(50) UNIQUE NOT NULL,
                species VARCHAR(50) NOT NULL,
                FOREIGN KEY (owner_id) REFERENCES pet_owners(id)
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS pets';
    }
};