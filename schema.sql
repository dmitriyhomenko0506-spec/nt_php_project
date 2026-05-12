/*
clinics — name, city → список клінік.
vets — clinic_id, name, specialty → ветеринари певної клініки.
pet_owners — name, phone → власники тварин.
pets — owner_id, name, species → домашні улюбленці.
appointments — clinic_id, vet_id, pet_id, scheduled_for, status → записи на прийом.
*/


/* clinics  --- Создаем клиники в Украине 454 города*/
CREATE TABLE if not exists clinics (
    id smallint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL
);

/* vets --- Добавляем список врачей, которые в них работают */
CREATE TABLE if not exists vets (
 id smallint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
 clinic_id smallint UNSIGNED NOT NULL,
 name VARCHAR(50) NOT NULL,
 specialty VARCHAR(255) NOT NULL,

 FOREIGN KEY (clinic_id) REFERENCES clinics(id)
);

/* pet_owners --- Формируем список клиентов */
CREATE TABLE if not exists pet_owners (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    name VARCHAR(50) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL
);

/* pets  --- Формируем список животных, наших клиентов */
CREATE TABLE if not exists pets (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    owner_id int UNSIGNED NOT NULL,
    name VARCHAR(50) NOT NULL,
    species VARCHAR(50) NOT NULL,

    FOREIGN KEY (owner_id) REFERENCES pet_owners(id)
);

/* appointments --- Запись к врачу */
CREATE TABLE if not exists appointments (
   id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
   clinic_id smallint UNSIGNED NOT NULL,
   vet_id smallint UNSIGNED NOT NULL,
   pet_id int UNSIGNED NOT NULL,
   scheduled_for DATETIME NOT NULL,
   status VARCHAR(20),

   FOREIGN KEY (clinic_id) REFERENCES clinics(id),
   FOREIGN KEY (vet_id) REFERENCES vets(id),
   FOREIGN KEY (pet_id) REFERENCES pets(id)
);