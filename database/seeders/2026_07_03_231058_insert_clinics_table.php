<?php

//Seeder: insert_clinics_table

return new class {
    public function run(): string
    {
        return "
        INSERT IGNORE INTO clinics (name, city) VALUES
        ('Вет-Киев', 'Киев'), 
        ('Вет-Одеса', 'Одесса'), 
        ('Вет-Харьков', 'Харьков'), 
        ('Вет-Львов', 'Львов'),
        ('Вет-Ужгород', 'Ужгород');
        ";
    }
};