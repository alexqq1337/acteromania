-- ActeRomânia CMS Database Schema
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS acteromania_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE acteromania_cms;

-- Site Settings (general configuration)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Hero Section
CREATE TABLE IF NOT EXISTS hero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    cta_text VARCHAR(100),
    cta_link VARCHAR(255),
    image_url TEXT,
    video_url TEXT,
    video_type ENUM('upload', 'youtube', 'vimeo') DEFAULT 'upload',
    media_type ENUM('image', 'video') DEFAULT 'image',
    trust_bar_enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Hero Trust Bar Items
CREATE TABLE IF NOT EXISTS hero_trust_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon_svg TEXT,
    text VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- About Section
CREATE TABLE IF NOT EXISTS about (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image_url TEXT,
    counters_enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- About Statistics/Counters
CREATE TABLE IF NOT EXISTS about_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon_svg TEXT,
    number_value INT NOT NULL,
    suffix VARCHAR(20),
    label VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url TEXT,
    icon_svg TEXT,
    features TEXT, -- JSON array of features
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services Section Settings
CREATE TABLE IF NOT EXISTS services_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Process Steps (Cum Funcționează)
CREATE TABLE IF NOT EXISTS process_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    step_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url TEXT,
    features TEXT, -- JSON array
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Process Section Settings
CREATE TABLE IF NOT EXISTS process_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Why Choose Us Section
CREATE TABLE IF NOT EXISTS why_us (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    image_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Why Choose Us Items
CREATE TABLE IF NOT EXISTS why_us_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon_svg TEXT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_location VARCHAR(255),
    client_photo TEXT,
    testimonial_text TEXT NOT NULL,
    rating INT DEFAULT 5,
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials Section Settings
CREATE TABLE IF NOT EXISTS testimonials_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- FAQ
CREATE TABLE IF NOT EXISTS faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- FAQ Section Settings
CREATE TABLE IF NOT EXISTS faq_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact Section
CREATE TABLE IF NOT EXISTS contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    phone VARCHAR(50),
    phone_icon TEXT,
    phone_enabled TINYINT(1) DEFAULT 1,
    whatsapp VARCHAR(50),
    whatsapp_icon TEXT,
    whatsapp_enabled TINYINT(1) DEFAULT 1,
    email VARCHAR(255),
    email_icon TEXT,
    email_enabled TINYINT(1) DEFAULT 1,
    address TEXT,
    address_icon TEXT,
    address_enabled TINYINT(1) DEFAULT 1,
    map_embed TEXT,
    map_enabled TINYINT(1) DEFAULT 1,
    form_title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Media Library
CREATE TABLE IF NOT EXISTS media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255),
    file_url TEXT NOT NULL,
    file_type VARCHAR(50),
    file_size INT,
    folder VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Users (for future multi-user support)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: "password")
INSERT INTO admin_users (username, password_hash, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@acteromania.ro');

-- Insert default Hero data
INSERT INTO hero (title, subtitle, cta_text, cta_link, image_url, trust_bar_enabled) VALUES 
('Asistență legală pentru documente românești', 
 'Servicii sigure, legale și transparente pentru cetățenii din Moldova și diaspora',
 'Programează o consultație',
 '#contact',
 'https://images.pexels.com/photos/5668858/pexels-photo-5668858.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
 1);

-- Insert Hero Trust Items
INSERT INTO hero_trust_items (icon_svg, text, sort_order) VALUES
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', '1000+ Clienți', 1),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>', 'Servicii Legale', 2),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>', 'Confidențialitate Garantată', 3);

-- Insert default About data
INSERT INTO about (section_label, title, content, image_url, counters_enabled) VALUES 
('Despre Noi',
 'Experiență și profesionalism în servicii juridice',
 '<p>Cu peste 10 ani de experiență în domeniul asistenței juridice pentru obținerea documentelor românești, echipa noastră a ajutat mii de clienți din Moldova și diaspora să își îndeplinească visul de a obține cetățenia română.</p><p>Ne mândrim cu o abordare personalizată, transparentă și profesională. Fiecare dosar este tratat cu maximă atenție, iar clienții noștri beneficiază de suport complet pe tot parcursul procesului.</p>',
 'https://images.pexels.com/photos/5668473/pexels-photo-5668473.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop',
 1);

-- Insert About Stats
INSERT INTO about_stats (icon_svg, number_value, suffix, label, sort_order) VALUES
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>', 10, '+', 'Ani de experiență', 1),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 2500, '+', 'Clienți ajutați', 2),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', 2500, '+', 'Dosare finalizate', 3),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', 98, '%', 'Rată de succes', 4);

-- Insert Services Section
INSERT INTO services_section (section_label, title, description) VALUES
('Serviciile Noastre', 'Soluții complete pentru documentele dumneavoastră', 'Oferim asistență juridică profesională pentru toate tipurile de documente românești');

-- Insert Services
INSERT INTO services (title, description, image_url, icon_svg, features, sort_order) VALUES
('Cetățenie Română', 'Asistență completă pentru obținerea cetățeniei române prin redobândire sau recunoaștere. Pregătim dosarul, vă ghidăm prin fiecare etapă și vă reprezentăm în fața autorităților.', 'https://images.pexels.com/photos/4427611/pexels-photo-4427611.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', '["Verificare eligibilitate","Pregătire dosar complet","Depunere și monitorizare"]', 1),
('Pașaport Românesc', 'Servicii de obținere și reînnoire a pașaportului românesc. Vă asistăm cu programarea, documentele necesare și procedura completă de emitere.', 'https://images.pexels.com/photos/4386442/pexels-photo-4386442.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="12" cy="12" r="3"/><line x1="7" y1="20" x2="7" y2="16"/><line x1="17" y1="20" x2="17" y2="16"/></svg>', '["Programare rapidă","Pregătire acte necesare","Asistență la depunere"]', 2),
('Buletin Românesc', 'Asistență pentru obținerea cărții de identitate românești. Vă ajutăm cu stabilirea domiciliului în România și toate procedurile aferente.', 'https://images.pexels.com/photos/4427616/pexels-photo-4427616.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2"/><line x1="14" y1="10" x2="20" y2="10"/><line x1="14" y1="14" x2="18" y2="14"/></svg>', '["Stabilire domiciliu","Documentație completă","Însoțire la autorități"]', 3),
('Consultanță Juridică', 'Servicii de consultanță juridică specializată în dreptul românesc. Vă consiliăm în probleme legate de acte de stare civilă, succesiuni și alte aspecte juridice.', 'https://images.pexels.com/photos/4427430/pexels-photo-4427430.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', '["Consultații online/fizice","Analiză situație personală","Recomandări personalizate"]', 4);

-- Insert Process Section
INSERT INTO process_section (section_label, title, description) VALUES
('Cum Funcționează', 'Procesul nostru simplu și transparent', 'Patru pași simpli către obținerea documentelor dumneavoastră românești');

-- Insert Process Steps
INSERT INTO process_steps (step_number, title, description, image_url, features, sort_order) VALUES
(1, 'Consultație Inițială', 'Programați o consultație gratuită pentru a discuta situația dumneavoastră și a stabili eligibilitatea pentru serviciile noastre.', 'https://images.pexels.com/photos/4427622/pexels-photo-4427622.jpeg?auto=compress&cs=tinysrgb&w=500&h=350&fit=crop', '["Analiză gratuită a cazului","Verificare eligibilitate","Plan personalizat"]', 1),
(2, 'Pregătirea Dosarului', 'Echipa noastră vă ghidează în strângerea documentelor necesare și pregătește dosarul complet pentru depunere.', 'https://images.pexels.com/photos/5668855/pexels-photo-5668855.jpeg?auto=compress&cs=tinysrgb&w=500&h=350&fit=crop', '["Listă documente necesare","Verificare acte","Traduceri și apostile"]', 2),
(3, 'Depunere și Monitorizare', 'Depunem dosarul la autoritățile competente și monitorizăm constant stadiul acestuia, informându-vă despre progres.', 'https://images.pexels.com/photos/5668859/pexels-photo-5668859.jpeg?auto=compress&cs=tinysrgb&w=500&h=350&fit=crop', '["Depunere oficială","Monitorizare continuă","Informări regulate"]', 3),
(4, 'Finalizare cu Succes', 'Vă asistăm în obținerea documentelor finale și vă îndrumăm în următorii pași pentru exercitarea drepturilor dumneavoastră.', 'https://images.pexels.com/photos/5668474/pexels-photo-5668474.jpeg?auto=compress&cs=tinysrgb&w=500&h=350&fit=crop', '["Primire documente","Îndrumare pași următori","Suport post-finalizare"]', 4);

-- Insert Why Us Section
INSERT INTO why_us (section_label, title, image_url) VALUES
('De Ce Să Ne Alegeți', 'Încredere și profesionalism la fiecare pas', 'https://images.pexels.com/photos/5668882/pexels-photo-5668882.jpeg?auto=compress&cs=tinysrgb&w=700&h=900&fit=crop');

-- Insert Why Us Items
INSERT INTO why_us_items (icon_svg, title, description, sort_order) VALUES
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>', 'Servicii 100% Legale', 'Toate procedurile sunt realizate în conformitate cu legislația română și europeană în vigoare.', 1),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>', 'Economisiți Timp', 'Ne ocupăm de toate formalitățile, astfel încât să vă puteți concentra pe ce contează pentru dumneavoastră.', 2),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>', 'Suport Dedicat', 'Echipă dedicată disponibilă să vă răspundă la întrebări și să vă asiste pe tot parcursul procesului.', 3),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', 'Prețuri Transparente', 'Fără costuri ascunse. Primiți oferta detaliată înainte de a începe colaborarea.', 4),
('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', 'Experiență Dovedită', 'Peste 2500 de dosare finalizate cu succes și sute de clienți mulțumiți din întreaga lume.', 5);

-- Insert Testimonials Section
INSERT INTO testimonials_section (section_label, title, description) VALUES
('Testimoniale', 'Ce spun clienții noștri', 'Mii de clienți mulțumiți din Moldova și diaspora');

-- Insert Testimonials
INSERT INTO testimonials (client_name, client_location, client_photo, testimonial_text, rating, sort_order) VALUES
('Ion Munteanu', 'Chișinău, Moldova', 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'Mulțumesc echipei ActeRomânia pentru profesionalism și dedicare. Am obținut cetățenia română în doar 8 luni, mult mai repede decât așteptam. Recomand cu încredere!', 5, 1),
('Maria Popescu', 'Bălți, Moldova', 'https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'După ani de încercări eșuate pe cont propriu, am apelat la ActeRomânia. În câteva luni am avut dosarul complet și acum am și pașaportul românesc. Servicii excelente!', 5, 2),
('Andrei Rusu', 'Roma, Italia', 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'Locuiesc în Italia de 15 ani și aveam nevoie de cetățenie română urgent. Echipa m-a ajutat cu toate documentele la distanță. Comunicare excelentă și rezultate rapide!', 5, 3);

-- Insert FAQ Section
INSERT INTO faq_section (section_label, title) VALUES
('Întrebări Frecvente', 'Răspunsuri la întrebările dumneavoastră');

-- Insert FAQ
INSERT INTO faq (question, answer, sort_order) VALUES
('Cine poate obține cetățenia română?', 'Cetățenia română poate fi obținută de persoanele care au avut strămoși (părinți, bunici, străbunici) cetățeni români, indiferent de țara în care locuiesc în prezent. Procesul se numește redobândirea cetățeniei române și este reglementat de Legea nr. 21/1991. De asemenea, cetățenia poate fi obținută prin naturalizare după o perioadă de reședință în România.', 1),
('Cât durează procesul de obținere a cetățeniei?', 'Durata procesului variază în funcție de complexitatea dosarului și de fluxul de lucru al Autorității Naționale pentru Cetățenie. În medie, procesul durează între 6 și 12 luni de la depunerea dosarului complet. Cu asistența noastră, ne asigurăm că dosarul este perfect întocmit pentru a evita întârzierile.', 2),
('Ce documente sunt necesare pentru cetățenie?', 'Documentele necesare includ: certificatele de stare civilă ale dumneavoastră și ale strămoșilor (naștere, căsătorie, deces), acte de identitate, dovada legăturii cu strămoșul cetățean român, cazier judiciar și alte documente specifice situației personale. La consultație, analizăm cazul dumneavoastră și vă oferim lista completă personalizată.', 3),
('Trebuie să vorbesc limba română?', 'Da, pentru depunerea jurământului de credință este necesară cunoașterea bazelor limbii române. Jurământul trebuie rostit în limba română. Oferim îndrumare cu privire la acest aspect și vă putem recomanda resurse pentru pregătire, astfel încât să vă simțiți confortabil la ceremonie.', 4),
('Pot obține pașaport fără să am domiciliu în România?', 'Da, pașaportul românesc poate fi obținut chiar dacă nu aveți domiciliul stabilit în România. Puteți solicita pașaportul CRDS (pentru cetățenii români cu domiciliul în străinătate) care are aceeași valabilitate și aceleași drepturi ca pașaportul obișnuit.', 5),
('Care sunt costurile serviciilor dumneavoastră?', 'Costurile depind de serviciile solicitate și de complexitatea dosarului. Oferim consultație inițială gratuită în care analizăm situația dumneavoastră și vă prezentăm o ofertă transparentă, fără costuri ascunse. Plata poate fi făcută în rate, iar taxele de stat sunt comunicate separat.', 6);

-- Insert Contact Section
INSERT INTO contact (section_label, title, description, phone, phone_icon, whatsapp, whatsapp_icon, email, email_icon, address, address_icon, map_embed, form_title) VALUES
('Contact', 
 'Suntem aici să vă ajutăm', 
 'Programați o consultație gratuită sau contactați-ne pentru orice întrebare. Echipa noastră vă răspunde în cel mai scurt timp.',
 '+40 721 234 567',
 '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
 '+40721234567',
 '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
 'contact@acteromania.ro',
 '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
 'Ismail 33, Euro Credit Bank, Et. 6, Cab. 612, Chișinău',
 '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
 '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2848.8444388838895!2d26.097216776514887!3d44.43635987107542!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b1ff427bee28c1%3A0x4c1b1b1b1b1b1b1b!2sCalea%20Victoriei%2C%20Bucharest%2C%20Romania!5e0!3m2!1sen!2sus!4v1707100000000!5m2!1sen!2sus" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
 'Programați o consultație gratuită');
