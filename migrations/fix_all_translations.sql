-- =============================================
-- FIX: Complete translations for all content
-- ActeRomânia CMS - Full EN/RU Translations
-- =============================================

-- =============================================
-- SERVICES TRANSLATIONS - COMPLETE
-- =============================================

-- Clear existing and insert fresh
DELETE FROM services_translations WHERE language IN ('en', 'ru');

-- English translations for all 17 services
INSERT INTO services_translations (service_id, language, title, description) VALUES
(1, 'en', 'Romanian Citizenship', 'Complete assistance for obtaining Romanian citizenship. We handle all documentation, legalization, and submission to authorities.'),
(2, 'en', 'Romanian Passport', 'Fast and efficient service for obtaining a Romanian passport after receiving citizenship.'),
(3, 'en', 'Romanian ID Card', 'Complete service for obtaining a Romanian ID card (buletin). Personal assistance at all stages.'),
(4, 'en', 'Birth & Marriage Certificates', 'Obtaining birth and marriage certificates from Romania with apostille and translation.'),
(5, 'en', 'Citizenship by Court', 'Obtaining Romanian citizenship through court proceedings for complex cases.'),
(6, 'en', 'Oath Appointment', 'Scheduling and assistance for the citizenship oath ceremony in Romania.'),
(7, 'en', 'Document Translation', 'Certified and notarized translations for all types of documents.'),
(8, 'en', 'Apostille Services', 'Apostille certification for Romanian documents for international use.'),
(9, 'en', 'Criminal Record Certificate', 'Obtaining criminal record certificates from Romania.'),
(10, 'en', 'Diaspora Services', 'Comprehensive services for Romanians living abroad.'),
(11, 'en', 'Civil Status Documents', 'Obtaining and processing civil status documents from Romania.'),
(12, 'en', 'Document Delivery', 'Secure delivery of Romanian documents to your location.'),
(13, 'en', 'Driving License', 'Assistance with Romanian driving license procedures.'),
(14, 'en', 'Child Allowance', 'Help obtaining child allowance benefits from Romania.'),
(15, 'en', 'Professional Certificates - CIP', 'Professional attestation and certificate services.'),
(16, 'en', 'Transport to Romania', 'Transportation services to and from Romania.'),
(17, 'en', 'ANC Case Number', 'Tracking and obtaining your ANC (National Citizenship Authority) case number.');

-- Russian translations for all 17 services
INSERT INTO services_translations (service_id, language, title, description) VALUES
(1, 'ru', 'Румынское гражданство', 'Полная помощь в получении румынского гражданства. Мы занимаемся всей документацией, легализацией и подачей в органы власти.'),
(2, 'ru', 'Румынский паспорт', 'Быстрая и эффективная услуга по получению румынского паспорта после получения гражданства.'),
(3, 'ru', 'Румынское удостоверение личности', 'Полный сервис по получению румынского удостоверения личности (булетин). Личное сопровождение на всех этапах.'),
(4, 'ru', 'Свидетельства о рождении и браке', 'Получение свидетельств о рождении и браке из Румынии с апостилем и переводом.'),
(5, 'ru', 'Гражданство через суд', 'Получение румынского гражданства через судебное разбирательство для сложных случаев.'),
(6, 'ru', 'Запись на присягу', 'Запись и помощь в церемонии присяги на гражданство в Румынии.'),
(7, 'ru', 'Перевод документов', 'Заверенные и нотариальные переводы всех видов документов.'),
(8, 'ru', 'Услуги апостиля', 'Апостиль на румынские документы для международного использования.'),
(9, 'ru', 'Справка о несудимости', 'Получение справок о несудимости из Румынии.'),
(10, 'ru', 'Услуги диаспоры', 'Комплексные услуги для румын, проживающих за рубежом.'),
(11, 'ru', 'Документы гражданского состояния', 'Получение и оформление документов гражданского состояния из Румынии.'),
(12, 'ru', 'Доставка документов', 'Безопасная доставка румынских документов к вам.'),
(13, 'ru', 'Водительские права', 'Помощь с процедурами получения румынских водительских прав.'),
(14, 'ru', 'Детское пособие', 'Помощь в получении детских пособий из Румынии.'),
(15, 'ru', 'Профессиональные сертификаты - CIP', 'Услуги профессиональной аттестации и сертификации.'),
(16, 'ru', 'Транспорт в Румынию', 'Транспортные услуги в и из Румынии.'),
(17, 'ru', 'Номер дела ANC', 'Отслеживание и получение номера дела ANC (Национальное управление по гражданству).');

-- =============================================
-- FAQ TRANSLATIONS - COMPLETE
-- =============================================

DELETE FROM faq_translations WHERE language IN ('en', 'ru');

-- English FAQ translations
INSERT INTO faq_translations (faq_id, language, question, answer) VALUES
(1, 'en', 'Who can obtain Romanian citizenship?', 'Anyone who can prove Romanian ancestry up to the 3rd generation (great-grandparents) can apply for Romanian citizenship. This includes descendants of people who lived in historical Romanian territories.'),
(2, 'en', 'How long does the citizenship process take?', 'The process typically takes 6-18 months, depending on the complexity of the case and the completeness of documents. We work to ensure the fastest possible processing.'),
(3, 'en', 'What documents are required for citizenship?', 'Required documents include: birth certificates (yours and ancestors), marriage certificates, proof of Romanian ancestry, valid ID/passport, and various supplementary documents depending on your case.'),
(4, 'en', 'Do I need to speak Romanian?', 'Basic knowledge of Romanian language and culture is required for the oath ceremony. We provide guidance and resources to help you prepare.'),
(5, 'en', 'Can I get a passport without residency in Romania?', 'Yes, you can obtain a Romanian passport without establishing residency in Romania. The passport is issued after receiving citizenship.'),
(6, 'en', 'What are the costs of your services?', 'Costs vary depending on the services requested. Contact us for a free consultation and personalized quote. We offer transparent pricing with no hidden fees.');

-- Russian FAQ translations
INSERT INTO faq_translations (faq_id, language, question, answer) VALUES
(1, 'ru', 'Кто может получить румынское гражданство?', 'Любой человек, который может доказать румынское происхождение до 3-го поколения (прабабушки и прадедушки), может подать заявление на румынское гражданство. Это включает потомков людей, живших на исторических румынских территориях.'),
(2, 'ru', 'Сколько времени занимает процесс получения гражданства?', 'Процесс обычно занимает 6-18 месяцев, в зависимости от сложности дела и полноты документов. Мы работаем над обеспечением максимально быстрого оформления.'),
(3, 'ru', 'Какие документы требуются для гражданства?', 'Необходимые документы включают: свидетельства о рождении (ваши и предков), свидетельства о браке, доказательство румынского происхождения, действующее удостоверение личности/паспорт и различные дополнительные документы в зависимости от вашего случая.'),
(4, 'ru', 'Нужно ли мне говорить по-румынски?', 'Для церемонии присяги требуется базовое знание румынского языка и культуры. Мы предоставляем рекомендации и ресурсы для подготовки.'),
(5, 'ru', 'Могу ли я получить паспорт без проживания в Румынии?', 'Да, вы можете получить румынский паспорт без установления постоянного места жительства в Румынии. Паспорт выдается после получения гражданства.'),
(6, 'ru', 'Сколько стоят ваши услуги?', 'Стоимость зависит от запрашиваемых услуг. Свяжитесь с нами для бесплатной консультации и персонального предложения. Мы предлагаем прозрачное ценообразование без скрытых платежей.');

-- =============================================
-- HERO TRUST ITEMS - FIX
-- =============================================

DELETE FROM hero_trust_items_translations WHERE language IN ('en', 'ru');

INSERT INTO hero_trust_items_translations (trust_item_id, language, text)
SELECT id, 'en',
    CASE id
        WHEN 1 THEN '15+ Years Experience'
        WHEN 2 THEN '15,000+ Successful Cases'
        WHEN 3 THEN 'Fast Processing Time'
        ELSE text
    END
FROM hero_trust_items WHERE enabled = 1;

INSERT INTO hero_trust_items_translations (trust_item_id, language, text)
SELECT id, 'ru',
    CASE id
        WHEN 1 THEN '15+ лет опыта'
        WHEN 2 THEN '15 000+ успешных дел'
        WHEN 3 THEN 'Короткие сроки обработки'
        ELSE text
    END
FROM hero_trust_items WHERE enabled = 1;

-- =============================================
-- ABOUT STATS - FIX
-- =============================================

DELETE FROM about_stats_translations WHERE language IN ('en', 'ru');

INSERT INTO about_stats_translations (stat_id, language, label, suffix)
SELECT id, 'en',
    CASE id
        WHEN 1 THEN 'Years Experience'
        WHEN 2 THEN 'Successful Cases'
        WHEN 3 THEN 'Satisfied Clients'
        WHEN 4 THEN 'Average Time'
        ELSE label
    END,
    CASE 
        WHEN suffix = 'ani' OR suffix LIKE '%ani%' THEN 'years'
        WHEN suffix = 'luni' OR suffix LIKE '%luni%' THEN 'months'
        WHEN suffix = '%' THEN '%'
        WHEN suffix = '+' THEN '+'
        ELSE suffix
    END
FROM about_stats WHERE enabled = 1;

INSERT INTO about_stats_translations (stat_id, language, label, suffix)
SELECT id, 'ru',
    CASE id
        WHEN 1 THEN 'Лет опыта'
        WHEN 2 THEN 'Успешных дел'
        WHEN 3 THEN 'Довольных клиентов'
        WHEN 4 THEN 'Среднее время'
        ELSE label
    END,
    CASE 
        WHEN suffix = 'ani' OR suffix LIKE '%ani%' THEN 'лет'
        WHEN suffix = 'luni' OR suffix LIKE '%luni%' THEN 'мес.'
        WHEN suffix = '%' THEN '%'
        WHEN suffix = '+' THEN '+'
        ELSE suffix
    END
FROM about_stats WHERE enabled = 1;

-- =============================================
-- PROCESS STEPS - FIX
-- =============================================

DELETE FROM process_steps_translations WHERE language IN ('en', 'ru');

INSERT INTO process_steps_translations (step_id, language, title, description)
SELECT id, 'en',
    CASE step_number
        WHEN 1 THEN 'Free Consultation'
        WHEN 2 THEN 'Document Preparation'
        WHEN 3 THEN 'Submission & Processing'
        WHEN 4 THEN 'Receive Documents'
        ELSE title
    END,
    CASE step_number
        WHEN 1 THEN 'We analyze your situation and determine the best route to obtaining Romanian citizenship and documents.'
        WHEN 2 THEN 'We collect, translate, and certify all necessary documents according to legal requirements.'
        WHEN 3 THEN 'We submit your file to Romanian authorities and monitor the entire process.'
        WHEN 4 THEN 'You receive your Romanian citizenship, passport, and/or ID card.'
        ELSE description
    END
FROM process_steps WHERE enabled = 1;

INSERT INTO process_steps_translations (step_id, language, title, description)
SELECT id, 'ru',
    CASE step_number
        WHEN 1 THEN 'Бесплатная консультация'
        WHEN 2 THEN 'Подготовка документов'
        WHEN 3 THEN 'Подача и обработка'
        WHEN 4 THEN 'Получение документов'
        ELSE title
    END,
    CASE step_number
        WHEN 1 THEN 'Мы анализируем вашу ситуацию и определяем лучший путь к получению румынского гражданства и документов.'
        WHEN 2 THEN 'Мы собираем, переводим и заверяем все необходимые документы согласно требованиям.'
        WHEN 3 THEN 'Мы подаем ваше дело в румынские органы власти и контролируем весь процесс.'
        WHEN 4 THEN 'Вы получаете румынское гражданство, паспорт и/или удостоверение личности.'
        ELSE description
    END
FROM process_steps WHERE enabled = 1;

-- =============================================
-- WHY US ITEMS - FIX
-- =============================================

DELETE FROM why_us_items_translations WHERE language IN ('en', 'ru');

INSERT INTO why_us_items_translations (item_id, language, title, description)
SELECT id, 'en',
    CASE id
        WHEN 1 THEN '15+ Years Experience'
        WHEN 2 THEN 'Guaranteed Results'
        WHEN 3 THEN 'Fast Processing'
        WHEN 4 THEN 'Full Transparency'
        WHEN 5 THEN 'Professional Team'
        ELSE title
    END,
    CASE id
        WHEN 1 THEN 'Over 15 years of experience helping clients obtain Romanian citizenship and documents.'
        WHEN 2 THEN 'We guarantee success or refund your fees. Your satisfaction is our priority.'
        WHEN 3 THEN 'The fastest processing times in the industry. We value your time.'
        WHEN 4 THEN 'Clear pricing with no hidden fees. You know exactly what you pay for.'
        WHEN 5 THEN 'Team of experienced lawyers and notaries specialized in Romanian citizenship.'
        ELSE description
    END
FROM why_us_items WHERE enabled = 1;

INSERT INTO why_us_items_translations (item_id, language, title, description)
SELECT id, 'ru',
    CASE id
        WHEN 1 THEN '15+ лет опыта'
        WHEN 2 THEN 'Гарантированный результат'
        WHEN 3 THEN 'Быстрая обработка'
        WHEN 4 THEN 'Полная прозрачность'
        WHEN 5 THEN 'Профессиональная команда'
        ELSE title
    END,
    CASE id
        WHEN 1 THEN 'Более 15 лет опыта помощи клиентам в получении румынского гражданства и документов.'
        WHEN 2 THEN 'Мы гарантируем успех или вернем деньги. Ваше удовлетворение - наш приоритет.'
        WHEN 3 THEN 'Самые быстрые сроки обработки в отрасли. Мы ценим ваше время.'
        WHEN 4 THEN 'Четкие цены без скрытых платежей. Вы точно знаете, за что платите.'
        WHEN 5 THEN 'Команда опытных юристов и нотариусов, специализирующихся на румынском гражданстве.'
        ELSE description
    END
FROM why_us_items WHERE enabled = 1;

-- =============================================
-- DONE
-- =============================================
SELECT 'All translations fixed successfully!' AS status;
