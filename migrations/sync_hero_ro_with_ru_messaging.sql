-- Aliniază conținutul hero (RO) cu mesajul din rusă/engleză: titlu, subtitlu, bară încredere.
-- Rulează: mysql -u root acteromania_cms < migrations/sync_hero_ro_with_ru_messaging.sql
-- sau: php scripts/apply_hero_ro_alignment.php

SET NAMES utf8mb4;

UPDATE hero SET
  title = 'Cetățenie română - Serviciu complet',
  subtitle = 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.',
  cta_text = 'Consultație gratuită',
  updated_at = CURRENT_TIMESTAMP
WHERE id = 1;

UPDATE hero_translations SET
  title = 'Cetățenie română - Serviciu complet',
  subtitle = 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.',
  cta_text = 'Consultație gratuită',
  updated_at = CURRENT_TIMESTAMP
WHERE hero_id = 1 AND language = 'ro';

UPDATE hero_trust_items SET
  text = '15+ ani de experiență',
  updated_at = CURRENT_TIMESTAMP
WHERE id = 1;

UPDATE hero_trust_items SET
  text = '15.000+ cazuri reușite',
  updated_at = CURRENT_TIMESTAMP
WHERE id = 2;

UPDATE hero_trust_items SET
  text = 'Termene scurte de procesare',
  updated_at = CURRENT_TIMESTAMP
WHERE id = 3;
