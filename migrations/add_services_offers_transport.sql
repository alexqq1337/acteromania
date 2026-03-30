-- Coloană per serviciu: se afișează în modalul de detalii pe site când este activată.
ALTER TABLE `services`
  ADD COLUMN `offers_transport` tinyint(1) NOT NULL DEFAULT 0 AFTER `enabled`;
