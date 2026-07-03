<?php

//Migration: create_pet_owners_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists pet_owners (
                id int UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
                name VARCHAR(50) UNIQUE NOT NULL,
                phone VARCHAR(20) UNIQUE NOT NULL
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS pet_owners';
    }
};