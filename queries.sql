

/* Какие клиники у нас есть */
SELECT * FROM clinics;

/* Какие врачи у нас есть в клинике Вет-Харьков -> только имя + специальность (Вет-Харьков - ID - 3) */
SELECT name, specialty FROM vets WHERE clinic_id = 3;

/* Какие врачи есть (специальность) и в какой клинике */
SELECT 
c.name as clinics_name,
v.name as doc_name,
v.specialty as doc_specialty
FROM clinics as c
JOIN vets as v ON v.clinic_id = c.id;

/* Записи в каких клиниках и у какого врача */
SELECT 
c.name as clinics_name,
v.name as doc_name,
v.specialty as doc_specialty,
app.scheduled_for as time
FROM clinics as c
JOIN vets as v ON v.clinic_id = c.id
JOIN appointments as app ON app.vet_id = v.id;

/* Записи в каких клиниках и у какого врача + что за животное */
SELECT 
c.name as clinics_name,
v.name as doc_name,
v.specialty as doc_specialty,
app.scheduled_for as time,
pet.name as pet_name,
pet.species as type_pets
FROM clinics as c
JOIN vets as v ON v.clinic_id = c.id
JOIN appointments as app ON app.vet_id = v.id
JOIN pets as pet ON pet.id = app.pet_id;

/* Записи в каких клиниках и у какого врача + что за животное + кто хозяин */
SELECT 
c.name as clinics_name,
v.name as doc_name,
v.specialty as doc_specialty,
app.scheduled_for as time,
pet.name as pet_name,
pet.species as type_pets,
o.name as name_owner,
o.phone as phone_owner
FROM clinics as c
JOIN vets as v ON v.clinic_id = c.id
JOIN appointments as app ON app.vet_id = v.id
JOIN pets as pet ON pet.id = app.pet_id
JOIN pet_owners as o ON o.id = pet.owner_id;

/* Чтобы получить по какой-то конкретной клинике добавить WHERE c.id = FK клиники (ID) */

/* Записи в каких клиниках и у какого врача + что за животное + кто хозяин - СОРТ Время */
SELECT 
c.name as clinics_name,
v.name as doc_name,
v.specialty as doc_specialty,
app.scheduled_for as time,
pet.name as pet_name,
pet.species as type_pets,
o.name as name_owner,
o.phone as phone_owner
FROM clinics as c
JOIN vets as v ON v.clinic_id = c.id
JOIN appointments as app ON app.vet_id = v.id
JOIN pets as pet ON pet.id = app.pet_id
JOIN pet_owners as o ON o.id = pet.owner_id
ORDER BY app.scheduled_for;
