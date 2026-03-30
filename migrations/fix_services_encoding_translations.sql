-- Migration: Fix encoding and add translations for new services
-- Run with: mysql -u root acteromania_cms --default-character-set=utf8mb4 < fix_services_encoding_translations.sql

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- =====================================================
-- FIX MAIN SERVICES TABLE (id 18, 19, 20 and others)
-- =====================================================

-- Serviciul 18: Consultație gratuită
UPDATE services SET 
    title = 'Consultație gratuită',
    description = 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră și vă oferim sfaturi personalizate.',
    short_description = 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române.',
    full_description = 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră, verificăm eligibilitatea și vă oferim sfaturi personalizate pentru următorii pași. Consultația se poate face online sau la sediul nostru.',
    features = '["Consultanță online sau la sediu", "Verificare eligibilitate gratuită", "Răspunsuri la toate întrebările", "Planificare personalizată"]'
WHERE id = 18;

-- Serviciul 19: Pregătirea dosarului și depunerea
UPDATE services SET 
    title = 'Pregătirea dosarului și depunerea',
    description = 'Vă ajutăm cu strângerea și pregătirea tuturor actelor necesare pentru dosar, precum și cu depunerea acestuia la ANC.',
    short_description = 'Pregătirea completă a dosarului pentru cetățenie română și depunerea la ANC.',
    full_description = 'Vă asistăm complet în pregătirea dosarului pentru obținerea cetățeniei române. Actele necesare includ: certificat de naștere, certificat de căsătorie (dacă este cazul), acte de identitate, documente care dovedesc legătura cu România (acte ale părinților/bunicilor), traduceri autorizate și apostile. Ne ocupăm de strângerea, verificarea și depunerea dosarului la ANC.',
    features = '["Certificat de naștere și căsătorie", "Acte de identitate valabile", "Documente ale ascendenților români", "Traduceri autorizate", "Apostilare acte", "Depunere la ANC"]'
WHERE id = 19;

-- Serviciul 20: Urgentare dosar
UPDATE services SET 
    title = 'Urgentare dosar',
    description = 'Servicii de urgentare a dosarului de cetățenie pentru reducerea timpului de așteptare și procesare mai rapidă.',
    short_description = 'Urgentarea dosarului de cetățenie pentru procesare mai rapidă la ANC.',
    full_description = 'Oferim servicii de urgentare a dosarului de cetățenie română pentru cei care au nevoie de procesare mai rapidă. Monitorizăm statusul dosarului, intervenim pentru deblocarea cazurilor întârziate și asigurăm comunicarea eficientă cu autoritățile competente.',
    features = '["Monitorizare activă dosar", "Intervenție la ANC", "Reducere timp așteptare", "Comunicare cu autoritățile", "Rapoarte de status"]'
WHERE id = 20;

-- Serviciul 6: Publicare ordin și Depunerea jurământului
UPDATE services SET 
    title = 'Publicare ordin și Depunerea jurământului',
    description = 'Vă asistăm de la publicarea ordinului de acordare a cetățeniei până la depunerea jurământului de credință față de România.',
    short_description = 'Asistență completă de la publicarea ordinului până la depunerea jurământului de credință.',
    full_description = 'Vă asistăm în întregul proces final de obținere a cetățeniei: monitorizarea publicării ordinului în Monitorul Oficial, programarea pentru depunerea jurământului de credință la ANC București sau la consulatele din străinătate, pregătirea pentru ceremonie și obținerea certificatului de cetățenie.',
    features = '["Monitorizare publicare ordin", "Programare jurământ ANC/Consulate", "Pregătire ceremonie", "Asistență la depunere jurământ", "Obținere certificat cetățenie"]'
WHERE id = 6;

-- Serviciul 4: Transcrierea actelor de stare civilă
UPDATE services SET 
    title = 'Transcrierea actelor de stare civilă',
    description = 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă în registrele românești.',
    short_description = 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă.',
    full_description = 'Servicii complete de transcriere a actelor de stare civilă (certificat de naștere, certificat de căsătorie, certificat de deces) din registrele străine în registrele românești. Necesar pentru obținerea actelor de identitate românești după cetățenie.',
    features = '["căsătorie / divorț / deces pe actul de naștere sau căsătorie", "Eliberare duplicate", "Rectificare date"]'
WHERE id = 4;

-- Serviciul 3: Buletin
UPDATE services SET 
    title = 'Buletin',
    description = 'Servicii complete pentru obținerea buletinului românesc (carte de identitate). Asistență cu stabilirea domiciliului și programarea la SPCLEP.',
    short_description = 'Servicii complete pentru obținerea buletinului românesc (carte de identitate).'
WHERE id = 3;

-- Serviciul 2: Pașaport
UPDATE services SET 
    title = 'Pașaport',
    description = 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură.',
    short_description = 'Obținerea pașaportului românesc - programări, documente și procedură completă.'
WHERE id = 2;

-- Serviciul 16: Transport
UPDATE services SET 
    title = 'Transport',
    description = 'Transport persoane și colete între Moldova și România. Curse regulate săptămânale la prețuri accesibile.',
    short_description = 'Transport persoane și colete între Moldova și România.'
WHERE id = 16;

-- =====================================================
-- TRANSLATIONS - ROMANIAN (RO)
-- =====================================================

-- Delete existing translations for new services to avoid duplicates
DELETE FROM services_translations WHERE service_id IN (18, 19, 20) OR (service_id IN (2, 3, 4, 6, 16) AND language IN ('ro', 'en', 'ru'));

-- Service 18: Consultație gratuită - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(18, 'ro', 'Consultație gratuită', 
'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră și vă oferim sfaturi personalizate.',
'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române.',
'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră, verificăm eligibilitatea și vă oferim sfaturi personalizate pentru următorii pași. Consultația se poate face online sau la sediul nostru.',
'["Consultanță online sau la sediu", "Verificare eligibilitate gratuită", "Răspunsuri la toate întrebările", "Planificare personalizată"]');

-- Service 19: Pregătirea dosarului și depunerea - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(19, 'ro', 'Pregătirea dosarului și depunerea',
'Vă ajutăm cu strângerea și pregătirea tuturor actelor necesare pentru dosar, precum și cu depunerea acestuia la ANC.',
'Pregătirea completă a dosarului pentru cetățenie română și depunerea la ANC.',
'Vă asistăm complet în pregătirea dosarului pentru obținerea cetățeniei române. Actele necesare includ: certificat de naștere, certificat de căsătorie (dacă este cazul), acte de identitate, documente care dovedesc legătura cu România (acte ale părinților/bunicilor), traduceri autorizate și apostile. Ne ocupăm de strângerea, verificarea și depunerea dosarului la ANC.',
'["Certificat de naștere și căsătorie", "Acte de identitate valabile", "Documente ale ascendenților români", "Traduceri autorizate", "Apostilare acte", "Depunere la ANC"]');

-- Service 20: Urgentare dosar - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(20, 'ro', 'Urgentare dosar',
'Servicii de urgentare a dosarului de cetățenie pentru reducerea timpului de așteptare și procesare mai rapidă.',
'Urgentarea dosarului de cetățenie pentru procesare mai rapidă la ANC.',
'Oferim servicii de urgentare a dosarului de cetățenie română pentru cei care au nevoie de procesare mai rapidă. Monitorizăm statusul dosarului, intervenim pentru deblocarea cazurilor întârziate și asigurăm comunicarea eficientă cu autoritățile competente.',
'["Monitorizare activă dosar", "Intervenție la ANC", "Reducere timp așteptare", "Comunicare cu autoritățile", "Rapoarte de status"]');

-- Service 6: Publicare ordin și Depunerea jurământului - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(6, 'ro', 'Publicare ordin și Depunerea jurământului',
'Vă asistăm de la publicarea ordinului de acordare a cetățeniei până la depunerea jurământului de credință față de România.',
'Asistență completă de la publicarea ordinului până la depunerea jurământului de credință.',
'Vă asistăm în întregul proces final de obținere a cetățeniei: monitorizarea publicării ordinului în Monitorul Oficial, programarea pentru depunerea jurământului de credință la ANC București sau la consulatele din străinătate, pregătirea pentru ceremonie și obținerea certificatului de cetățenie.',
'["Monitorizare publicare ordin", "Programare jurământ ANC/Consulate", "Pregătire ceremonie", "Asistență la depunere jurământ", "Obținere certificat cetățenie"]');

-- Service 4: Transcrierea actelor de stare civilă - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(4, 'ro', 'Transcrierea actelor de stare civilă',
'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă în registrele românești.',
'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă.',
'Servicii complete de transcriere a actelor de stare civilă (certificat de naștere, certificat de căsătorie, certificat de deces) din registrele străine în registrele românești. Necesar pentru obținerea actelor de identitate românești după cetățenie.',
'["căsătorie / divorț / deces pe actul de naștere sau căsătorie", "Eliberare duplicate", "Rectificare date"]');

-- Service 3: Buletin - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(3, 'ro', 'Buletin',
'Servicii complete pentru obținerea buletinului românesc (carte de identitate). Asistență cu stabilirea domiciliului și programarea la SPCLEP.',
'Servicii complete pentru obținerea buletinului românesc (carte de identitate).',
'Servicii complete pentru obținerea cărții de identitate românești. Vă asistăm cu stabilirea domiciliului, pregătirea actelor și programarea la SPCLEP.',
'["Programare online", "Verificare acte", "Asistență depunere", "Livrare rapidă"]');

-- Service 2: Pașaport - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(2, 'ro', 'Pașaport',
'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură.',
'Obținerea pașaportului românesc - programări, documente și procedură completă.',
'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură pentru pașaport simplu sau CRDS.',
'["Programare la instituții", "Pregătire documente necesare", "Asistență la depunere", "Livrare la domiciliu"]');

-- Service 16: Transport - RO
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(16, 'ro', 'Transport',
'Transport persoane și colete între Moldova și România. Curse regulate săptămânale la prețuri accesibile.',
'Transport persoane și colete între Moldova și România.',
'Transport persoane și colete între Moldova, România și diasporă. Curse regulate săptămânale la prețuri accesibile.',
'["Curse regulate", "Transport persoane", "Colete și documente", "Prețuri accesibile"]');

-- =====================================================
-- TRANSLATIONS - ENGLISH (EN)
-- =====================================================

-- Service 18: Free Consultation - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(18, 'en', 'Free Consultation',
'We offer a free initial consultation to help you understand the process of obtaining Romanian citizenship. We analyze your situation and provide personalized advice.',
'We offer a free initial consultation to help you understand the Romanian citizenship process.',
'We offer a free initial consultation to help you understand the process of obtaining Romanian citizenship. We analyze your situation, verify eligibility, and provide personalized advice for the next steps. Consultation can be done online or at our office.',
'["Online or in-office consultation", "Free eligibility check", "Answers to all questions", "Personalized planning"]');

-- Service 19: File Preparation and Submission - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(19, 'en', 'File Preparation and Submission',
'We help you gather and prepare all necessary documents for your file, as well as submit it to the ANC.',
'Complete file preparation for Romanian citizenship and submission to ANC.',
'We fully assist you in preparing your file for obtaining Romanian citizenship. Required documents include: birth certificate, marriage certificate (if applicable), identity documents, documents proving connection to Romania (parents/grandparents documents), authorized translations and apostilles. We handle gathering, verification, and submission to the ANC.',
'["Birth and marriage certificates", "Valid identity documents", "Romanian ancestors documents", "Authorized translations", "Document apostille", "ANC submission"]');

-- Service 20: File Expediting - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(20, 'en', 'File Expediting',
'File expediting services for citizenship to reduce waiting time and faster processing.',
'Citizenship file expediting for faster processing at ANC.',
'We offer Romanian citizenship file expediting services for those who need faster processing. We monitor file status, intervene to unblock delayed cases, and ensure efficient communication with competent authorities.',
'["Active file monitoring", "ANC intervention", "Reduced waiting time", "Communication with authorities", "Status reports"]');

-- Service 6: Order Publication and Oath Ceremony - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(6, 'en', 'Order Publication and Oath Ceremony',
'We assist you from the publication of the citizenship order to taking the oath of allegiance to Romania.',
'Complete assistance from order publication to taking the oath of allegiance.',
'We assist you throughout the final process of obtaining citizenship: monitoring the publication of the order in the Official Gazette, scheduling the oath of allegiance at ANC Bucharest or consulates abroad, ceremony preparation, and obtaining the citizenship certificate.',
'["Order publication monitoring", "Oath scheduling ANC/Consulates", "Ceremony preparation", "Oath assistance", "Citizenship certificate"]');

-- Service 4: Civil Status Documents Transcription - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(4, 'en', 'Civil Status Documents Transcription',
'Transcription of birth certificates, marriage certificates and other civil status documents into Romanian registers.',
'Transcription of birth, marriage and other civil status certificates.',
'Complete services for transcribing civil status documents (birth certificate, marriage certificate, death certificate) from foreign registers into Romanian registers. Required for obtaining Romanian identity documents after citizenship.',
'["Marriage / divorce / death on the birth or marriage certificate", "Duplicate issuance", "Data correction"]');

-- Service 3: ID Card - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(3, 'en', 'ID Card',
'Complete services for obtaining Romanian ID card. Assistance with residence establishment and SPCLEP appointment.',
'Complete services for obtaining Romanian ID card.',
'Complete services for obtaining Romanian identity card. We assist with residence establishment, document preparation, and SPCLEP appointment.',
'["Online appointment", "Document verification", "Submission assistance", "Fast delivery"]');

-- Service 2: Passport - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(2, 'en', 'Passport',
'Get your Romanian passport quickly and hassle-free. We handle appointments, documents, and the entire procedure.',
'Romanian passport - appointments, documents, and complete procedure.',
'Get your Romanian passport quickly and hassle-free. We handle appointments, documents, and the entire procedure for simple passport or CRDS.',
'["Institution appointments", "Document preparation", "Submission assistance", "Home delivery"]');

-- Service 16: Transport - EN
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(16, 'en', 'Transport',
'Person and parcel transport between Moldova and Romania. Regular weekly routes at affordable prices.',
'Person and parcel transport between Moldova and Romania.',
'Person and parcel transport between Moldova, Romania and the diaspora. Regular weekly routes at affordable prices.',
'["Regular routes", "Person transport", "Parcels and documents", "Affordable prices"]');

-- =====================================================
-- TRANSLATIONS - RUSSIAN (RU)
-- =====================================================

-- Service 18: Бесплатная консультация - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(18, 'ru', 'Бесплатная консультация',
'Мы предлагаем бесплатную первичную консультацию, чтобы помочь вам понять процесс получения румынского гражданства. Анализируем вашу ситуацию и даём персональные рекомендации.',
'Бесплатная первичная консультация по получению румынского гражданства.',
'Мы предлагаем бесплатную первичную консультацию, чтобы помочь вам понять процесс получения румынского гражданства. Анализируем вашу ситуацию, проверяем соответствие требованиям и даём персональные рекомендации по дальнейшим шагам. Консультация возможна онлайн или в нашем офисе.',
'["Онлайн или в офисе", "Бесплатная проверка соответствия", "Ответы на все вопросы", "Персональное планирование"]');

-- Service 19: Подготовка и подача досье - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(19, 'ru', 'Подготовка и подача досье',
'Помогаем собрать и подготовить все необходимые документы для досье, а также подать его в ANC.',
'Полная подготовка досье на румынское гражданство и подача в ANC.',
'Полностью помогаем в подготовке досье для получения румынского гражданства. Необходимые документы: свидетельство о рождении, свидетельство о браке (при наличии), документы удостоверяющие личность, документы подтверждающие связь с Румынией (документы родителей/бабушек и дедушек), заверенные переводы и апостили. Занимаемся сбором, проверкой и подачей в ANC.',
'["Свидетельства о рождении и браке", "Действующие документы", "Документы предков-румын", "Заверенные переводы", "Апостиль документов", "Подача в ANC"]');

-- Service 20: Ускорение досье - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(20, 'ru', 'Ускорение досье',
'Услуги по ускорению рассмотрения досье на гражданство для сокращения времени ожидания.',
'Ускорение досье на гражданство для более быстрого рассмотрения в ANC.',
'Предлагаем услуги по ускорению досье на румынское гражданство для тех, кому нужно более быстрое рассмотрение. Отслеживаем статус досье, вмешиваемся для разблокировки задержанных дел и обеспечиваем эффективную связь с компетентными органами.',
'["Активный мониторинг досье", "Вмешательство в ANC", "Сокращение времени ожидания", "Связь с властями", "Отчёты о статусе"]');

-- Service 6: Публикация приказа и Присяга - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(6, 'ru', 'Публикация приказа и Присяга',
'Помогаем от публикации приказа о предоставлении гражданства до принесения присяги верности Румынии.',
'Полная помощь от публикации приказа до принесения присяги.',
'Помогаем на всём финальном этапе получения гражданства: мониторинг публикации приказа в Официальном вестнике, запись на присягу в ANC Бухарест или консульства за рубежом, подготовка к церемонии и получение сертификата о гражданстве.',
'["Мониторинг публикации приказа", "Запись на присягу ANC/Консульства", "Подготовка к церемонии", "Помощь при присяге", "Сертификат о гражданстве"]');

-- Service 4: Транскрипция актов гражданского состояния - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(4, 'ru', 'Транскрипция актов гражданского состояния',
'Транскрипция свидетельств о рождении, браке и других актов гражданского состояния в румынские реестры.',
'Транскрипция свидетельств о рождении, браке и других актов.',
'Полный комплекс услуг по транскрипции актов гражданского состояния (свидетельство о рождении, браке, смерти) из иностранных реестров в румынские. Необходимо для получения румынских документов после гражданства.',
'["Брак / развод / смерть в свидетельстве о рождении или браке", "Выдача дубликатов", "Исправление данных"]');

-- Service 3: Удостоверение личности - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(3, 'ru', 'Удостоверение личности',
'Полный комплекс услуг для получения румынского удостоверения личности. Помощь с пропиской и записью в SPCLEP.',
'Полный комплекс услуг для получения румынского удостоверения личности.',
'Полный комплекс услуг для получения румынского удостоверения личности. Помогаем с пропиской, подготовкой документов и записью в SPCLEP.',
'["Онлайн запись", "Проверка документов", "Помощь при подаче", "Быстрая доставка"]');

-- Service 2: Паспорт - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(2, 'ru', 'Паспорт',
'Получите румынский паспорт быстро и без хлопот. Занимаемся записями, документами и всей процедурой.',
'Румынский паспорт - записи, документы и полная процедура.',
'Получите румынский паспорт быстро и без хлопот. Занимаемся записями, документами и всей процедурой для простого паспорта или CRDS.',
'["Запись в учреждения", "Подготовка документов", "Помощь при подаче", "Доставка на дом"]');

-- Service 16: Транспорт - RU
INSERT INTO services_translations (service_id, language, title, description, short_description, full_description, features) VALUES
(16, 'ru', 'Транспорт',
'Перевозка людей и посылок между Молдовой и Румынией. Регулярные еженедельные рейсы по доступным ценам.',
'Перевозка людей и посылок между Молдовой и Румынией.',
'Перевозка людей и посылок между Молдовой, Румынией и диаспорой. Регулярные еженедельные рейсы по доступным ценам.',
'["Регулярные рейсы", "Перевозка людей", "Посылки и документы", "Доступные цены"]');

-- Verification
SELECT s.id, s.title, s.sort_order, st.language, st.title as translated_title
FROM services s
LEFT JOIN services_translations st ON s.id = st.service_id
WHERE s.sort_order <= 8
ORDER BY s.sort_order, st.language;
