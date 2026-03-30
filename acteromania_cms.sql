-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 06 2026 г., 18:13
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `acteromania_cms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `counters_enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `about`
--

INSERT INTO `about` (`id`, `section_label`, `title`, `content`, `image_url`, `counters_enabled`, `created_at`, `updated_at`) VALUES
(1, 'Despre Noi', 'Experiență și profesionalism în servicii juridice', '<p>Cu peste 10 ani de experiență în domeniul asistenței juridice pentru obținerea documentelor românești, echipa noastră a ajutat mii de clienți din Moldova și diaspora să își îndeplinească visul de a obține cetățenia română.</p><p>Ne mândrim cu o abordare personalizată, transparentă și profesională. Fiecare dosar este tratat cu maximă atenție, iar clienții noștri beneficiază de suport complet pe tot parcursul procesului.</p>', 'uploads/about/69a6b82136c2a_1772533793.png', 1, '2026-03-02 22:50:28', '2026-03-03 10:29:53');

-- --------------------------------------------------------

--
-- Структура таблицы `about_stats`
--

CREATE TABLE `about_stats` (
  `id` int(11) NOT NULL,
  `icon_svg` text DEFAULT NULL,
  `number_value` int(11) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `about_stats`
--

INSERT INTO `about_stats` (`id`, `icon_svg`, `number_value`, `suffix`, `label`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><path d=\"M12 6v6l4 2\"/></svg>', 10, '+', 'Ani de experiență', 1, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(2, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>', 2500, '+', 'Clienți ajutați', 2, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(3, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z\"/><polyline points=\"14 2 14 8 20 8\"/><line x1=\"16\" y1=\"13\" x2=\"8\" y2=\"13\"/><line x1=\"16\" y1=\"17\" x2=\"8\" y2=\"17\"/></svg>', 2500, '+', 'Dosare finalizate', 3, 1, '2026-03-02 22:50:28', '2026-03-02 22:50:28'),
(4, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M22 11.08V12a10 10 0 1 1-5.93-9.14\"/><polyline points=\"22 4 12 14.01 9 11.01\"/></svg>', 98, '%', 'Rată de succes', 4, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `about_stats_translations`
--

CREATE TABLE `about_stats_translations` (
  `id` int(11) NOT NULL,
  `stat_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `label` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `about_stats_translations`
--

INSERT INTO `about_stats_translations` (`id`, `stat_id`, `language`, `label`, `suffix`, `created_at`, `updated_at`) VALUES
(33, 1, 'en', 'Years of Experience', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(34, 1, 'ru', 'Лет опыта', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(35, 2, 'en', 'Clients Helped', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(36, 2, 'ru', 'Клиентов помогли', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(37, 3, 'en', 'Completed Cases', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(38, 3, 'ru', 'Завершённых дел', '+', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(39, 4, 'en', 'Success Rate', '%', '2026-03-03 12:32:14', '2026-03-03 12:32:14'),
(40, 4, 'ru', 'Рейтинг успеха', '%', '2026-03-03 12:32:14', '2026-03-03 12:32:14');

-- --------------------------------------------------------

--
-- Структура таблицы `about_translations`
--

CREATE TABLE `about_translations` (
  `id` int(11) NOT NULL,
  `about_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `about_translations`
--

INSERT INTO `about_translations` (`id`, `about_id`, `language`, `section_label`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Despre Noi', 'Experiență și profesionalism în servicii juridice', '<p>Cu peste 10 ani de experiență în domeniul asistenței juridice pentru obținerea documentelor românești, echipa noastră a ajutat mii de clienți din Moldova și diaspora să își îndeplinească visul de a obține cetățenia română.</p><p>Ne mândrim cu o abordare personalizată, transparentă și profesională. Fiecare dosar este tratat cu maximă atenție, iar clienții noștri beneficiază de suport complet pe tot parcursul procesului.</p>', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'About Us', 'Experience and Professionalism in Legal Services', '<p>With over 10 years of experience in legal assistance for obtaining Romanian documents, our team has helped thousands of clients from Moldova and the diaspora fulfill their dream of obtaining Romanian citizenship.</p><p>We take pride in a personalized, transparent, and professional approach. Each case is treated with utmost attention, and our clients receive complete support throughout the entire process.</p>', '2026-03-03 11:55:29', '2026-03-03 12:30:22'),
(4, 1, 'ru', 'О нас', 'Опыт и профессионализм в юридических услугах', '<p>С более чем 10-летним опытом в области юридической помощи для получения румынских документов, наша команда помогла тысячам клиентов из Молдовы и диаспоры осуществить свою мечту о получении румынского гражданства.</p><p>Мы гордимся индивидуальным, прозрачным и профессиональным подходом. Каждое дело рассматривается с максимальным вниманием, а наши клиенты получают полную поддержку на протяжении всего процесса.</p>', '2026-03-03 12:07:02', '2026-03-03 12:30:22');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@acteromania.ro', '2026-03-06 16:34:58', '2026-03-02 22:50:28', '2026-03-06 16:34:58');

-- --------------------------------------------------------

--
-- Структура таблицы `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone_icon` text DEFAULT NULL,
  `phone_enabled` tinyint(1) DEFAULT 1,
  `whatsapp` varchar(50) DEFAULT NULL,
  `whatsapp_icon` text DEFAULT NULL,
  `whatsapp_enabled` tinyint(1) DEFAULT 1,
  `email` varchar(255) DEFAULT NULL,
  `email_icon` text DEFAULT NULL,
  `email_enabled` tinyint(1) DEFAULT 1,
  `address` text DEFAULT NULL,
  `address_icon` text DEFAULT NULL,
  `address_enabled` tinyint(1) DEFAULT 1,
  `map_embed` text DEFAULT NULL,
  `map_enabled` tinyint(1) DEFAULT 1,
  `form_title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contact`
--

INSERT INTO `contact` (`id`, `section_label`, `title`, `description`, `phone`, `phone_icon`, `phone_enabled`, `whatsapp`, `whatsapp_icon`, `whatsapp_enabled`, `email`, `email_icon`, `email_enabled`, `address`, `address_icon`, `address_enabled`, `map_embed`, `map_enabled`, `form_title`, `created_at`, `updated_at`) VALUES
(1, 'Contact', 'Suntem aici să vă ajutăm', 'Programați o consultație gratuită sau contactați-ne pentru orice întrebare. Echipa noastră vă răspunde în cel mai scurt timp.', '+373 78625055', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z\"/></svg>', 1, '+373 78625055', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z\"/></svg>', 1, 'contact@acteromania.ro', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z\"/><polyline points=\"22,6 12,13 2,6\"/></svg>', 1, 'Ismail 33, Euro Credit Bank, Et. 6, Cab. 612, Chișinău', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z\"/><circle cx=\"12\" cy=\"10\" r=\"3\"/></svg>', 1, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2719.8!2d28.8277!3d47.0245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zSXNtYWlsIDMzLCBDaGnImWluxIN1!5e0!3m2!1sen!2smd\" width=\"100%\" height=\"300\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 1, 'Programați o consultație gratuită', '2026-03-02 22:50:28', '2026-03-03 10:07:48');

-- --------------------------------------------------------

--
-- Структура таблицы `contacts_history`
--

CREATE TABLE `contacts_history` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `service` varchar(255) DEFAULT NULL,
  `source` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contacts_history`
--

INSERT INTO `contacts_history` (`id`, `name`, `phone`, `service`, `source`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'Ivasenco Alex', '+373 68705070', 'pașaport românesc', 'contact_form', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 23:30:51');

-- --------------------------------------------------------

--
-- Структура таблицы `contact_translations`
--

CREATE TABLE `contact_translations` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `form_title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contact_translations`
--

INSERT INTO `contact_translations` (`id`, `contact_id`, `language`, `section_label`, `title`, `description`, `form_title`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Contact', 'Suntem aici să vă ajutăm', 'Programați o consultație gratuită sau contactați-ne pentru orice întrebare. Echipa noastră vă răspunde în cel mai scurt timp.', 'Programați o consultație gratuită', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'Contact', 'Contact Us', 'We are available for any questions. Contact us via phone, email, or the form below.', 'Send Us a Message', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Контакты', 'Свяжитесь с нами', 'Мы готовы ответить на любые вопросы. Свяжитесь с нами по телефону, электронной почте или через форму ниже.', 'Напишите нам', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'Cine poate obține cetățenia română?', 'Cetățenia română poate fi obținută de persoanele care au avut strămoși (părinți, bunici, străbunici) cetățeni români, indiferent de țara în care locuiesc în prezent. Procesul se numește redobândirea cetățeniei române și este reglementat de Legea nr. 21/1991. De asemenea, cetățenia poate fi obținută prin naturalizare după o perioadă de reședință în România.', 1, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(2, 'Cât durează procesul de obținere a cetățeniei?', 'Durata procesului variază în funcție de complexitatea dosarului și de fluxul de lucru al Autorității Naționale pentru Cetățenie. În medie, procesul durează între 6 și 12 luni de la depunerea dosarului complet. Cu asistența noastră, ne asigurăm că dosarul este perfect întocmit pentru a evita întârzierile.', 2, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(3, 'Ce documente sunt necesare pentru cetățenie?', 'Documentele necesare includ: certificatele de stare civilă ale dumneavoastră și ale strămoșilor (naștere, căsătorie, deces), acte de identitate, dovada legăturii cu strămoșul cetățean român, cazier judiciar și alte documente specifice situației personale. La consultație, analizăm cazul dumneavoastră și vă oferim lista completă personalizată.', 3, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(4, 'Trebuie să vorbesc limba română?', 'Da, pentru depunerea jurământului de credință este necesară cunoașterea bazelor limbii române. Jurământul trebuie rostit în limba română. Oferim îndrumare cu privire la acest aspect și vă putem recomanda resurse pentru pregătire, astfel încât să vă simțiți confortabil la ceremonie.', 4, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(5, 'Pot obține pașaport fără să am domiciliu în România?', 'Da, pașaportul românesc poate fi obținut chiar dacă nu aveți domiciliul stabilit în România. Puteți solicita pașaportul CRDS (pentru cetățenii români cu domiciliul în străinătate) care are aceeași valabilitate și aceleași drepturi ca pașaportul obișnuit.', 5, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(6, 'Care sunt costurile serviciilor dumneavoastră?', 'Costurile depind de serviciile solicitate și de complexitatea dosarului. Oferim consultație inițială gratuită în care analizăm situația dumneavoastră și vă prezentăm o ofertă transparentă, fără costuri ascunse. Plata poate fi făcută în rate, iar taxele de stat sunt comunicate separat.', 6, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `faq_section`
--

CREATE TABLE `faq_section` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `faq_section`
--

INSERT INTO `faq_section` (`id`, `section_label`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Întrebări Frecvente', 'Răspunsuri la întrebările dumneavoastră', '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `faq_section_translations`
--

CREATE TABLE `faq_section_translations` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `faq_section_translations`
--

INSERT INTO `faq_section_translations` (`id`, `section_id`, `language`, `section_label`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Întrebări Frecvente', 'Răspunsuri la întrebările dumneavoastră', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'Frequently Asked Questions', 'FAQ', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Часто задаваемые вопросы', 'FAQ', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `faq_translations`
--

CREATE TABLE `faq_translations` (
  `id` int(11) NOT NULL,
  `faq_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `faq_translations`
--

INSERT INTO `faq_translations` (`id`, `faq_id`, `language`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Cine poate obține cetățenia română?', 'Cetățenia română poate fi obținută de persoanele care au avut strămoși (părinți, bunici, străbunici) cetățeni români, indiferent de țara în care locuiesc în prezent. Procesul se numește redobândirea cetățeniei române și este reglementat de Legea nr. 21/1991. De asemenea, cetățenia poate fi obținută prin naturalizare după o perioadă de reședință în România.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 2, 'ro', 'Cât durează procesul de obținere a cetățeniei?', 'Durata procesului variază în funcție de complexitatea dosarului și de fluxul de lucru al Autorității Naționale pentru Cetățenie. În medie, procesul durează între 6 și 12 luni de la depunerea dosarului complet. Cu asistența noastră, ne asigurăm că dosarul este perfect întocmit pentru a evita întârzierile.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(3, 3, 'ro', 'Ce documente sunt necesare pentru cetățenie?', 'Documentele necesare includ: certificatele de stare civilă ale dumneavoastră și ale strămoșilor (naștere, căsătorie, deces), acte de identitate, dovada legăturii cu strămoșul cetățean român, cazier judiciar și alte documente specifice situației personale. La consultație, analizăm cazul dumneavoastră și vă oferim lista completă personalizată.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(4, 4, 'ro', 'Trebuie să vorbesc limba română?', 'Da, pentru depunerea jurământului de credință este necesară cunoașterea bazelor limbii române. Jurământul trebuie rostit în limba română. Oferim îndrumare cu privire la acest aspect și vă putem recomanda resurse pentru pregătire, astfel încât să vă simțiți confortabil la ceremonie.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(5, 5, 'ro', 'Pot obține pașaport fără să am domiciliu în România?', 'Da, pașaportul românesc poate fi obținut chiar dacă nu aveți domiciliul stabilit în România. Puteți solicita pașaportul CRDS (pentru cetățenii români cu domiciliul în străinătate) care are aceeași valabilitate și aceleași drepturi ca pașaportul obișnuit.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(6, 6, 'ro', 'Care sunt costurile serviciilor dumneavoastră?', 'Costurile depind de serviciile solicitate și de complexitatea dosarului. Oferim consultație inițială gratuită în care analizăm situația dumneavoastră și vă prezentăm o ofertă transparentă, fără costuri ascunse. Plata poate fi făcută în rate, iar taxele de stat sunt comunicate separat.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(22, 1, 'en', 'Who can obtain Romanian citizenship?', 'Anyone who can prove Romanian ancestry up to the 3rd generation (great-grandparents) can apply for Romanian citizenship. This includes descendants of people who lived in historical Romanian territories.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(23, 2, 'en', 'How long does the citizenship process take?', 'The process typically takes 6-18 months, depending on the complexity of the case and the completeness of documents. We work to ensure the fastest possible processing.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(24, 3, 'en', 'What documents are required for citizenship?', 'Required documents include: birth certificates (yours and ancestors), marriage certificates, proof of Romanian ancestry, valid ID/passport, and various supplementary documents depending on your case.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(25, 4, 'en', 'Do I need to speak Romanian?', 'Basic knowledge of Romanian language and culture is required for the oath ceremony. We provide guidance and resources to help you prepare.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(26, 5, 'en', 'Can I get a passport without residency in Romania?', 'Yes, you can obtain a Romanian passport without establishing residency in Romania. The passport is issued after receiving citizenship.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(27, 6, 'en', 'What are the costs of your services?', 'Costs vary depending on the services requested. Contact us for a free consultation and personalized quote. We offer transparent pricing with no hidden fees.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(34, 1, 'ru', 'Кто может получить румынское гражданство?', 'Любой человек, который может доказать румынское происхождение до 3-го поколения (прабабушки и прадедушки), может подать заявление на румынское гражданство. Это включает потомков людей, живших на исторических румынских территориях.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(35, 2, 'ru', 'Сколько времени занимает процесс получения гражданства?', 'Процесс обычно занимает 6-18 месяцев, в зависимости от сложности дела и полноты документов. Мы работаем над обеспечением максимально быстрого оформления.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(36, 3, 'ru', 'Какие документы требуются для гражданства?', 'Необходимые документы включают: свидетельства о рождении (ваши и предков), свидетельства о браке, доказательство румынского происхождения, действующее удостоверение личности/паспорт.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(37, 4, 'ru', 'Нужно ли мне говорить по-румынски?', 'Для церемонии присяги требуется базовое знание румынского языка и культуры. Мы предоставляем рекомендации и ресурсы для подготовки.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(38, 5, 'ru', 'Могу ли я получить паспорт без проживания в Румынии?', 'Да, вы можете получить румынский паспорт без установления постоянного места жительства в Румынии. Паспорт выдается после получения гражданства.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(39, 6, 'ru', 'Сколько стоят ваши услуги?', 'Стоимость зависит от запрашиваемых услуг. Свяжитесь с нами для бесплатной консультации и персонального предложения. Мы предлагаем прозрачное ценообразование без скрытых платежей.', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `hero`
--

CREATE TABLE `hero` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `video_url` text DEFAULT NULL,
  `video_type` enum('upload','youtube','vimeo') DEFAULT 'upload',
  `media_type` enum('image','video') DEFAULT 'image',
  `trust_bar_enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `hero`
--

INSERT INTO `hero` (`id`, `title`, `subtitle`, `cta_text`, `cta_link`, `image_url`, `video_url`, `video_type`, `media_type`, `trust_bar_enabled`, `created_at`, `updated_at`) VALUES
(1, 'Cetățenie română - Serviciu complet', 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.', 'Consultație gratuită', '#contact', 'https://images.pexels.com/photos/5668858/pexels-photo-5668858.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop', 'uploads/hero/69a6ade081b1e_1772531168.mp4', 'upload', 'video', 1, '2026-03-02 22:50:28', '2026-03-03 09:46:08');

-- --------------------------------------------------------

--
-- Структура таблицы `hero_translations`
--

CREATE TABLE `hero_translations` (
  `id` int(11) NOT NULL,
  `hero_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `hero_translations`
--

INSERT INTO `hero_translations` (`id`, `hero_id`, `language`, `title`, `subtitle`, `cta_text`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Cetățenie română - Serviciu complet', 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.', 'Consultație gratuită', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'Romanian Citizenship - Complete Service', 'Fast and professional legal assistance for obtaining Romanian citizenship, passport, and ID card. Over 15,000 successful cases.', 'Free Consultation', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Румынское гражданство - Полный сервис', 'Быстрая и профессиональная юридическая помощь в получении румынского гражданства, паспорта и удостоверения личности. Более 15 000 успешных дел.', 'Бесплатная консультация', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `hero_trust_items`
--

CREATE TABLE `hero_trust_items` (
  `id` int(11) NOT NULL,
  `icon_svg` text DEFAULT NULL,
  `text` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `hero_trust_items`
--

INSERT INTO `hero_trust_items` (`id`, `icon_svg`, `text`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>', '15+ ani de experiență', 1, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(2, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\"/><polyline points=\"9 12 11 14 15 10\"/></svg>', '15.000+ cazuri reușite', 2, 1, '2026-03-02 22:50:28', '2026-03-02 22:50:28'),
(3, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><rect x=\"3\" y=\"11\" width=\"18\" height=\"11\" rx=\"2\" ry=\"2\"/><path d=\"M7 11V7a5 5 0 0 1 10 0v4\"/></svg>', 'Termene scurte de procesare', 3, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `hero_trust_items_translations`
--

CREATE TABLE `hero_trust_items_translations` (
  `id` int(11) NOT NULL,
  `trust_item_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `text` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `hero_trust_items_translations`
--

INSERT INTO `hero_trust_items_translations` (`id`, `trust_item_id`, `language`, `text`, `created_at`, `updated_at`) VALUES
(7, 1, 'en', '15+ Years Experience', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(8, 2, 'en', '15,000+ Successful Cases', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(9, 3, 'en', 'Fast Processing Time', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(13, 1, 'ru', '15+ лет опыта', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(14, 2, 'ru', '15 000+ успешных дел', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(15, 3, 'ru', 'Короткие сроки обработки', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `file_url` text NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `folder` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pending_reviews`
--

CREATE TABLE `pending_reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `message` text NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `process_section`
--

CREATE TABLE `process_section` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `process_section`
--

INSERT INTO `process_section` (`id`, `section_label`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cum Funcționează', 'Procesul nostru simplu și transparent', 'Patru pași simpli către obținerea documentelor dumneavoastră românești', '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `process_section_translations`
--

CREATE TABLE `process_section_translations` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `process_section_translations`
--

INSERT INTO `process_section_translations` (`id`, `section_id`, `language`, `section_label`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Cum Funcționează', 'Procesul nostru simplu și transparent', 'Patru pași simpli către obținerea documentelor dumneavoastră românești', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'How It Works', 'Simple Process in 4 Steps', 'We simplify the procedure for obtaining Romanian documents.', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Как это работает', 'Простой процесс в 4 шага', 'Мы упрощаем процедуру получения румынских документов.', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `process_steps`
--

CREATE TABLE `process_steps` (
  `id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `process_steps`
--

INSERT INTO `process_steps` (`id`, `step_number`, `title`, `description`, `image_url`, `features`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 1, 'Consultație Inițială', 'Programați o consultație gratuită pentru a discuta situația dumneavoastră și a stabili eligibilitatea pentru serviciile noastre.', 'uploads/process/69a6b83a4ce01_1772533818.jpg', '[\"Analiză gratuită a cazului\",\"Verificare eligibilitate\",\"Plan personalizat\"]', 1, 1, '2026-03-02 22:50:28', '2026-03-03 10:30:18'),
(2, 2, 'Pregătirea Dosarului', 'Echipa noastră vă ghidează în strângerea documentelor necesare și pregătește dosarul complet pentru depunere.', 'uploads/process/69a6b84f6ac35_1772533839.jpg', '[\"Listă documente necesare\",\"Verificare acte\",\"Traduceri și apostile\"]', 2, 1, '2026-03-02 22:50:28', '2026-03-03 10:30:39'),
(3, 3, 'Depunere și Monitorizare', 'Depunem dosarul la autoritățile competente și monitorizăm constant stadiul acestuia, informându-vă despre progres.', 'uploads/process/69a6b85f740e3_1772533855.jpg', '[\"Depunere oficială\",\"Monitorizare continuă\",\"Informări regulate\"]', 3, 1, '2026-03-02 22:50:28', '2026-03-03 10:30:55'),
(4, 4, 'Finalizare cu Succes', 'Vă asistăm în obținerea documentelor finale și vă îndrumăm în următorii pași pentru exercitarea drepturilor dumneavoastră.', 'uploads/process/69a6b86ebae51_1772533870.jpg', '[\"Primire documente\",\"Îndrumare pași următori\",\"Suport post-finalizare\"]', 4, 1, '2026-03-02 22:50:28', '2026-03-03 10:31:10');

-- --------------------------------------------------------

--
-- Структура таблицы `process_steps_translations`
--

CREATE TABLE `process_steps_translations` (
  `id` int(11) NOT NULL,
  `step_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `process_steps_translations`
--

INSERT INTO `process_steps_translations` (`id`, `step_id`, `language`, `title`, `description`, `features`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Consultație Inițială', 'Programați o consultație gratuită pentru a discuta situația dumneavoastră și a stabili eligibilitatea pentru serviciile noastre.', '[\"Analiză gratuită a cazului\",\"Verificare eligibilitate\",\"Plan personalizat\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 2, 'ro', 'Pregătirea Dosarului', 'Echipa noastră vă ghidează în strângerea documentelor necesare și pregătește dosarul complet pentru depunere.', '[\"Listă documente necesare\",\"Verificare acte\",\"Traduceri și apostile\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(3, 3, 'ro', 'Depunere și Monitorizare', 'Depunem dosarul la autoritățile competente și monitorizăm constant stadiul acestuia, informându-vă despre progres.', '[\"Depunere oficială\",\"Monitorizare continuă\",\"Informări regulate\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(4, 4, 'ro', 'Finalizare cu Succes', 'Vă asistăm în obținerea documentelor finale și vă îndrumăm în următorii pași pentru exercitarea drepturilor dumneavoastră.', '[\"Primire documente\",\"Îndrumare pași următori\",\"Suport post-finalizare\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(22, 1, 'en', 'Free Consultation', 'We analyze your situation and determine the best route to obtaining Romanian citizenship and documents.', '[\"Free case analysis\",\"Eligibility check\",\"Personalized plan\"]', '2026-03-03 12:01:24', '2026-03-03 12:22:38'),
(23, 2, 'en', 'Document Preparation', 'We collect, translate, and certify all necessary documents according to legal requirements.', '[\"Required documents list\",\"Document verification\",\"Translations and apostille\"]', '2026-03-03 12:01:24', '2026-03-03 12:22:38'),
(24, 3, 'en', 'Submission & Processing', 'We submit your file to Romanian authorities and monitor the entire process.', '[\"Official submission\",\"Continuous monitoring\",\"Regular updates\"]', '2026-03-03 12:01:24', '2026-03-03 12:22:38'),
(25, 4, 'en', 'Receive Documents', 'You receive your Romanian citizenship, passport, and/or ID card.', '[\"Receive documents\",\"Guidance for next steps\",\"Post-completion support\"]', '2026-03-03 12:01:24', '2026-03-03 12:22:38'),
(36, 1, 'ru', 'Бесплатная консультация', 'Мы анализируем вашу ситуацию и определяем лучший путь к получению румынского гражданства и документов.', '[\"Бесплатный анализ дела\",\"Проверка права на получение\",\"Индивидуальный план\"]', '2026-03-03 12:07:02', '2026-03-03 12:22:38'),
(37, 2, 'ru', 'Подготовка документов', 'Мы собираем, переводим и заверяем все необходимые документы согласно требованиям.', '[\"Список необходимых документов\",\"Проверка документов\",\"Переводы и апостиль\"]', '2026-03-03 12:07:02', '2026-03-03 12:22:38'),
(38, 3, 'ru', 'Подача и обработка', 'Мы подаем ваше дело в румынские органы власти и контролируем весь процесс.', '[\"Официальная подача\",\"Постоянный мониторинг\",\"Регулярные обновления\"]', '2026-03-03 12:07:02', '2026-03-03 12:22:38'),
(39, 4, 'ru', 'Получение документов', 'Вы получаете румынское гражданство, паспорт и/или удостоверение личности.', '[\"Получение документов\",\"Руководство по дальнейшим шагам\",\"Поддержка после завершения\"]', '2026-03-03 12:07:02', '2026-03-03 12:22:38');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL COMMENT 'Numele utilizatorului',
  `email` varchar(120) NOT NULL COMMENT 'Adresa de email',
  `title` varchar(100) NOT NULL COMMENT 'Titlul recenziei',
  `message` text NOT NULL COMMENT 'Textul recenziei',
  `rating` tinyint(3) UNSIGNED NOT NULL COMMENT 'Rating 1-5 stele',
  `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=??n a??teptare, 1=aprobat, 2=respins',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP-ul utilizatorului',
  `user_agent` varchar(255) DEFAULT NULL COMMENT 'Browser-ul utilizatorului',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data trimiterii',
  `approved_at` timestamp NULL DEFAULT NULL COMMENT 'Data aprob??rii'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `email`, `title`, `message`, `rating`, `approved`, `ip_address`, `user_agent`, `created_at`, `approved_at`) VALUES
(1, 'vasile', 'vasile.popovici@example.com', 'lalallallal', 'weuitgheuiwglLEIUGTEWRUIKGF', 4, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 09:47:32', '2026-03-03 09:47:41'),
(2, 'vasile', '', 'l8ofityjgu', 'trdutsy6ce5ri7u76rytrs', 4, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 17:05:11', '2026-03-03 17:08:02');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews_section_translations`
--

CREATE TABLE `reviews_section_translations` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `full_description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `icon_svg` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `offers_transport` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `short_description`, `full_description`, `image_url`, `icon_svg`, `features`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'Cetățenie română', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', 'uploads/services/69a6b93c00a05_1772534076.png', '', '[\"Verificare eligibilitate gratuită\", \"Strângerea și pregătirea actelor\", \"Traduceri autorizate\", \"Depunere dosar ANC\", \"Monitorizare status dosar\"]', 9, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(2, 'Pașaport', 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură.', 'Obținerea pașaportului românesc - programări, documente și procedură completă.', 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură pentru pașaport simplu sau CRDS.', 'uploads/services/69ab0255dc350_1772814933.png', '', '[\"Programare la instituții\", \"Pregătire documente necesare\", \"Asistență la depunere\", \"Livrare la domiciliu\"]', 7, 1, '2026-03-03 10:01:37', '2026-03-06 16:43:03'),
(3, 'Buletin', 'Servicii complete pentru obținerea buletinului românesc (carte de identitate). Asistență cu stabilirea domiciliului și programarea la SPCLEP.', 'Servicii complete pentru obținerea buletinului românesc (carte de identitate).', 'Servicii complete pentru obținerea cărții de identitate românești. Vă asistăm cu stabilirea domiciliului, pregătirea actelor și programarea la SPCLEP.', 'uploads/services/69a6b960c897a_1772534112.png', '', '[\"Programare online\", \"Verificare acte\", \"Asistență depunere\", \"Livrare rapidă\"]', 6, 1, '2026-03-03 10:01:37', '2026-03-06 16:43:03'),
(4, 'Transcrierea actelor de stare civilă', 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă în registrele românești.', 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă.', 'Servicii complete de transcriere a actelor de stare civilă (certificat de naștere, certificat de căsătorie, certificat de deces) din registrele străine în registrele românești. Necesar pentru obținerea actelor de identitate românești după cetățenie.', 'uploads/services/69a6b96fb2a6b_1772534127.jpg', '', '[\"căsătorie / divorț / deces pe actul de naștere sau căsătorie\", \"Eliberare duplicate\", \"Rectificare date\"]', 5, 1, '2026-03-03 10:01:37', '2026-03-06 16:43:03'),
(5, 'Cetățenia română prin judecată', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', 'uploads/services/69a6b983b6ae8_1772534147.jpg', '', '[\"Analiză gratuită dosar\", \"Reprezentare în instanță\", \"Avocat specializat\", \"Monitorizare proces\"]', 10, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(6, 'Publicare ordin și Depunerea jurământului', 'Vă asistăm de la publicarea ordinului de acordare a cetățeniei până la depunerea jurământului de credință față de România.', 'Asistență completă de la publicarea ordinului până la depunerea jurământului de credință.', 'Vă asistăm în întregul proces final de obținere a cetățeniei: monitorizarea publicării ordinului în Monitorul Oficial, programarea pentru depunerea jurământului de credință la ANC București sau la consulatele din străinătate, pregătirea pentru ceremonie și obținerea certificatului de cetățenie.', 'uploads/services/69a6b996a309f_1772534166.jpg', '', '[\"Monitorizare publicare ordin\", \"Programare jurământ ANC/Consulate\", \"Pregătire ceremonie\", \"Asistență la depunere jurământ\", \"Obținere certificat cetățenie\"]', 4, 1, '2026-03-03 10:01:37', '2026-03-06 16:43:03'),
(7, 'Traduceri acte', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', 'uploads/services/69a6b9a5a2569_1772534181.jpg', '', '[\"Traducători autorizați\", \"Toate limbile europene\", \"Legalizare notarială\", \"Termen rapid\"]', 11, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(8, 'Apostila pe acte', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', 'uploads/services/69a6b9b4375e2_1772534196.png', '', '[\"Apostilă Haga\", \"Supralegalizare\", \"Termen express\", \"Toate tipurile de acte\"]', 12, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(9, 'Cazier judiciar', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', 'uploads/services/69a6b9c1451e1_1772534209.png', '', '[\"Cazier pentru persoane fizice\", \"Cazier pentru persoane juridice\", \"Termen rapid\", \"Livrare la domiciliu\"]', 13, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(10, 'Servicii diasporă', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', 'uploads/services/69a6b9db7e840_1772534235.png', '', '[\"Consultanță online\", \"Reprezentare în România\", \"Servicii consulare\", \"Suport permanent\"]', 14, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(11, 'Acte stare civilă', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', 'uploads/services/69a6b9f5bb99e_1772534261.jpg', '', '[\"Transcriere acte străine\", \"Rectificare date\", \"Mențiuni marginale\", \"Duplicate\"]', 15, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(12, 'Livrare acte românești', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', 'uploads/services/69a6ba047328c_1772534276.png', '', '[\"Livrare internațională\", \"Tracking în timp real\", \"Ambalare sigură\", \"Asigurare colet\"]', 16, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(13, 'Permis de conducere', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', 'uploads/services/69a6ba14cc7f7_1772534292.png', '', '[\"Preschimbare permis străin\", \"Duplicat permis\", \"Programare DRPCIV\", \"Consultanță completă\"]', 17, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(14, 'Alocație pentru copil', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', 'uploads/services/69a6ba1ee07ea_1772534302.jpg', '', '[\"Verificare eligibilitate\", \"Pregătire dosar\", \"Depunere cerere\", \"Monitorizare plăți\"]', 18, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(15, 'Atestate profesionale - CIP', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', 'uploads/services/69a6ba27ca8e4_1772534311.jpg', '', '[\"Echivalare diplome\", \"Recunoaștere calificări\", \"Atestate profesionale\", \"Consultanță specializată\"]', 19, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(16, 'Transport', 'Transport persoane și colete între Moldova și România. Curse regulate săptămânale la prețuri accesibile.', 'Transport persoane și colete între Moldova și România.', 'Transport persoane și colete între Moldova, România și diasporă. Curse regulate săptămânale la prețuri accesibile.', 'uploads/services/69a6ba31a7262_1772534321.jpg', '', '[\"Curse regulate\", \"Transport persoane\", \"Colete și documente\", \"Prețuri accesibile\"]', 8, 1, '2026-03-03 10:01:37', '2026-03-06 16:43:03'),
(17, 'Număr dosar ANC', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', 'uploads/services/69a6ba3d8c1c2_1772534333.jpg', '', '[\"Verificare număr dosar\", \"Status procesare\", \"Estimare termen\", \"Notificări actualizări\"]', 20, 1, '2026-03-03 10:01:37', '2026-03-06 16:35:44'),
(18, 'Consultație gratuită', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră și vă oferim sfaturi personalizate.', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române.', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră, verificăm eligibilitatea și vă oferim sfaturi personalizate pentru următorii pași. Consultația se poate face online sau la sediul nostru.', 'uploads/services/69ab0aaff3c22_1772817071.png', '&lt;svg viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot;&gt;&lt;path d=&quot;M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z&quot;&gt;&lt;/path&gt;&lt;/svg&gt;', '[\"Consultanță online sau la sediu\", \"Verificare eligibilitate gratuită\", \"Răspunsuri la toate întrebările\", \"Planificare personalizată\"]', 1, 1, '2026-03-06 16:35:44', '2026-03-06 17:11:11'),
(19, 'Pregătirea dosarului și depunerea', 'Vă ajutăm cu strângerea și pregătirea tuturor actelor necesare pentru dosar, precum și cu depunerea acestuia la ANC.', 'Pregătirea completă a dosarului pentru cetățenie română și depunerea la ANC.', 'Vă asistăm complet în pregătirea dosarului pentru obținerea cetățeniei române. Actele necesare includ: certificat de naștere, certificat de căsătorie (dacă este cazul), acte de identitate, documente care dovedesc legătura cu România (acte ale părinților/bunicilor), traduceri autorizate și apostile. Ne ocupăm de strângerea, verificarea și depunerea dosarului la ANC.', 'uploads/services/69ab0ac0914b5_1772817088.png', '&lt;svg viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot;&gt;&lt;path d=&quot;M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z&quot;&gt;&lt;/path&gt;&lt;polyline points=&quot;14 2 14 8 20 8&quot;&gt;&lt;/polyline&gt;&lt;line x1=&quot;16&quot; y1=&quot;13&quot; x2=&quot;8&quot; y2=&quot;13&quot;&gt;&lt;/line&gt;&lt;line x1=&quot;16&quot; y1=&quot;17&quot; x2=&quot;8&quot; y2=&quot;17&quot;&gt;&lt;/line&gt;&lt;/svg&gt;', '[\"Certificat de naștere și căsătorie\", \"Acte de identitate valabile\", \"Documente ale ascendenților români\", \"Traduceri autorizate\", \"Apostilare acte\", \"Depunere la ANC\"]', 2, 1, '2026-03-06 16:35:44', '2026-03-06 17:11:28'),
(20, 'Urgentare dosar', 'Servicii de urgentare a dosarului de cetățenie pentru reducerea timpului de așteptare și procesare mai rapidă.', 'Urgentarea dosarului de cetățenie pentru procesare mai rapidă la ANC.', 'Oferim servicii de urgentare a dosarului de cetățenie română pentru cei care au nevoie de procesare mai rapidă. Monitorizăm statusul dosarului, intervenim pentru deblocarea cazurilor întârziate și asigurăm comunicarea eficientă cu autoritățile competente.', 'uploads/services/69ab0ac82da2f_1772817096.png', '&lt;svg viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot;&gt;&lt;circle cx=&quot;12&quot; cy=&quot;12&quot; r=&quot;10&quot;&gt;&lt;/circle&gt;&lt;polyline points=&quot;12 6 12 12 16 14&quot;&gt;&lt;/polyline&gt;&lt;/svg&gt;', '[\"Monitorizare activă dosar\", \"Intervenție la ANC\", \"Reducere timp așteptare\", \"Comunicare cu autoritățile\", \"Rapoarte de status\"]', 3, 1, '2026-03-06 16:35:44', '2026-03-06 17:11:36');

UPDATE `services` SET `offers_transport` = 1 WHERE `id` = 16;

-- --------------------------------------------------------

--
-- Структура таблицы `services_section`
--

CREATE TABLE `services_section` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services_section`
--

INSERT INTO `services_section` (`id`, `section_label`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Serviciile Noastre', 'Soluții complete pentru documentele dumneavoastră', 'Oferim asistență juridică profesională pentru toate tipurile de documente românești', '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `services_section_translations`
--

CREATE TABLE `services_section_translations` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services_section_translations`
--

INSERT INTO `services_section_translations` (`id`, `section_id`, `language`, `section_label`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Serviciile Noastre', 'Soluții complete pentru documentele dumneavoastră', 'Oferim asistență juridică profesională pentru toate tipurile de documente românești', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'Our Services', 'Complete Services for Romanian Documents', 'We offer a full range of services for obtaining Romanian citizenship and all related documents.', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Наши услуги', 'Полный спектр услуг для румынских документов', 'Мы предлагаем полный комплекс услуг по получению румынского гражданства и всех связанных документов.', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `services_translations`
--

CREATE TABLE `services_translations` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `full_description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services_translations`
--

INSERT INTO `services_translations` (`id`, `service_id`, `language`, `title`, `description`, `short_description`, `full_description`, `features`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Cetățenie română', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', 'Asistență completă pentru obținerea cetățeniei române prin origine sau redobândire. Vă ghidăm pas cu pas prin întregul proces, de la verificarea eligibilității până la obținerea certificatului de cetățenie.', '[\"Verificare eligibilitate gratuită\", \"Strângerea și pregătirea actelor\", \"Traduceri autorizate\", \"Depunere dosar ANC\", \"Monitorizare status dosar\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(5, 5, 'ro', 'Cetățenia română prin judecată', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', 'Recuperarea cetățeniei române pe cale judecătorească. Soluții pentru dosare respinse sau cazuri complexe, cu avocați specializați.', '[\"Analiză gratuită dosar\", \"Reprezentare în instanță\", \"Avocat specializat\", \"Monitorizare proces\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(7, 7, 'ro', 'Traduceri acte', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', 'Traduceri autorizate și legalizate pentru toate tipurile de documente. Colaborăm cu traducători autorizați de Ministerul Justiției, din toate limbile.', '[\"Traducători autorizați\", \"Toate limbile europene\", \"Legalizare notarială\", \"Termen rapid\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(8, 8, 'ro', 'Apostila pe acte', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', 'Servicii de apostilare documente pentru uz internațional. Apostilă Haga pentru recunoașterea actelor românești în străinătate.', '[\"Apostilă Haga\", \"Supralegalizare\", \"Termen express\", \"Toate tipurile de acte\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(9, 9, 'ro', 'Cazier judiciar', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', 'Obținerea cazierului judiciar românesc pentru angajare, emigrare sau alte proceduri. Disponibil pentru persoane fizice și juridice.', '[\"Cazier pentru persoane fizice\", \"Cazier pentru persoane juridice\", \"Termen rapid\", \"Livrare la domiciliu\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(10, 10, 'ro', 'Servicii diasporă', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', 'Pachete complete pentru românii din străinătate. Vă reprezentăm în România prin procură, fără să fie nevoie să vă deplasați personal.', '[\"Consultanță online\", \"Reprezentare în România\", \"Servicii consulare\", \"Suport permanent\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(11, 11, 'ro', 'Acte stare civilă', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', 'Toate serviciile pentru actele de stare civilă românești. Transcrieri, rectificări, mențiuni marginale și duplicate pentru naștere, căsătorie, deces.', '[\"Transcriere acte străine\", \"Rectificare date\", \"Mențiuni marginale\", \"Duplicate\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(12, 12, 'ro', 'Livrare acte românești', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', 'Livrare rapidă a documentelor oriunde în lume. Curierat internațional cu tracking, ambalare sigură și asigurare completă.', '[\"Livrare internațională\", \"Tracking în timp real\", \"Ambalare sigură\", \"Asigurare colet\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(13, 13, 'ro', 'Permis de conducere', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', 'Preschimbarea permisului de conducere străin în format românesc. Serviciu complet cu programare DRPCIV și pregătire documente.', '[\"Preschimbare permis străin\", \"Duplicat permis\", \"Programare DRPCIV\", \"Consultanță completă\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(14, 14, 'ro', 'Alocație pentru copil', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', 'Asistență pentru obținerea alocației de stat. Drept garantat pentru toți copiii cetățeni români, indiferent de țara de reședință.', '[\"Verificare eligibilitate\", \"Pregătire dosar\", \"Depunere cerere\", \"Monitorizare plăți\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(15, 15, 'ro', 'Atestate profesionale - CIP', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', 'Recunoașterea diplomelor și calificărilor profesionale în România și UE. Echivalare studii pentru profesii reglementate.', '[\"Echivalare diplome\", \"Recunoaștere calificări\", \"Atestate profesionale\", \"Consultanță specializată\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(17, 17, 'ro', 'Număr dosar ANC', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', 'Verificarea numărului și statusului dosarului de cetățenie la ANC. Monitorizare și notificări la fiecare actualizare.', '[\"Verificare număr dosar\", \"Status procesare\", \"Estimare termen\", \"Notificări actualizări\"]', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(94, 1, 'en', 'Romanian Citizenship', 'Complete assistance for obtaining Romanian citizenship. We handle all documentation, legalization, and submission to authorities.', 'Complete assistance for obtaining Romanian citizenship. We handle all documentation, legalization, and submission to authorities.', 'Complete assistance for obtaining Romanian citizenship through origin or marriage. We handle the entire process from documentation, legalization, and submission to the authorities.', '[\"Complete documentation\",\"Legalization and apostille\",\"Submission to authorities\",\"Case monitoring\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(98, 5, 'en', 'Citizenship by Court', 'Obtaining Romanian citizenship through court proceedings for complex cases.', 'Obtaining Romanian citizenship through court proceedings for complex cases.', 'Obtaining Romanian citizenship through court proceedings for complex cases. Experienced lawyers specialized in citizenship law.', '[\"Case analysis\",\"Specialized lawyers\",\"Court representation\",\"Success guarantee\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(100, 7, 'en', 'Document Translation', 'Certified and notarized translations for all types of documents.', 'Certified and notarized translations for all types of documents.', 'Authorized and legalized translations for all types of documents. Certified translators for Romanian, Russian, and other languages.', '[\"Certified translators\",\"Notarized translations\",\"All languages\",\"Fast delivery\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(101, 8, 'en', 'Apostille Services', 'Apostille certification for Romanian documents for international use.', 'Apostille certification for Romanian documents for international use.', 'Document apostille services for international use. Apostille on Romanian documents for use abroad.', '[\"Apostille application\",\"Fast processing\",\"All document types\",\"International validity\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(102, 9, 'en', 'Criminal Record Certificate', 'Obtaining criminal record certificates from Romania.', 'Obtaining criminal record certificates from Romania.', 'Obtaining criminal record certificates from Romania. Required for citizenship, employment, or other procedures.', '[\"Online request\",\"Fast processing\",\"Apostille included\",\"Home delivery\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(103, 10, 'en', 'Diaspora Services', 'Comprehensive services for Romanians living abroad.', 'Comprehensive services for Romanians living abroad.', 'Comprehensive services for Romanians living abroad. Document assistance without traveling to Romania.', '[\"Remote services\",\"Consular assistance\",\"Document delivery\",\"Continuous support\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(104, 11, 'en', 'Civil Status Documents', 'Obtaining and processing civil status documents from Romania.', 'Obtaining and processing civil status documents from Romania.', 'Obtaining and processing civil status documents from Romania. Birth, marriage, death certificates, and more.', '[\"All document types\",\"Registry requests\",\"Apostille\",\"Fast delivery\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(105, 12, 'en', 'Document Delivery', 'Secure delivery of Romanian documents to your location.', 'Secure delivery of Romanian documents to your location.', 'Secure delivery of Romanian documents to your home. Fast and tracked delivery services.', '[\"Tracked delivery\",\"Insurance included\",\"Express options\",\"International delivery\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(106, 13, 'en', 'Driving License', 'Assistance with Romanian driving license procedures.', 'Assistance with Romanian driving license procedures.', 'Assistance with Romanian driving license procedures. License exchange or new issuance.', '[\"License exchange\",\"Required documentation\",\"Appointments\",\"Fast processing\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(107, 14, 'en', 'Child Allowance', 'Help obtaining child allowance benefits from Romania.', 'Help obtaining child allowance benefits from Romania.', 'Assistance in obtaining child allowance from Romania. Complete documentation and submission process.', '[\"Eligibility check\",\"Complete documentation\",\"Submission to authorities\",\"Tracking\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(108, 15, 'en', 'Professional Certificates - CIP', 'Professional attestation and certificate services.', 'Professional attestation and certificate services.', 'Professional attestation and certification services. Recognition of diplomas and professional qualifications.', '[\"Diploma recognition\",\"Professional attestation\",\"Translations\",\"Legalization\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(110, 17, 'en', 'ANC Case Number', 'Tracking and obtaining your ANC (National Citizenship Authority) case number.', 'Tracking and obtaining your ANC (National Citizenship Authority) case number.', 'Tracking and obtaining your ANC case number. Find out the status of your citizenship application.', '[\"Online tracking\",\"Status updates\",\"Phone support\",\"Detailed reports\"]', '2026-03-03 12:01:24', '2026-03-03 12:26:58'),
(128, 1, 'ru', 'Румынское гражданство', 'Полная помощь в получении румынского гражданства. Мы занимаемся всей документацией, легализацией и подачей в органы власти.', 'Полная помощь в получении румынского гражданства. Мы занимаемся всей документацией, легализацией и подачей в органы власти.', 'Полная помощь в получении румынского гражданства по происхождению или браку. Мы занимаемся всей документацией, легализацией и подачей в органы власти.', '[\"Полная документация\",\"Легализация и апостиль\",\"Подача в органы власти\",\"Мониторинг дела\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(132, 5, 'ru', 'Румынское гражданство через суд', 'Получение румынского гражданства через судебное разбирательство для сложных случаев.', 'Получение румынского гражданства через судебное разбирательство для сложных случаев.', 'Получение румынского гражданства через судебное разбирательство для сложных случаев. Опытные адвокаты, специализирующиеся на гражданстве.', '[\"Анализ дела\",\"Специализированные адвокаты\",\"Представительство в суде\",\"Гарантия успеха\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(134, 7, 'ru', 'Перевод документов', 'Заверенные и нотариальные переводы всех видов документов.', 'Заверенные и нотариальные переводы всех видов документов.', 'Авторизованные и легализованные переводы всех видов документов. Сертифицированные переводчики на румынский, русский и другие языки.', '[\"Сертифицированные переводчики\",\"Нотариальные переводы\",\"Все языки\",\"Быстрая доставка\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(135, 8, 'ru', 'Апостиль документов', 'Апостиль на румынские документы для международного использования.', 'Апостиль на румынские документы для международного использования.', 'Услуги апостиля на документы для международного использования. Апостиль на румынские документы для использования за рубежом.', '[\"Подача на апостиль\",\"Быстрая обработка\",\"Все виды документов\",\"Международная действительность\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(136, 9, 'ru', 'Справка о несудимости', 'Получение справок о несудимости из Румынии.', 'Получение справок о несудимости из Румынии.', 'Получение справок о несудимости из Румынии. Требуется для гражданства, трудоустройства или других процедур.', '[\"Онлайн запрос\",\"Быстрая обработка\",\"Апостиль включён\",\"Доставка на дом\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(137, 10, 'ru', 'Услуги для диаспоры', 'Комплексные услуги для румын, проживающих за рубежом.', 'Комплексные услуги для румын, проживающих за рубежом.', 'Комплексные услуги для румын, проживающих за рубежом. Помощь с документами без поездки в Румынию.', '[\"Дистанционные услуги\",\"Консульская помощь\",\"Доставка документов\",\"Постоянная поддержка\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(138, 11, 'ru', 'Акты гражданского состояния', 'Получение и оформление документов гражданского состояния из Румынии.', 'Получение и оформление документов гражданского состояния из Румынии.', 'Получение и оформление документов гражданского состояния из Румынии. Свидетельства о рождении, браке, смерти и другие.', '[\"Все виды документов\",\"Запросы в реестр\",\"Апостиль\",\"Быстрая доставка\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(139, 12, 'ru', 'Доставка румынских документов', 'Безопасная доставка румынских документов к вам.', 'Безопасная доставка румынских документов к вам.', 'Безопасная доставка румынских документов к вам домой. Быстрая и отслеживаемая служба доставки.', '[\"Отслеживаемая доставка\",\"Страховка включена\",\"Экспресс варианты\",\"Международная доставка\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(140, 13, 'ru', 'Румынские водительские права', 'Помощь с процедурами получения румынских водительских прав.', 'Помощь с процедурами получения румынских водительских прав.', 'Помощь с процедурами получения румынских водительских прав. Обмен прав или новое оформление.', '[\"Обмен прав\",\"Необходимая документация\",\"Записи\",\"Быстрая обработка\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(141, 14, 'ru', 'Пособие на ребёнка', 'Помощь в получении детских пособий из Румынии.', 'Помощь в получении детских пособий из Румынии.', 'Помощь в получении детских пособий из Румынии. Полная документация и процесс подачи.', '[\"Проверка права\",\"Полная документация\",\"Подача в органы\",\"Отслеживание\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(142, 15, 'ru', 'Сертификаты квалификации - CIP', 'Услуги профессиональной аттестации и сертификации.', 'Услуги профессиональной аттестации и сертификации.', 'Услуги профессиональной аттестации и сертификации. Признание дипломов и профессиональных квалификаций.', '[\"Признание дипломов\",\"Профессиональная аттестация\",\"Переводы\",\"Легализация\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(144, 17, 'ru', 'Номер дела', 'Отслеживание и получение номера дела ANC.', 'Отслеживание и получение номера дела ANC.', 'Отслеживание и получение номера вашего дела ANC. Узнайте статус вашего заявления на гражданство.', '[\"Онлайн отслеживание\",\"Обновления статуса\",\"Поддержка по телефону\",\"Детальные отчёты\"]', '2026-03-03 12:07:02', '2026-03-03 12:26:58'),
(169, 18, 'ro', 'Consultație gratuită', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră și vă oferim sfaturi personalizate.', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române.', 'Oferim consultație inițială gratuită pentru a vă ajuta să înțelegeți procesul de obținere a cetățeniei române. Analizăm situația dumneavoastră, verificăm eligibilitatea și vă oferim sfaturi personalizate pentru următorii pași. Consultația se poate face online sau la sediul nostru.', '[\"Consultanță online sau la sediu\", \"Verificare eligibilitate gratuită\", \"Răspunsuri la toate întrebările\", \"Planificare personalizată\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(170, 18, 'en', 'Free Consultation', 'We offer a free initial consultation to help you understand the process of obtaining Romanian citizenship. We analyze your situation and provide personalized advice.', 'We offer a free initial consultation to help you understand the Romanian citizenship process.', 'We offer a free initial consultation to help you understand the process of obtaining Romanian citizenship. We analyze your situation, verify eligibility, and provide personalized advice for the next steps. Consultation can be done online or at our office.', '[\"Online or in-office consultation\", \"Free eligibility check\", \"Answers to all questions\", \"Personalized planning\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(171, 18, 'ru', 'Бесплатная консультация', 'Мы предлагаем бесплатную первичную консультацию, чтобы помочь вам понять процесс получения румынского гражданства. Анализируем вашу ситуацию и даём персональные рекомендации.', 'Бесплатная первичная консультация по получению румынского гражданства.', 'Мы предлагаем бесплатную первичную консультацию, чтобы помочь вам понять процесс получения румынского гражданства. Анализируем вашу ситуацию, проверяем соответствие требованиям и даём персональные рекомендации по дальнейшим шагам. Консультация возможна онлайн или в нашем офисе.', '[\"Онлайн или в офисе\", \"Бесплатная проверка соответствия\", \"Ответы на все вопросы\", \"Персональное планирование\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(172, 19, 'ro', 'Pregătirea dosarului și depunerea', 'Vă ajutăm cu strângerea și pregătirea tuturor actelor necesare pentru dosar, precum și cu depunerea acestuia la ANC.', 'Pregătirea completă a dosarului pentru cetățenie română și depunerea la ANC.', 'Vă asistăm complet în pregătirea dosarului pentru obținerea cetățeniei române. Actele necesare includ: certificat de naștere, certificat de căsătorie (dacă este cazul), acte de identitate, documente care dovedesc legătura cu România (acte ale părinților/bunicilor), traduceri autorizate și apostile. Ne ocupăm de strângerea, verificarea și depunerea dosarului la ANC.', '[\"Certificat de naștere și căsătorie\", \"Acte de identitate valabile\", \"Documente ale ascendenților români\", \"Traduceri autorizate\", \"Apostilare acte\", \"Depunere la ANC\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(173, 19, 'en', 'File Preparation and Submission', 'We help you gather and prepare all necessary documents for your file, as well as submit it to the ANC.', 'Complete file preparation for Romanian citizenship and submission to ANC.', 'We fully assist you in preparing your file for obtaining Romanian citizenship. Required documents include: birth certificate, marriage certificate (if applicable), identity documents, documents proving connection to Romania (parents/grandparents documents), authorized translations and apostilles. We handle gathering, verification, and submission to the ANC.', '[\"Birth and marriage certificates\", \"Valid identity documents\", \"Romanian ancestors documents\", \"Authorized translations\", \"Document apostille\", \"ANC submission\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(174, 19, 'ru', 'Подготовка и подача досье', 'Помогаем собрать и подготовить все необходимые документы для досье, а также подать его в ANC.', 'Полная подготовка досье на румынское гражданство и подача в ANC.', 'Полностью помогаем в подготовке досье для получения румынского гражданства. Необходимые документы: свидетельство о рождении, свидетельство о браке (при наличии), документы удостоверяющие личность, документы подтверждающие связь с Румынией (документы родителей/бабушек и дедушек), заверенные переводы и апостили. Занимаемся сбором, проверкой и подачей в ANC.', '[\"Свидетельства о рождении и браке\", \"Действующие документы\", \"Документы предков-румын\", \"Заверенные переводы\", \"Апостиль документов\", \"Подача в ANC\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(175, 20, 'ro', 'Urgentare dosar', 'Servicii de urgentare a dosarului de cetățenie pentru reducerea timpului de așteptare și procesare mai rapidă.', 'Urgentarea dosarului de cetățenie pentru procesare mai rapidă la ANC.', 'Oferim servicii de urgentare a dosarului de cetățenie română pentru cei care au nevoie de procesare mai rapidă. Monitorizăm statusul dosarului, intervenim pentru deblocarea cazurilor întârziate și asigurăm comunicarea eficientă cu autoritățile competente.', '[\"Monitorizare activă dosar\", \"Intervenție la ANC\", \"Reducere timp așteptare\", \"Comunicare cu autoritățile\", \"Rapoarte de status\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(176, 20, 'en', 'File Expediting', 'File expediting services for citizenship to reduce waiting time and faster processing.', 'Citizenship file expediting for faster processing at ANC.', 'We offer Romanian citizenship file expediting services for those who need faster processing. We monitor file status, intervene to unblock delayed cases, and ensure efficient communication with competent authorities.', '[\"Active file monitoring\", \"ANC intervention\", \"Reduced waiting time\", \"Communication with authorities\", \"Status reports\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(177, 20, 'ru', 'Ускорение досье', 'Услуги по ускорению рассмотрения досье на гражданство для сокращения времени ожидания.', 'Ускорение досье на гражданство для более быстрого рассмотрения в ANC.', 'Предлагаем услуги по ускорению досье на румынское гражданство для тех, кому нужно более быстрое рассмотрение. Отслеживаем статус досье, вмешиваемся для разблокировки задержанных дел и обеспечиваем эффективную связь с компетентными органами.', '[\"Активный мониторинг досье\", \"Вмешательство в ANC\", \"Сокращение времени ожидания\", \"Связь с властями\", \"Отчёты о статусе\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(178, 6, 'ro', 'Publicare ordin și Depunerea jurământului', 'Vă asistăm de la publicarea ordinului de acordare a cetățeniei până la depunerea jurământului de credință față de România.', 'Asistență completă de la publicarea ordinului până la depunerea jurământului de credință.', 'Vă asistăm în întregul proces final de obținere a cetățeniei: monitorizarea publicării ordinului în Monitorul Oficial, programarea pentru depunerea jurământului de credință la ANC București sau la consulatele din străinătate, pregătirea pentru ceremonie și obținerea certificatului de cetățenie.', '[\"Monitorizare publicare ordin\", \"Programare jurământ ANC/Consulate\", \"Pregătire ceremonie\", \"Asistență la depunere jurământ\", \"Obținere certificat cetățenie\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(179, 6, 'en', 'Order Publication and Oath Ceremony', 'We assist you from the publication of the citizenship order to taking the oath of allegiance to Romania.', 'Complete assistance from order publication to taking the oath of allegiance.', 'We assist you throughout the final process of obtaining citizenship: monitoring the publication of the order in the Official Gazette, scheduling the oath of allegiance at ANC Bucharest or consulates abroad, ceremony preparation, and obtaining the citizenship certificate.', '[\"Order publication monitoring\", \"Oath scheduling ANC/Consulates\", \"Ceremony preparation\", \"Oath assistance\", \"Citizenship certificate\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(180, 6, 'ru', 'Запись на присягу', 'Помогаем от публикации приказа о предоставлении гражданства до принесения присяги верности Румынии.', 'Полная помощь от публикации приказа до принесения присяги.', 'Помогаем на всём финальном этапе получения гражданства: мониторинг публикации приказа в Официальном вестнике, запись на присягу в ANC Бухарест или консульства за рубежом, подготовка к церемонии и получение сертификата о гражданстве.', '[\"Мониторинг публикации приказа\", \"Запись на присягу ANC/Консульства\", \"Подготовка к церемонии\", \"Помощь при присяге\", \"Сертификат о гражданстве\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(181, 4, 'ro', 'Transcrierea actelor de stare civilă', 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă în registrele românești.', 'Transcriere certificate de naștere, căsătorie și alte acte de stare civilă.', 'Servicii complete de transcriere a actelor de stare civilă (certificat de naștere, certificat de căsătorie, certificat de deces) din registrele străine în registrele românești. Necesar pentru obținerea actelor de identitate românești după cetățenie.', '[\"căsătorie / divorț / deces pe actul de naștere sau căsătorie\", \"Eliberare duplicate\", \"Rectificare date\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(182, 4, 'en', 'Civil Status Documents Transcription', 'Transcription of birth certificates, marriage certificates and other civil status documents into Romanian registers.', 'Transcription of birth, marriage and other civil status certificates.', 'Complete services for transcribing civil status documents (birth certificate, marriage certificate, death certificate) from foreign registers into Romanian registers. Required for obtaining Romanian identity documents after citizenship.', '[\"Marriage / divorce / death on the birth or marriage certificate\", \"Duplicate issuance\", \"Data correction\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(183, 4, 'ru', 'Румынские свидетельства о рождении и браке', 'Транскрипция свидетельств о рождении, браке и других актов гражданского состояния в румынские реестры.', 'Транскрипция свидетельств о рождении, браке и других актов.', 'Полный комплекс услуг по транскрипции актов гражданского состояния (свидетельство о рождении, браке, смерти) из иностранных реестров в румынские. Необходимо для получения румынских документов после гражданства.', '[\"Брак / развод / смерть в свидетельстве о рождении или браке\", \"Выдача дубликатов\", \"Исправление данных\"]', '2026-03-06 16:43:03', '2026-03-06 16:43:03'),
(184, 3, 'ro', 'Buletin', 'Servicii complete pentru obținerea buletinului românesc (carte de identitate). Asistență cu stabilirea domiciliului și programarea la SPCLEP.', 'Servicii complete pentru obținerea buletinului românesc (carte de identitate).', 'Servicii complete pentru obținerea cărții de identitate românești. Vă asistăm cu stabilirea domiciliului, pregătirea actelor și programarea la SPCLEP.', '[\"Programare online\", \"Verificare acte\", \"Asistență depunere\", \"Livrare rapidă\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(185, 3, 'en', 'ID Card', 'Complete services for obtaining Romanian ID card. Assistance with residence establishment and SPCLEP appointment.', 'Complete services for obtaining Romanian ID card.', 'Complete services for obtaining Romanian identity card. We assist with residence establishment, document preparation, and SPCLEP appointment.', '[\"Online appointment\", \"Document verification\", \"Submission assistance\", \"Fast delivery\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(186, 3, 'ru', 'Румынский булетин', 'Полный комплекс услуг для получения румынского удостоверения личности. Помощь с пропиской и записью в SPCLEP.', 'Полный комплекс услуг для получения румынского удостоверения личности.', 'Полный комплекс услуг для получения румынского удостоверения личности. Помогаем с пропиской, подготовкой документов и записью в SPCLEP.', '[\"Онлайн запись\", \"Проверка документов\", \"Помощь при подаче\", \"Быстрая доставка\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(187, 2, 'ro', 'Pașaport', 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură.', 'Obținerea pașaportului românesc - programări, documente și procedură completă.', 'Obțineți pașaportul românesc rapid și fără bătăi de cap. Ne ocupăm de programări, documente și întreaga procedură pentru pașaport simplu sau CRDS.', '[\"Programare la instituții\", \"Pregătire documente necesare\", \"Asistență la depunere\", \"Livrare la domiciliu\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(188, 2, 'en', 'Passport', 'Get your Romanian passport quickly and hassle-free. We handle appointments, documents, and the entire procedure.', 'Romanian passport - appointments, documents, and complete procedure.', 'Get your Romanian passport quickly and hassle-free. We handle appointments, documents, and the entire procedure for simple passport or CRDS.', '[\"Institution appointments\", \"Document preparation\", \"Submission assistance\", \"Home delivery\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(189, 2, 'ru', 'Румынский паспорт', 'Получите румынский паспорт быстро и без хлопот. Занимаемся записями, документами и всей процедурой.', 'Румынский паспорт - записи, документы и полная процедура.', 'Получите румынский паспорт быстро и без хлопот. Занимаемся записями, документами и всей процедурой для простого паспорта или CRDS.', '[\"Запись в учреждения\", \"Подготовка документов\", \"Помощь при подаче\", \"Доставка на дом\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(190, 16, 'ro', 'Transport', 'Transport persoane și colete între Moldova și România. Curse regulate săptămânale la prețuri accesibile.', 'Transport persoane și colete între Moldova și România.', 'Transport persoane și colete între Moldova, România și diasporă. Curse regulate săptămânale la prețuri accesibile.', '[\"Curse regulate\", \"Transport persoane\", \"Colete și documente\", \"Prețuri accesibile\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(191, 16, 'en', 'Transport', 'Person and parcel transport between Moldova and Romania. Regular weekly routes at affordable prices.', 'Person and parcel transport between Moldova and Romania.', 'Person and parcel transport between Moldova, Romania and the diaspora. Regular weekly routes at affordable prices.', '[\"Regular routes\", \"Person transport\", \"Parcels and documents\", \"Affordable prices\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04'),
(192, 16, 'ru', 'Транспорт в Румынию и обратно', 'Перевозка людей и посылок между Молдовой и Румынией. Регулярные еженедельные рейсы по доступным ценам.', 'Перевозка людей и посылок между Молдовой и Румынией.', 'Перевозка людей и посылок между Молдовой, Румынией и диаспорой. Регулярные еженедельные рейсы по доступным ценам.', '[\"Регулярные рейсы\", \"Перевозка людей\", \"Посылки и документы\", \"Доступные цены\"]', '2026-03-06 16:43:04', '2026-03-06 16:43:04');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_location` varchar(255) DEFAULT NULL,
  `client_photo` text DEFAULT NULL,
  `testimonial_text` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name`, `client_location`, `client_photo`, `testimonial_text`, `rating`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'Ion Munteanu', 'Chișinău, Moldova', 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'Mulțumesc echipei ActeRomânia pentru profesionalism și dedicare. Am obținut cetățenia română în doar 8 luni, mult mai repede decât așteptam. Recomand cu încredere!', 5, 1, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(2, 'Maria Popescu', 'Bălți, Moldova', 'https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'După ani de încercări eșuate pe cont propriu, am apelat la ActeRomânia. În câteva luni am avut dosarul complet și acum am și pașaportul românesc. Servicii excelente!', 5, 2, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(3, 'Andrei Rusu', 'Roma, Italia', 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop', 'Locuiesc în Italia de 15 ani și aveam nevoie de cetățenie română urgent. Echipa m-a ajutat cu toate documentele la distanță. Comunicare excelentă și rezultate rapide!', 5, 3, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `testimonials_section`
--

CREATE TABLE `testimonials_section` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `testimonials_section`
--

INSERT INTO `testimonials_section` (`id`, `section_label`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Testimoniale', 'Ce spun clienții noștri', 'Mii de clienți mulțumiți din Moldova și diaspora', '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_faq_translated`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_faq_translated` (
`id` int(11)
,`sort_order` int(11)
,`enabled` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
,`question` varchar(500)
,`answer` mediumtext
,`language` varchar(5)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_services_translated`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_services_translated` (
`id` int(11)
,`image_url` text
,`icon_svg` text
,`sort_order` int(11)
,`enabled` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
,`title` varchar(255)
,`description` mediumtext
,`short_description` mediumtext
,`full_description` mediumtext
,`features` mediumtext
,`language` varchar(5)
);

-- --------------------------------------------------------

--
-- Структура таблицы `why_us`
--

CREATE TABLE `why_us` (
  `id` int(11) NOT NULL,
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `why_us`
--

INSERT INTO `why_us` (`id`, `section_label`, `title`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'De Ce Să Ne Alegeți', 'Încredere și profesionalism la fiecare pas', 'uploads/why-us/69a6bb9f75b3d_1772534687.jpg', '2026-03-02 22:50:28', '2026-03-03 10:44:47');

-- --------------------------------------------------------

--
-- Структура таблицы `why_us_items`
--

CREATE TABLE `why_us_items` (
  `id` int(11) NOT NULL,
  `icon_svg` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `why_us_items`
--

INSERT INTO `why_us_items` (`id`, `icon_svg`, `title`, `description`, `sort_order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\"/><polyline points=\"9 12 11 14 15 10\"/></svg>', 'Servicii 100% Legale', 'Toate procedurile sunt realizate în conformitate cu legislația română și europeană în vigoare.', 1, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(2, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><path d=\"M12 6v6l4 2\"/></svg>', 'Economisiți Timp', 'Ne ocupăm de toate formalitățile, astfel încât să vă puteți concentra pe ce contează pentru dumneavoastră.', 2, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(3, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z\"/></svg>', 'Suport Dedicat', 'Echipă dedicată disponibilă să vă răspundă la întrebări și să vă asiste pe tot parcursul procesului.', 3, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(4, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><line x1=\"12\" y1=\"1\" x2=\"12\" y2=\"23\"/><path d=\"M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6\"/></svg>', 'Prețuri Transparente', 'Fără costuri ascunse. Primiți oferta detaliată înainte de a începe colaborarea.', 4, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21'),
(5, '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M22 11.08V12a10 10 0 1 1-5.93-9.14\"/><polyline points=\"22 4 12 14.01 9 11.01\"/></svg>', 'Experiență Dovedită', 'Peste 2500 de dosare finalizate cu succes și sute de clienți mulțumiți din întreaga lume.', 5, 1, '2026-03-02 22:50:28', '2026-03-03 09:43:21');

-- --------------------------------------------------------

--
-- Структура таблицы `why_us_items_translations`
--

CREATE TABLE `why_us_items_translations` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `why_us_items_translations`
--

INSERT INTO `why_us_items_translations` (`id`, `item_id`, `language`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'Servicii 100% Legale', 'Toate procedurile sunt realizate în conformitate cu legislația română și europeană în vigoare.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 2, 'ro', 'Economisiți Timp', 'Ne ocupăm de toate formalitățile, astfel încât să vă puteți concentra pe ce contează pentru dumneavoastră.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(3, 3, 'ro', 'Suport Dedicat', 'Echipă dedicată disponibilă să vă răspundă la întrebări și să vă asiste pe tot parcursul procesului.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(4, 4, 'ro', 'Prețuri Transparente', 'Fără costuri ascunse. Primiți oferta detaliată înainte de a începe colaborarea.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(5, 5, 'ro', 'Experiență Dovedită', 'Peste 2500 de dosare finalizate cu succes și sute de clienți mulțumiți din întreaga lume.', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(22, 1, 'en', '15+ Years Experience', 'Over 15 years of experience helping clients obtain Romanian citizenship and documents.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(23, 2, 'en', 'Guaranteed Results', 'We guarantee success or refund your fees. Your satisfaction is our priority.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(24, 3, 'en', 'Fast Processing', 'The fastest processing times in the industry. We value your time.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(25, 4, 'en', 'Full Transparency', 'Clear pricing with no hidden fees. You know exactly what you pay for.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(26, 5, 'en', 'Professional Team', 'Team of experienced lawyers and notaries specialized in Romanian citizenship.', '2026-03-03 12:01:24', '2026-03-03 12:01:24'),
(36, 1, 'ru', '15+ лет опыта', 'Более 15 лет опыта помощи клиентам в получении румынского гражданства и документов.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(37, 2, 'ru', 'Гарантированный результат', 'Мы гарантируем успех или вернем деньги. Ваше удовлетворение - наш приоритет.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(38, 3, 'ru', 'Быстрая обработка', 'Самые быстрые сроки обработки в отрасли. Мы ценим ваше время.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(39, 4, 'ru', 'Полная прозрачность', 'Четкие цены без скрытых платежей. Вы точно знаете, за что платите.', '2026-03-03 12:07:02', '2026-03-03 12:07:02'),
(40, 5, 'ru', 'Профессиональная команда', 'Команда опытных юристов и нотариусов, специализирующихся на румынском гражданстве.', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура таблицы `why_us_translations`
--

CREATE TABLE `why_us_translations` (
  `id` int(11) NOT NULL,
  `why_us_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'ro',
  `section_label` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `why_us_translations`
--

INSERT INTO `why_us_translations` (`id`, `why_us_id`, `language`, `section_label`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 'ro', 'De Ce Să Ne Alegeți', 'Încredere și profesionalism la fiecare pas', '2026-03-03 11:50:50', '2026-03-03 11:50:50'),
(2, 1, 'en', 'Why Choose Us', 'Why Work With Us?', '2026-03-03 11:55:29', '2026-03-03 11:55:29'),
(4, 1, 'ru', 'Почему мы', 'Почему выбирают нас?', '2026-03-03 12:07:02', '2026-03-03 12:07:02');

-- --------------------------------------------------------

--
-- Структура для представления `v_faq_translated`
--
DROP TABLE IF EXISTS `v_faq_translated`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_faq_translated`  AS SELECT `f`.`id` AS `id`, `f`.`sort_order` AS `sort_order`, `f`.`enabled` AS `enabled`, `f`.`created_at` AS `created_at`, `f`.`updated_at` AS `updated_at`, coalesce(`ft`.`question`,`f`.`question`) AS `question`, coalesce(`ft`.`answer`,`f`.`answer`) AS `answer`, coalesce(`ft`.`language`,'ro') AS `language` FROM (`faq` `f` left join `faq_translations` `ft` on(`f`.`id` = `ft`.`faq_id`)) ;

-- --------------------------------------------------------

--
-- Структура для представления `v_services_translated`
--
DROP TABLE IF EXISTS `v_services_translated`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_services_translated`  AS SELECT `s`.`id` AS `id`, `s`.`image_url` AS `image_url`, `s`.`icon_svg` AS `icon_svg`, `s`.`sort_order` AS `sort_order`, `s`.`enabled` AS `enabled`, `s`.`created_at` AS `created_at`, `s`.`updated_at` AS `updated_at`, coalesce(`st`.`title`,`s`.`title`) AS `title`, coalesce(`st`.`description`,`s`.`description`) AS `description`, coalesce(`st`.`short_description`,`s`.`short_description`) AS `short_description`, coalesce(`st`.`full_description`,`s`.`full_description`) AS `full_description`, coalesce(`st`.`features`,`s`.`features`) AS `features`, coalesce(`st`.`language`,'ro') AS `language` FROM (`services` `s` left join `services_translations` `st` on(`s`.`id` = `st`.`service_id`)) ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `about_stats`
--
ALTER TABLE `about_stats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `about_stats_translations`
--
ALTER TABLE `about_stats_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_stat_lang` (`stat_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `about_translations`
--
ALTER TABLE `about_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_about_lang` (`about_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contacts_history`
--
ALTER TABLE `contacts_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_source` (`source`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Индексы таблицы `contact_translations`
--
ALTER TABLE `contact_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_contact_lang` (`contact_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `faq_section`
--
ALTER TABLE `faq_section`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `faq_section_translations`
--
ALTER TABLE `faq_section_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section_lang` (`section_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `faq_translations`
--
ALTER TABLE `faq_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_faq_lang` (`faq_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `hero`
--
ALTER TABLE `hero`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `hero_translations`
--
ALTER TABLE `hero_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_hero_lang` (`hero_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `hero_trust_items`
--
ALTER TABLE `hero_trust_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `hero_trust_items_translations`
--
ALTER TABLE `hero_trust_items_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_trust_lang` (`trust_item_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pending_reviews`
--
ALTER TABLE `pending_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `process_section`
--
ALTER TABLE `process_section`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `process_section_translations`
--
ALTER TABLE `process_section_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section_lang` (`section_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `process_steps`
--
ALTER TABLE `process_steps`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `process_steps_translations`
--
ALTER TABLE `process_steps_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_step_lang` (`step_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_approved` (`approved`),
  ADD KEY `idx_reviews_created_at` (`created_at`),
  ADD KEY `idx_reviews_email` (`email`);

--
-- Индексы таблицы `reviews_section_translations`
--
ALTER TABLE `reviews_section_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_lang` (`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `services_section`
--
ALTER TABLE `services_section`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `services_section_translations`
--
ALTER TABLE `services_section_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section_lang` (`section_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `services_translations`
--
ALTER TABLE `services_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_service_lang` (`service_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Индексы таблицы `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testimonials_section`
--
ALTER TABLE `testimonials_section`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `why_us`
--
ALTER TABLE `why_us`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `why_us_items`
--
ALTER TABLE `why_us_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `why_us_items_translations`
--
ALTER TABLE `why_us_items_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_lang` (`item_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- Индексы таблицы `why_us_translations`
--
ALTER TABLE `why_us_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_why_us_lang` (`why_us_id`,`language`),
  ADD KEY `idx_language` (`language`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `about_stats`
--
ALTER TABLE `about_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `about_stats_translations`
--
ALTER TABLE `about_stats_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `about_translations`
--
ALTER TABLE `about_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `contacts_history`
--
ALTER TABLE `contacts_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `contact_translations`
--
ALTER TABLE `contact_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `faq_section`
--
ALTER TABLE `faq_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `faq_section_translations`
--
ALTER TABLE `faq_section_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `faq_translations`
--
ALTER TABLE `faq_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `hero`
--
ALTER TABLE `hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `hero_translations`
--
ALTER TABLE `hero_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `hero_trust_items`
--
ALTER TABLE `hero_trust_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `hero_trust_items_translations`
--
ALTER TABLE `hero_trust_items_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pending_reviews`
--
ALTER TABLE `pending_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `process_section`
--
ALTER TABLE `process_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `process_section_translations`
--
ALTER TABLE `process_section_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `process_steps`
--
ALTER TABLE `process_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `process_steps_translations`
--
ALTER TABLE `process_steps_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `reviews_section_translations`
--
ALTER TABLE `reviews_section_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `services_section`
--
ALTER TABLE `services_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `services_section_translations`
--
ALTER TABLE `services_section_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `services_translations`
--
ALTER TABLE `services_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `testimonials_section`
--
ALTER TABLE `testimonials_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `why_us`
--
ALTER TABLE `why_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `why_us_items`
--
ALTER TABLE `why_us_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `why_us_items_translations`
--
ALTER TABLE `why_us_items_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `why_us_translations`
--
ALTER TABLE `why_us_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `about_stats_translations`
--
ALTER TABLE `about_stats_translations`
  ADD CONSTRAINT `about_stats_translations_ibfk_1` FOREIGN KEY (`stat_id`) REFERENCES `about_stats` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `about_translations`
--
ALTER TABLE `about_translations`
  ADD CONSTRAINT `about_translations_ibfk_1` FOREIGN KEY (`about_id`) REFERENCES `about` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `contact_translations`
--
ALTER TABLE `contact_translations`
  ADD CONSTRAINT `contact_translations_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `faq_section_translations`
--
ALTER TABLE `faq_section_translations`
  ADD CONSTRAINT `faq_section_translations_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `faq_section` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `faq_translations`
--
ALTER TABLE `faq_translations`
  ADD CONSTRAINT `faq_translations_ibfk_1` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `hero_translations`
--
ALTER TABLE `hero_translations`
  ADD CONSTRAINT `hero_translations_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `hero` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `hero_trust_items_translations`
--
ALTER TABLE `hero_trust_items_translations`
  ADD CONSTRAINT `hero_trust_items_translations_ibfk_1` FOREIGN KEY (`trust_item_id`) REFERENCES `hero_trust_items` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `process_section_translations`
--
ALTER TABLE `process_section_translations`
  ADD CONSTRAINT `process_section_translations_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `process_section` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `process_steps_translations`
--
ALTER TABLE `process_steps_translations`
  ADD CONSTRAINT `process_steps_translations_ibfk_1` FOREIGN KEY (`step_id`) REFERENCES `process_steps` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `services_section_translations`
--
ALTER TABLE `services_section_translations`
  ADD CONSTRAINT `services_section_translations_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `services_section` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `services_translations`
--
ALTER TABLE `services_translations`
  ADD CONSTRAINT `services_translations_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `why_us_items_translations`
--
ALTER TABLE `why_us_items_translations`
  ADD CONSTRAINT `why_us_items_translations_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `why_us_items` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `why_us_translations`
--
ALTER TABLE `why_us_translations`
  ADD CONSTRAINT `why_us_translations_ibfk_1` FOREIGN KEY (`why_us_id`) REFERENCES `why_us` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
