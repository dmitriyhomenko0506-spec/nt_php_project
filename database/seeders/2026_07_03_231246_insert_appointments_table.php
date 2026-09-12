<?php

//Seeder: insert_appointments_table

return new class {
    public function run(): string
    {
        return "
        INSERT IGNORE INTO appointments (clinic_id, vet_id, pet_id, scheduled_for, status) VALUES
        (1, 3, 8, '2026-05-18 09:00:00', 'confirm'),
        (3, 7, 4, '2026-05-18 12:30:00', 'confirm'),
        (2, 6, 2, '2026-05-18 18:00:00', 'confirm'),
        (4, 10, 7, '2026-05-18 17:00:00', 'confirm'),
        (5, 13, 11, '2026-05-18 12:00:00', 'confirm'),
        (1, 3, 6, '2026-05-18 20:00:00', 'confirm');
        ";
    }
};