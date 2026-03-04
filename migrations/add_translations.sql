-- Migration: Add translation columns for RU and EN languages
-- Run this SQL in phpMyAdmin or MySQL command line

-- Hero section translations
ALTER TABLE hero 
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN subtitle_ru TEXT DEFAULT NULL AFTER subtitle,
ADD COLUMN subtitle_en TEXT DEFAULT NULL AFTER subtitle_ru,
ADD COLUMN cta_text_ru VARCHAR(100) DEFAULT NULL AFTER cta_text,
ADD COLUMN cta_text_en VARCHAR(100) DEFAULT NULL AFTER cta_text_ru,
ADD COLUMN cta_secondary_text_ru VARCHAR(100) DEFAULT NULL AFTER cta_secondary_text,
ADD COLUMN cta_secondary_text_en VARCHAR(100) DEFAULT NULL AFTER cta_secondary_text_ru;

-- About section translations
ALTER TABLE about 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN content_ru TEXT DEFAULT NULL AFTER content,
ADD COLUMN content_en TEXT DEFAULT NULL AFTER content_ru;

-- About stats translations
ALTER TABLE about_stats 
ADD COLUMN label_ru VARCHAR(100) DEFAULT NULL AFTER label,
ADD COLUMN label_en VARCHAR(100) DEFAULT NULL AFTER label_ru,
ADD COLUMN suffix_ru VARCHAR(50) DEFAULT NULL AFTER suffix,
ADD COLUMN suffix_en VARCHAR(50) DEFAULT NULL AFTER suffix_ru;

-- Services section translations
ALTER TABLE services_section 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru;

-- Services translations
ALTER TABLE services 
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN short_description_ru TEXT DEFAULT NULL AFTER short_description,
ADD COLUMN short_description_en TEXT DEFAULT NULL AFTER short_description_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru,
ADD COLUMN features_ru TEXT DEFAULT NULL AFTER features,
ADD COLUMN features_en TEXT DEFAULT NULL AFTER features_ru;

-- Process section translations
ALTER TABLE process_section 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru;

-- Process steps translations
ALTER TABLE process_steps 
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru,
ADD COLUMN features_ru TEXT DEFAULT NULL AFTER features,
ADD COLUMN features_en TEXT DEFAULT NULL AFTER features_ru;

-- Why Us section translations
ALTER TABLE why_us_section 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru;

-- Why Us items translations
ALTER TABLE why_us_items 
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru;

-- FAQ section translations
ALTER TABLE faq_section 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru;

-- FAQ translations
ALTER TABLE faq 
ADD COLUMN question_ru TEXT DEFAULT NULL AFTER question,
ADD COLUMN question_en TEXT DEFAULT NULL AFTER question_ru,
ADD COLUMN answer_ru TEXT DEFAULT NULL AFTER answer,
ADD COLUMN answer_en TEXT DEFAULT NULL AFTER answer_ru;

-- Contact section translations
ALTER TABLE contact 
ADD COLUMN section_label_ru VARCHAR(100) DEFAULT NULL AFTER section_label,
ADD COLUMN section_label_en VARCHAR(100) DEFAULT NULL AFTER section_label_ru,
ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL AFTER title,
ADD COLUMN title_en VARCHAR(255) DEFAULT NULL AFTER title_ru,
ADD COLUMN description_ru TEXT DEFAULT NULL AFTER description,
ADD COLUMN description_en TEXT DEFAULT NULL AFTER description_ru,
ADD COLUMN form_title_ru VARCHAR(255) DEFAULT NULL AFTER form_title,
ADD COLUMN form_title_en VARCHAR(255) DEFAULT NULL AFTER form_title_ru;

-- Hero trust items translations
ALTER TABLE hero_trust_items 
ADD COLUMN text_ru VARCHAR(255) DEFAULT NULL AFTER text,
ADD COLUMN text_en VARCHAR(255) DEFAULT NULL AFTER text_ru;
