

/* Добавляем колонку purpose  - причина обращения  */
ALTER TABLE appointments ADD COLUMN purpose VARCHAR(255) AFTER scheduled_for;

/* Смена телефона у нашего клиента Романова Наталія Іванівна ID - 4*/
UPDATE pet_owners SET phone = '380509874561' WHERE id = 4;

/* Удаляем колонку purpose  - причина обращения */
ALTER TABLE appointments DROP COLUMN purpose;

/* Удаляем таблицу + все смежные данные с ней */
DROP TABLE appointments CASCADE;
