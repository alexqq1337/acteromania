-- Serviciu id 4: o singură bifă în loc de 3 (naștere/căsătorie/deces separate)
UPDATE services SET
  features = '["căsătorie / divorț / deces pe actul de naștere sau căsătorie", "Eliberare duplicate", "Rectificare date"]',
  updated_at = CURRENT_TIMESTAMP
WHERE id = 4;

UPDATE services_translations SET
  features = '["căsătorie / divorț / deces pe actul de naștere sau căsătorie", "Eliberare duplicate", "Rectificare date"]',
  updated_at = CURRENT_TIMESTAMP
WHERE service_id = 4 AND language = 'ro';

UPDATE services_translations SET
  features = '["Marriage / divorce / death on the birth or marriage certificate", "Duplicate issuance", "Data correction"]',
  updated_at = CURRENT_TIMESTAMP
WHERE service_id = 4 AND language = 'en';

UPDATE services_translations SET
  features = '["Брак / развод / смерть в свидетельстве о рождении или браке", "Выдача дубликатов", "Исправление данных"]',
  updated_at = CURRENT_TIMESTAMP
WHERE service_id = 4 AND language = 'ru';
