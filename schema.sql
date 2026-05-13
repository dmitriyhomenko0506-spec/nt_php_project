
/* 
courses — title, description → перелік курсів.
lessons — course_id, title, video_url → уроки всередині курсу.
users — name, email → акаунти слухачів або авторів.
enrolments — user_id, course_id, progress_pct → запис користувача на курс і прогрес.
tags — name → ключові слова.
course_tag — course_id, tag_id → проміжна таблиця для many-to-many «курс ↔ тег».

*/

/* courses - Формируем список курсов */
CREATE TABLE if not exists courses (
    id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);


/* lessons - Формируем список уроков */
CREATE TABLE if not exists lessons (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    course_id SMALLINT UNSIGNED NOT NULL,
    video_url VARCHAR(255) NOT NULL,

    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

/* users - Создаем список пользователей */
CREATE TABLE if not exists users (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL
);

/* enrolments Запись пользователя на курс */
CREATE TABLE if not exists enrolments (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id int UNSIGNED NOT NULL,
    course_id SMALLINT UNSIGNED NOT NULL,
    progress_pct TINYINT UNSIGNED,

    
    UNIQUE (user_id , course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

/*tags  Таблица тэгов */
CREATE TABLE if not exists tags (
    id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL
);

/* course_tag  для many-to-many */
CREATE TABLE if not exists course_tag (
    course_id SMALLINT UNSIGNED NOT NULL,
    tags_id SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (course_id, tags_id), 
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tags_id) REFERENCES tags(id) ON DELETE CASCADE
);


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