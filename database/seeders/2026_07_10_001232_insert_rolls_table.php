<?php

//Seeder: insert_rolls_table

/*
* For Admin (email - > admin@clinic.ua / pass -> admin)
* For Clinic (email - > vet-odesa@clinic.ua / pass -> od-admin)
* For Doctor (email - > doc-odesa@clinic.ua / pass -> doc-admin)
*/

return new class {
    public function run(): string
    {
        return 'INSERT IGNORE INTO rolls (email, pass, clinic_id, name, status) VALUES 
        ("admin@clinic.ua", "' . password_hash('admin', PASSWORD_DEFAULT) . '", 0, "admin", 0),
        ("vet-odesa@clinic.ua", "' . password_hash('od-admin', PASSWORD_DEFAULT) . '", 2, "Вет-Одеса", 1),
        ("doc-odesa@clinic.ua", "' . password_hash('doc-admin', PASSWORD_DEFAULT) . '", 1, "Илинов Сергей", 2)';
    }
};
