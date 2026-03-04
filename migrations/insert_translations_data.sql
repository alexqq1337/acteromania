-- =============================================
-- TRADUCERI PENTRU CONȚINUT DINAMIC
-- ActeRomânia CMS - EN / RU Translations
-- =============================================

-- =============================================
-- HERO SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO hero_translations (hero_id, language, title, subtitle, cta_text) 
SELECT id, 'en', 
    'Romanian Citizenship - Complete Service',
    'Fast and professional legal assistance for obtaining Romanian citizenship, passport, and ID card. Over 15,000 successful cases.',
    'Free Consultation'
FROM hero WHERE id = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    subtitle = VALUES(subtitle),
    cta_text = VALUES(cta_text);

-- Russian
INSERT INTO hero_translations (hero_id, language, title, subtitle, cta_text) 
SELECT id, 'ru', 
    'Румынское гражданство - Полный сервис',
    'Быстрая и профессиональная юридическая помощь в получении румынского гражданства, паспорта и удостоверения личности. Более 15 000 успешных дел.',
    'Бесплатная консультация'
FROM hero WHERE id = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    subtitle = VALUES(subtitle),
    cta_text = VALUES(cta_text);

-- =============================================
-- HERO TRUST ITEMS TRANSLATIONS
-- =============================================

-- English
INSERT INTO hero_trust_items_translations (trust_item_id, language, text)
SELECT id, 'en', 
    CASE 
        WHEN text LIKE '%ani experien%' THEN '15+ years experience'
        WHEN text LIKE '%dosare%' THEN '15,000+ successful cases'
        WHEN text LIKE '%termen%' THEN 'Short processing time'
        WHEN text LIKE '%garanta%' THEN 'Guaranteed results'
        ELSE text
    END
FROM hero_trust_items WHERE enabled = 1
ON DUPLICATE KEY UPDATE text = VALUES(text);

-- Russian
INSERT INTO hero_trust_items_translations (trust_item_id, language, text)
SELECT id, 'ru', 
    CASE 
        WHEN text LIKE '%ani experien%' THEN '15+ лет опыта'
        WHEN text LIKE '%dosare%' THEN '15 000+ успешных дел'
        WHEN text LIKE '%termen%' THEN 'Короткие сроки'
        WHEN text LIKE '%garanta%' THEN 'Гарантированный результат'
        ELSE text
    END
FROM hero_trust_items WHERE enabled = 1
ON DUPLICATE KEY UPDATE text = VALUES(text);

-- =============================================
-- ABOUT SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO about_translations (about_id, language, section_label, title, content)
SELECT id, 'en',
    'About Us',
    'Professional Team for Your Documentation',
    'ActeRomânia is a team of legal experts specializing in obtaining Romanian citizenship and documents. We provide comprehensive services from document preparation to obtaining citizenship, passport, and ID card. Our experience and professionalism guarantee fast and quality results.'
FROM about WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    content = VALUES(content);

-- Russian
INSERT INTO about_translations (about_id, language, section_label, title, content)
SELECT id, 'ru',
    'О нас',
    'Профессиональная команда для ваших документов',
    'ActeRomânia - команда юридических экспертов, специализирующихся на получении румынского гражданства и документов. Мы предоставляем комплексные услуги от подготовки документов до получения гражданства, паспорта и удостоверения личности. Наш опыт и профессионализм гарантируют быстрые и качественные результаты.'
FROM about WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    content = VALUES(content);

-- =============================================
-- ABOUT STATS TRANSLATIONS
-- =============================================

-- English
INSERT INTO about_stats_translations (stat_id, language, label, suffix)
SELECT id, 'en',
    CASE 
        WHEN label LIKE '%ani experien%' OR label LIKE '%ani de experiență%' THEN 'Years Experience'
        WHEN label LIKE '%dosare%' OR label LIKE '%cazuri%' THEN 'Successful Cases'
        WHEN label LIKE '%clien%' THEN 'Satisfied Clients'
        WHEN label LIKE '%termen%' OR label LIKE '%zile%' OR label LIKE '%luni%' THEN 'Average Processing Time'
        ELSE label
    END,
    CASE 
        WHEN suffix LIKE '%ani%' OR suffix LIKE '%years%' THEN 'years'
        WHEN suffix LIKE '%luni%' THEN 'months'
        WHEN suffix LIKE '%zile%' THEN 'days'
        WHEN suffix = '%' THEN '%'
        WHEN suffix = '+' THEN '+'
        ELSE suffix
    END
FROM about_stats WHERE enabled = 1
ON DUPLICATE KEY UPDATE label = VALUES(label), suffix = VALUES(suffix);

-- Russian
INSERT INTO about_stats_translations (stat_id, language, label, suffix)
SELECT id, 'ru',
    CASE 
        WHEN label LIKE '%ani experien%' OR label LIKE '%ani de experiență%' THEN 'Лет опыта'
        WHEN label LIKE '%dosare%' OR label LIKE '%cazuri%' THEN 'Успешных дел'
        WHEN label LIKE '%clien%' THEN 'Довольных клиентов'
        WHEN label LIKE '%termen%' OR label LIKE '%zile%' OR label LIKE '%luni%' THEN 'Средний срок обработки'
        ELSE label
    END,
    CASE 
        WHEN suffix LIKE '%ani%' OR suffix LIKE '%years%' THEN 'лет'
        WHEN suffix LIKE '%luni%' THEN 'мес.'
        WHEN suffix LIKE '%zile%' THEN 'дней'
        WHEN suffix = '%' THEN '%'
        WHEN suffix = '+' THEN '+'
        ELSE suffix
    END
FROM about_stats WHERE enabled = 1
ON DUPLICATE KEY UPDATE label = VALUES(label), suffix = VALUES(suffix);

-- =============================================
-- SERVICES SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO services_section_translations (section_id, language, section_label, title, description)
SELECT id, 'en',
    'Our Services',
    'Complete Services for Romanian Documents',
    'We offer a full range of services for obtaining Romanian citizenship and all related documents.'
FROM services_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description);

-- Russian
INSERT INTO services_section_translations (section_id, language, section_label, title, description)
SELECT id, 'ru',
    'Наши услуги',
    'Полный спектр услуг для румынских документов',
    'Мы предлагаем полный комплекс услуг по получению румынского гражданства и всех связанных документов.'
FROM services_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description);

-- =============================================
-- SERVICES TRANSLATIONS
-- =============================================

-- English
INSERT INTO services_translations (service_id, language, title, description, short_description)
SELECT id, 'en',
    CASE 
        WHEN title LIKE '%Cetățenie%' OR title LIKE '%cetățen%' THEN 'Romanian Citizenship'
        WHEN title LIKE '%Pașaport%' OR title LIKE '%pașaport%' THEN 'Romanian Passport'
        WHEN title LIKE '%Buletin%' OR title LIKE '%buletin%' OR title LIKE '%carte de identitate%' THEN 'Romanian ID Card'
        WHEN title LIKE '%Act%' OR title LIKE '%naștere%' THEN 'Birth Certificate'
        WHEN title LIKE '%Căsătorie%' OR title LIKE '%căsătorie%' THEN 'Marriage Certificate'
        WHEN title LIKE '%Apostil%' OR title LIKE '%apostil%' THEN 'Apostille Service'
        WHEN title LIKE '%Traducere%' OR title LIKE '%traducere%' THEN 'Document Translation'
        WHEN title LIKE '%Consultanță%' OR title LIKE '%consultanță%' THEN 'Legal Consultation'
        ELSE title
    END,
    CASE 
        WHEN title LIKE '%Cetățenie%' OR title LIKE '%cetățen%' THEN 'Full support for obtaining Romanian citizenship. We handle all documentation, translation, legalization, and submission to the authorities.'
        WHEN title LIKE '%Pașaport%' OR title LIKE '%pașaport%' THEN 'Fast obtaining of Romanian passport after receiving citizenship. Guidance through the entire process.'
        WHEN title LIKE '%Buletin%' OR title LIKE '%buletin%' OR title LIKE '%carte de identitate%' THEN 'Complete service for obtaining Romanian ID card. Personal assistance at all stages.'
        WHEN title LIKE '%Act%' OR title LIKE '%naștere%' THEN 'Obtaining birth certificates from Romania with apostille and translation.'
        WHEN title LIKE '%Căsătorie%' OR title LIKE '%căsătorie%' THEN 'Obtaining marriage certificates with all necessary legalizations.'
        WHEN title LIKE '%Apostil%' OR title LIKE '%apostil%' THEN 'Apostille service for all types of Romanian documents.'
        WHEN title LIKE '%Traducere%' OR title LIKE '%traducere%' THEN 'Certified translations of all documents from/to Romanian.'
        WHEN title LIKE '%Consultanță%' OR title LIKE '%consultanță%' THEN 'Free consultation on all matters related to Romanian documents.'
        ELSE description
    END,
    short_description
FROM services WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- Russian
INSERT INTO services_translations (service_id, language, title, description, short_description)
SELECT id, 'ru',
    CASE 
        WHEN title LIKE '%Cetățenie%' OR title LIKE '%cetățen%' THEN 'Румынское гражданство'
        WHEN title LIKE '%Pașaport%' OR title LIKE '%pașaport%' THEN 'Румынский паспорт'
        WHEN title LIKE '%Buletin%' OR title LIKE '%buletin%' OR title LIKE '%carte de identitate%' THEN 'Румынское удостоверение личности'
        WHEN title LIKE '%Act%' OR title LIKE '%naștere%' THEN 'Свидетельство о рождении'
        WHEN title LIKE '%Căsătorie%' OR title LIKE '%căsătorie%' THEN 'Свидетельство о браке'
        WHEN title LIKE '%Apostil%' OR title LIKE '%apostil%' THEN 'Услуги апостиля'
        WHEN title LIKE '%Traducere%' OR title LIKE '%traducere%' THEN 'Перевод документов'
        WHEN title LIKE '%Consultanță%' OR title LIKE '%consultanță%' THEN 'Юридическая консультация'
        ELSE title
    END,
    CASE 
        WHEN title LIKE '%Cetățenie%' OR title LIKE '%cetățen%' THEN 'Полное сопровождение в получении румынского гражданства. Мы занимаемся всей документацией, переводом, легализацией и подачей в органы власти.'
        WHEN title LIKE '%Pașaport%' OR title LIKE '%pașaport%' THEN 'Быстрое получение румынского паспорта после получения гражданства. Помощь на всех этапах.'
        WHEN title LIKE '%Buletin%' OR title LIKE '%buletin%' OR title LIKE '%carte de identitate%' THEN 'Полный сервис по получению румынского удостоверения личности. Личное сопровождение на всех этапах.'
        WHEN title LIKE '%Act%' OR title LIKE '%naștere%' THEN 'Получение свидетельств о рождении из Румынии с апостилем и переводом.'
        WHEN title LIKE '%Căsătorie%' OR title LIKE '%căsătorie%' THEN 'Получение свидетельств о браке со всеми необходимыми легализациями.'
        WHEN title LIKE '%Apostil%' OR title LIKE '%apostil%' THEN 'Услуги апостиля для всех типов румынских документов.'
        WHEN title LIKE '%Traducere%' OR title LIKE '%traducere%' THEN 'Нотариально заверенные переводы всех документов с/на румынский язык.'
        WHEN title LIKE '%Consultanță%' OR title LIKE '%consultanță%' THEN 'Бесплатная консультация по всем вопросам, связанным с румынскими документами.'
        ELSE description
    END,
    short_description
FROM services WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- =============================================
-- PROCESS SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO process_section_translations (section_id, language, section_label, title, description)
SELECT id, 'en',
    'How It Works',
    'Simple Process in 4 Steps',
    'We simplify the procedure for obtaining Romanian documents.'
FROM process_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description);

-- Russian
INSERT INTO process_section_translations (section_id, language, section_label, title, description)
SELECT id, 'ru',
    'Как это работает',
    'Простой процесс в 4 шага',
    'Мы упрощаем процедуру получения румынских документов.'
FROM process_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description);

-- =============================================
-- PROCESS STEPS TRANSLATIONS
-- =============================================

-- English
INSERT INTO process_steps_translations (step_id, language, title, description)
SELECT id, 'en',
    CASE step_number
        WHEN 1 THEN 'Free Consultation'
        WHEN 2 THEN 'Document Preparation'
        WHEN 3 THEN 'Submission to Authorities'
        WHEN 4 THEN 'Receiving Documents'
        ELSE title
    END,
    CASE step_number
        WHEN 1 THEN 'We analyze your situation and determine the best route to obtaining Romanian citizenship and documents.'
        WHEN 2 THEN 'We prepare all necessary documents, translate them and certify them according to requirements.'
        WHEN 3 THEN 'We submit the file to the Romanian authorities and monitor the entire process.'
        WHEN 4 THEN 'You receive your Romanian citizenship, passport and/or ID card.'
        ELSE description
    END
FROM process_steps WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- Russian
INSERT INTO process_steps_translations (step_id, language, title, description)
SELECT id, 'ru',
    CASE step_number
        WHEN 1 THEN 'Бесплатная консультация'
        WHEN 2 THEN 'Подготовка документов'
        WHEN 3 THEN 'Подача в органы власти'
        WHEN 4 THEN 'Получение документов'
        ELSE title
    END,
    CASE step_number
        WHEN 2 THEN 'Мы подготовим все необходимые документы, переведем их и заверим согласно требованиям.'
        WHEN 2 THEN 'Мы подготовим все необходимые документы, переведем и заверим согласно требованиям.'
        WHEN 3 THEN 'Мы подаем дело в румынские органы власти и контролируем весь процесс.'
        WHEN 4 THEN 'Вы получаете румынское гражданство, паспорт и/или удостоверение личности.'
        ELSE description
    END
FROM process_steps WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- =============================================
-- WHY US SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO why_us_translations (why_us_id, language, section_label, title)
SELECT id, 'en',
    'Why Choose Us',
    'Why Work With Us?'
FROM why_us WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title);

-- Russian
INSERT INTO why_us_translations (why_us_id, language, section_label, title)
SELECT id, 'ru',
    'Почему мы',
    'Почему выбирают нас?'
FROM why_us WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title);

-- =============================================
-- WHY US ITEMS TRANSLATIONS
-- =============================================

-- English
INSERT INTO why_us_items_translations (item_id, language, title, description)
SELECT id, 'en',
    CASE 
        WHEN title LIKE '%experien%' THEN '15+ Years Experience'
        WHEN title LIKE '%garanta%' OR title LIKE '%Garanta%' THEN 'Guaranteed Results'
        WHEN title LIKE '%rapid%' OR title LIKE '%Rapid%' THEN 'Fast Processing'
        WHEN title LIKE '%transparen%' OR title LIKE '%Transparen%' THEN 'Full Transparency'
        WHEN title LIKE '%profesi%' OR title LIKE '%Profesi%' THEN 'Professional Team'
        WHEN title LIKE '%asisten%' OR title LIKE '%Asisten%' THEN 'Complete Assistance'
        ELSE title
    END,
    CASE 
        WHEN title LIKE '%experien%' THEN 'Over 15 years of experience in obtaining Romanian citizenship and documents.'
        WHEN title LIKE '%garanta%' OR title LIKE '%Garanta%' THEN 'We guarantee the success of your case or refund your money.'
        WHEN title LIKE '%rapid%' OR title LIKE '%Rapid%' THEN 'The fastest processing times on the market.'
        WHEN title LIKE '%transparen%' OR title LIKE '%Transparen%' THEN 'Clear prices, no hidden costs. You know exactly what you pay for.'
        WHEN title LIKE '%profesi%' OR title LIKE '%Profesi%' THEN 'Team of specialized lawyers and notaries.'
        WHEN title LIKE '%asisten%' OR title LIKE '%Asisten%' THEN 'We assist you at all stages of the process.'
        ELSE description
    END
FROM why_us_items WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- Russian
INSERT INTO why_us_items_translations (item_id, language, title, description)
SELECT id, 'ru',
    CASE 
        WHEN title LIKE '%experien%' THEN '15+ лет опыта'
        WHEN title LIKE '%garanta%' OR title LIKE '%Garanta%' THEN 'Гарантированный результат'
        WHEN title LIKE '%rapid%' OR title LIKE '%Rapid%' THEN 'Быстрая обработка'
        WHEN title LIKE '%transparen%' OR title LIKE '%Transparen%' THEN 'Полная прозрачность'
        WHEN title LIKE '%profesi%' OR title LIKE '%Profesi%' THEN 'Профессиональная команда'
        WHEN title LIKE '%asisten%' OR title LIKE '%Asisten%' THEN 'Полное сопровождение'
        ELSE title
    END,
    CASE 
        WHEN title LIKE '%experien%' THEN 'Более 15 лет опыта в получении румынского гражданства и документов.'
        WHEN title LIKE '%garanta%' OR title LIKE '%Garanta%' THEN 'Мы гарантируем успех вашего дела или вернем деньги.'
        WHEN title LIKE '%rapid%' OR title LIKE '%Rapid%' THEN 'Самые быстрые сроки обработки на рынке.'
        WHEN title LIKE '%transparen%' OR title LIKE '%Transparen%' THEN 'Четкие цены, никаких скрытых расходов. Вы точно знаете, за что платите.'
        WHEN title LIKE '%profesi%' OR title LIKE '%Profesi%' THEN 'Команда специализированных юристов и нотариусов.'
        WHEN title LIKE '%asisten%' OR title LIKE '%Asisten%' THEN 'Мы сопровождаем вас на всех этапах процесса.'
        ELSE description
    END
FROM why_us_items WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    description = VALUES(description);

-- =============================================
-- FAQ SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO faq_section_translations (section_id, language, section_label, title)
SELECT id, 'en',
    'Frequently Asked Questions',
    'FAQ'
FROM faq_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title);

-- Russian
INSERT INTO faq_section_translations (section_id, language, section_label, title)
SELECT id, 'ru',
    'Часто задаваемые вопросы',
    'FAQ'
FROM faq_section WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title);

-- =============================================
-- FAQ TRANSLATIONS
-- =============================================

-- English FAQs
INSERT INTO faq_translations (faq_id, language, question, answer)
SELECT id, 'en',
    CASE 
        WHEN question LIKE '%cât durează%' OR question LIKE '%dureaza%' OR question LIKE '%timp%' THEN 'How long does it take to obtain Romanian citizenship?'
        WHEN question LIKE '%cost%' OR question LIKE '%preț%' OR question LIKE '%pret%' THEN 'How much do your services cost?'
        WHEN question LIKE '%documente%' OR question LIKE '%necesare%' THEN 'What documents are needed?'
        WHEN question LIKE '%eligibil%' OR question LIKE '%poate%' OR question LIKE '%cine%' THEN 'Who is eligible for Romanian citizenship?'
        WHEN question LIKE '%garanție%' OR question LIKE '%garantie%' OR question LIKE '%garantați%' THEN 'Do you offer any guarantees?'
        WHEN question LIKE '%prezență%' OR question LIKE '%prezent%' OR question LIKE '%România%' THEN 'Do I need to be present in Romania?'
        ELSE question
    END,
    CASE 
        WHEN question LIKE '%cât durează%' OR question LIKE '%dureaza%' OR question LIKE '%timp%' THEN 'The process usually takes 6-12 months, depending on the complexity of the case and the completeness of documents.'
        WHEN question LIKE '%cost%' OR question LIKE '%preț%' OR question LIKE '%pret%' THEN 'Prices vary depending on the services requested. Contact us for a free consultation and personalized quote.'
        WHEN question LIKE '%documente%' OR question LIKE '%necesare%' THEN 'The required documents include: birth certificate, marriage certificate (if applicable), proof of Romanian ancestry, ID, etc. We will provide you with the complete list after consultation.'
        WHEN question LIKE '%eligibil%' OR question LIKE '%poate%' OR question LIKE '%cine%' THEN 'Anyone who can prove Romanian ancestry up to 3 generations can apply for citizenship.'
        WHEN question LIKE '%garanție%' OR question LIKE '%garantie%' OR question LIKE '%garantați%' THEN 'Yes, we offer a success guarantee. If we fail to obtain your citizenship, we refund your fees.'
        WHEN question LIKE '%prezență%' OR question LIKE '%prezent%' OR question LIKE '%România%' THEN 'In most cases, physical presence in Romania is required only for the oath ceremony. We handle all documentation remotely.'
        ELSE answer
    END
FROM faq WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    question = VALUES(question),
    answer = VALUES(answer);

-- Russian FAQs
INSERT INTO faq_translations (faq_id, language, question, answer)
SELECT id, 'ru',
    CASE 
        WHEN question LIKE '%cât durează%' OR question LIKE '%dureaza%' OR question LIKE '%timp%' THEN 'Сколько времени занимает получение румынского гражданства?'
        WHEN question LIKE '%cost%' OR question LIKE '%preț%' OR question LIKE '%pret%' THEN 'Сколько стоят ваши услуги?'
        WHEN question LIKE '%documente%' OR question LIKE '%necesare%' THEN 'Какие документы необходимы?'
        WHEN question LIKE '%eligibil%' OR question LIKE '%poate%' OR question LIKE '%cine%' THEN 'Кто имеет право на румынское гражданство?'
        WHEN question LIKE '%garanție%' OR question LIKE '%garantie%' OR question LIKE '%garantați%' THEN 'Вы даете какие-либо гарантии?'
        WHEN question LIKE '%prezență%' OR question LIKE '%prezent%' OR question LIKE '%România%' THEN 'Нужно ли мне присутствовать в Румынии?'
        ELSE question
    END,
    CASE 
        WHEN question LIKE '%cât durează%' OR question LIKE '%dureaza%' OR question LIKE '%timp%' THEN 'Процесс обычно занимает 6-12 месяцев, в зависимости от сложности дела и полноты документов.'
        WHEN question LIKE '%cost%' OR question LIKE '%preț%' OR question LIKE '%pret%' THEN 'Цены варьируются в зависимости от запрашиваемых услуг. Свяжитесь с нами для бесплатной консультации и индивидуального предложения.'
        WHEN question LIKE '%documente%' OR question LIKE '%necesare%' THEN 'Необходимые документы включают: свидетельство о рождении, свидетельство о браке (если применимо), доказательство румынского происхождения, удостоверение личности и др. Мы предоставим вам полный список после консультации.'
        WHEN question LIKE '%eligibil%' OR question LIKE '%poate%' OR question LIKE '%cine%' THEN 'Любой человек, который может доказать румынское происхождение до 3 поколений, может подать заявление на гражданство.'
        WHEN question LIKE '%garanție%' OR question LIKE '%garantie%' OR question LIKE '%garantați%' THEN 'Да, мы предоставляем гарантию успеха. Если мы не сможем получить ваше гражданство, мы вернем ваши деньги.'
        WHEN question LIKE '%prezență%' OR question LIKE '%prezent%' OR question LIKE '%România%' THEN 'В большинстве случаев личное присутствие в Румынии требуется только для церемонии присяги. Мы обрабатываем всю документацию удаленно.'
        ELSE answer
    END
FROM faq WHERE enabled = 1
ON DUPLICATE KEY UPDATE 
    question = VALUES(question),
    answer = VALUES(answer);

-- =============================================
-- CONTACT SECTION TRANSLATIONS
-- =============================================

-- English
INSERT INTO contact_translations (contact_id, language, section_label, title, description, form_title)
SELECT id, 'en',
    'Contact',
    'Contact Us',
    'We are available for any questions. Contact us via phone, email, or the form below.',
    'Send Us a Message'
FROM contact WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description),
    form_title = VALUES(form_title);

-- Russian
INSERT INTO contact_translations (contact_id, language, section_label, title, description, form_title)
SELECT id, 'ru',
    'Контакты',
    'Свяжитесь с нами',
    'Мы готовы ответить на любые вопросы. Свяжитесь с нами по телефону, электронной почте или через форму ниже.',
    'Напишите нам'
FROM contact WHERE id = 1
ON DUPLICATE KEY UPDATE 
    section_label = VALUES(section_label),
    title = VALUES(title),
    description = VALUES(description),
    form_title = VALUES(form_title);

-- =============================================
-- DONE!
-- =============================================
SELECT 'Translation data inserted successfully!' AS status;
