-- Actualizare titluri servicii pentru limba rusă (alinieri la denumiri afișate pe site)
-- Rulează în phpMyAdmin sau: mysql -u root acteromania_cms < migrations/update_russian_service_titles.sql

UPDATE services_translations SET title = 'Румынское гражданство через суд', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 5 AND language = 'ru';

UPDATE services_translations SET title = 'Румынский паспорт', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 2 AND language = 'ru';

UPDATE services_translations SET title = 'Румынский булетин', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 3 AND language = 'ru';

UPDATE services_translations SET title = 'Румынские водительские права', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 13 AND language = 'ru';

UPDATE services_translations SET title = 'Апостиль документов', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 8 AND language = 'ru';

UPDATE services_translations SET title = 'Услуги для диаспоры', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 10 AND language = 'ru';

UPDATE services_translations SET title = 'Сертификаты квалификации - CIP', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 15 AND language = 'ru';

UPDATE services_translations SET title = 'Пособие на ребёнка', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 14 AND language = 'ru';

UPDATE services_translations SET title = 'Акты гражданского состояния', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 11 AND language = 'ru';

UPDATE services_translations SET title = 'Справка о несудимости', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 9 AND language = 'ru';

UPDATE services_translations SET title = 'Номер дела', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 17 AND language = 'ru';

UPDATE services_translations SET title = 'Перевод документов', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 7 AND language = 'ru';

UPDATE services_translations SET title = 'Запись на присягу', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 6 AND language = 'ru';

UPDATE services_translations SET title = 'Румынские свидетельства о рождении и браке', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 4 AND language = 'ru';

UPDATE services_translations SET title = 'Доставка румынских документов', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 12 AND language = 'ru';

UPDATE services_translations SET title = 'Транспорт в Румынию и обратно', updated_at = CURRENT_TIMESTAMP
WHERE service_id = 16 AND language = 'ru';
