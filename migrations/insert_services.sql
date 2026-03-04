-- Insert all services (most requested first)
SET NAMES utf8mb4;

INSERT INTO services (title, description, features, sort_order, enabled) VALUES
('Cetățenie română', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', '["Verificare eligibilitate gratuită", "Strângerea și pregătirea actelor", "Traduceri autorizate", "Depunere dosar ANC", "Monitorizare status dosar"]', 1, 1),

('Pașaport românesc', 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură pentru pașaport simplu sau CRDS.', '["Programare la instituții", "Pregătire documente necesare", "Asistență la depunere", "Livrare la domiciliu"]', 2, 1),

('Buletin românesc', 'Servicii complete pentru obținerea cărții de identitate românești. Vă asistăm cu stabilirea domiciliului, pregătirea actelor și programarea la SPCLEP.', '["Programare online", "Verificare acte", "Asistență depunere", "Livrare rapidă"]', 3, 1),

('Certificate de naștere și căsătorie', 'Obținerea certificatelor românești de stare civilă. Transcriere acte străine, eliberare duplicate, reconstituire și apostilare documente.', '["Transcriere certificate străine", "Eliberare duplicate", "Reconstituire acte", "Apostilare documente"]', 4, 1),

('Cetățenia română prin judecată', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', '["Analiză gratuită dosar", "Reprezentare în instanță", "Avocat specializat", "Monitorizare proces"]', 5, 1),

('Programare jurământ', 'Programare la depunerea jurământului de credință - ultimul pas pentru cetățenie. Programări la ANC București sau la consulatele din străinătate.', '["Programare rapidă", "Pregătire ceremonie", "Asistență deplasare", "Informare completă"]', 6, 1),

('Traduceri acte', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', '["Traducători autorizați", "Toate limbile europene", "Legalizare notarială", "Termen rapid"]', 7, 1),

('Apostila pe acte', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', '["Apostilă Haga", "Supralegalizare", "Termen express", "Toate tipurile de acte"]', 8, 1),

('Cazier judiciar', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', '["Cazier pentru persoane fizice", "Cazier pentru persoane juridice", "Termen rapid", "Livrare la domiciliu"]', 9, 1),

('Servicii diasporă', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', '["Consultanță online", "Reprezentare în România", "Servicii consulare", "Suport permanent"]', 10, 1),

('Acte stare civilă', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', '["Transcriere acte străine", "Rectificare date", "Mențiuni marginale", "Duplicate"]', 11, 1),

('Livrare acte românești', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', '["Livrare internațională", "Tracking în timp real", "Ambalare sigură", "Asigurare colet"]', 12, 1),

('Permis de conducere', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', '["Preschimbare permis străin", "Duplicat permis", "Programare DRPCIV", "Consultanță completă"]', 13, 1),

('Alocație pentru copil', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', '["Verificare eligibilitate", "Pregătire dosar", "Depunere cerere", "Monitorizare plăți"]', 14, 1),

('Atestate profesionale - CIP', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', '["Echivalare diplome", "Recunoaștere calificări", "Atestate profesionale", "Consultanță specializată"]', 15, 1),

('Transport România', 'Transport persoane și colete între Moldova, România și diasporă. Curse regulate săptămânale la prețuri accesibile.', '["Curse regulate", "Transport persoane", "Colete și documente", "Prețuri accesibile"]', 16, 1),

('Număr dosar ANC', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', '["Verificare număr dosar", "Status procesare", "Estimare termen", "Notificări actualizări"]', 17, 1);
