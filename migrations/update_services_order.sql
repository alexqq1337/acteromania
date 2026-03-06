-- Migration: Reorganizare servicii - 8 servicii principale
-- Cele 8 servicii principale trebuie să fie primele vizibile:
-- 1. Consultație gratuită (NOU)
-- 2. Pregătirea dosarului și depunerea (NOU)
-- 3. Urgentare dosar (NOU)
-- 4. Publicare ordin și Depunerea jurământului de credință (actualizare id 6)
-- 5. Transcrierea actelor de stare civilă (actualizare id 4)
-- 6. Buletin (id 3)
-- 7. Pașaport (id 2)
-- 8. Transport (id 16)

-- Pasul 1: Schimbăm toate sort_order-urile să fie mai mari pentru a face loc
UPDATE services SET sort_order = sort_order + 100 WHERE sort_order > 0;

-- Pasul 2: Inserăm serviciile noi

-- Serviciul 1: Consultație gratuită
INSERT INTO services (title, description, short_description, full_description, image_url, icon_svg, features, sort_order, enabled)
VALUES (
    'Consultație gratuită',
    'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră și vă oferim sfaturi personalizate.',
    'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române.',
    'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră, verificăm eligibilitatea și vă oferim sfaturi personalizate pentru următorii pași. Consultația se poate face online sau la sediul nostru.',
    '',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
    '["Consultanță online sau la sediu", "Verificare eligibilitate gratuită", "Răspunsuri la toate întrebările", "Planificare personalizată"]',
    1,
    1
);

-- Serviciul 2: Pregătirea dosarului și depunerea
INSERT INTO services (title, description, short_description, full_description, image_url, icon_svg, features, sort_order, enabled)
VALUES (
    'Pregătirea dosarului și depunerea',
    'Vă ajutăm cu strângerea și pregătirea tuturor actelor necesare pentru dosar, precum și cu depunerea acestuia la ANC.',
    'Pregătirea completă a dosarului pentru cetățenie română și depunerea la ANC.',
    'Vă asistăm complet în pregătirea dosarului pentru obținerea cetățeniei române. Actele necesare includ: certificat de naștere, certificat de căsătorie (dacă este cazul), acte de identitate, documente care dovedesc legătura cu România (acte ale părinților/bunicilor), traduceri autorizate și apostile. Ne ocupăm de strângerea, verificarea și depunerea dosarului la ANC.',
    '',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
    '["Certificat de naștere și căsătorie", "Acte de identitate valabile", "Documente ale ascendenților români", "Traduceri autorizate", "Apostilare acte", "Depunere la ANC"]',
    2,
    1
);

-- Serviciul 3: Urgentare dosar
INSERT INTO services (title, description, short_description, full_description, image_url, icon_svg, features, sort_order, enabled)
VALUES (
    'Urgentare dosar',
    'Servicii de urgentare a dosarului de cetățenie pentru reducerea timpului de așteptare și procesare mai rapidă.',
    'Urgentarea dosarului de cetățenie pentru procesare mai rapidă la ANC.',
    'Oferim servicii de urgentare a dosarului de cetățenie română pentru cei care au nevoie de procesare mai rapidă. Monitorizăm statusul dosarului, intervenim pentru deblocarea cazurilor întârziate și asigurăm comunicarea eficientă cu autoritățile competente.',
    '',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
    '["Monitorizare activă dosar", "Intervenție la ANC", "Reducere timp așteptare", "Comunicare cu autoritățile", "Rapoarte de status"]',
    3,
    1
);

-- Pasul 3: Actualizăm serviciul id 6 (Programare jurământ) -> Publicare ordin și Depunerea jurământului de credință
UPDATE services SET 
    title = 'Publicare ordin și Depunerea jurământului',
    description = 'Vă asistăm de la publicarea ordinului de acordare a cetățeniei până la depunerea jurământului de credință față de România.',
    short_description = 'Asistență completă de la publicarea ordinului până la depunerea jurământului de credință.',
    full_description = 'Vă asistăm în întregul proces final de obținere a cetățeniei: monitorizarea publicării ordinului în Monitorul Oficial, programarea pentru depunerea jurământului de credință la ANC București sau la consulatele din străinătate, pregătirea pentru ceremonie și obținerea certificatului de cetățenie.',
    features = '["Monitorizare publicare ordin", "Programare jurământ ANC/Consulate", "Pregătire ceremonie", "Asistență la depunere jurământ", "Obținere certificat cetățenie"]',
    sort_order = 4
WHERE id = 6;

-- Pasul 4: Actualizăm serviciul id 4 (Certificate) -> Transcrierea actelor de stare civilă
UPDATE services SET 
    title = 'Transcrierea actelor de stare civilă',
    description = 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă în registrele românești.',
    short_description = 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă.',
    full_description = 'Servicii complete de transcriere a actelor de stare civilă (certificat de naștere, certificat de căsătorie, certificat de deces) din registrele străine în registrele românești. Necesar pentru obținerea actelor de identitate românești după cetățenie.',
    features = '["Transcriere certificat naștere", "Transcriere certificat căsătorie", "Transcriere certificat deces", "Eliberare duplicate", "Rectificare date"]',
    sort_order = 5
WHERE id = 4;

-- Pasul 5: Actualizăm sort_order pentru Buletin (id 3)
UPDATE services SET 
    title = 'Buletin',
    description = 'Servicii complete pentru obținerea buletinului românesc (carte de identitate). Asistență cu stabilirea domiciliului și programarea la SPCLEP.',
    short_description = 'Servicii complete pentru obținerea buletinului românesc (carte de identitate).',
    sort_order = 6
WHERE id = 3;

-- Pasul 6: Actualizăm sort_order pentru Pașaport (id 2)
UPDATE services SET 
    title = 'Pașaport',
    description = 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură.',
    short_description = 'Obținerea pașaportului românesc - programări, documente și procedură completă.',
    sort_order = 7
WHERE id = 2;

-- Pasul 7: Actualizăm sort_order pentru Transport (id 16)
UPDATE services SET 
    title = 'Transport',
    description = 'Transport persoane și colete între Moldova și România. Curse regulate săptămânale la prețuri accesibile.',
    short_description = 'Transport persoane și colete între Moldova și România.',
    sort_order = 8
WHERE id = 16;

-- Pasul 8: Reordonăm celelalte servicii (încep de la 9)
-- Cetățenie română (id 1) -> 9
UPDATE services SET sort_order = 9 WHERE id = 1;

-- Cetățenia română prin judecată (id 5) -> 10
UPDATE services SET sort_order = 10 WHERE id = 5;

-- Traduceri acte (id 7) -> 11
UPDATE services SET sort_order = 11 WHERE id = 7;

-- Apostila pe acte (id 8) -> 12
UPDATE services SET sort_order = 12 WHERE id = 8;

-- Cazier judiciar (id 9) -> 13
UPDATE services SET sort_order = 13 WHERE id = 9;

-- Servicii diasporă (id 10) -> 14
UPDATE services SET sort_order = 14 WHERE id = 10;

-- Acte stare civilă (id 11) -> 15
UPDATE services SET sort_order = 15 WHERE id = 11;

-- Livrare acte românești (id 12) -> 16
UPDATE services SET sort_order = 16 WHERE id = 12;

-- Permis de conducere (id 13) -> 17
UPDATE services SET sort_order = 17 WHERE id = 13;

-- Alocație pentru copil (id 14) -> 18
UPDATE services SET sort_order = 18 WHERE id = 14;

-- Atestate profesionale - CIP (id 15) -> 19
UPDATE services SET sort_order = 19 WHERE id = 15;

-- Număr dosar ANC (id 17) -> 20
UPDATE services SET sort_order = 20 WHERE id = 17;

-- Verificare finală - afișăm serviciile în ordinea nouă
SELECT id, title, sort_order FROM services WHERE enabled = 1 ORDER BY sort_order ASC;
