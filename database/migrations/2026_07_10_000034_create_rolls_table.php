<?php

//Migration: create_rolls_table

return new class {
    public function up(): string
    {
        return 'CREATE TABLE if not exists rolls (
                id smallint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(50) UNIQUE NOT NULL,
                pass VARCHAR(255) NOT NULL,
                clinic_id VARCHAR(255) NOT NULL,
                name VARCHAR(50) NOT NULL,
                status smallint NOT NULL
                );';
    }

    public function down(): string
    {
        return 'DROP TABLE IF EXISTS rolls';
    }
};
