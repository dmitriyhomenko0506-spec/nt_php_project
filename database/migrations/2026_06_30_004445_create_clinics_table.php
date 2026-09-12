<?php

//Migration: create_clinics_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists clinics (
                id smallint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(50) UNIQUE NOT NULL,
                city VARCHAR(50) NOT NULL
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS clinics';
    }
};