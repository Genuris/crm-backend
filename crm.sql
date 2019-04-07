-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 27 2019 г., 12:30
-- Версия сервера: 5.5.50
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `agencies`
--

CREATE TABLE IF NOT EXISTS `agencies` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `agencies`
--

INSERT INTO `agencies` (`id`, `title`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'The first agency', 1, '2018-12-16 14:34:22', '2018-12-16 14:34:22');

-- --------------------------------------------------------

--
-- Структура таблицы `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  `office_id` int(10) unsigned NOT NULL,
  `cards_contacts_id` int(11) DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apartment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landmark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_or_realtor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_built` int(11) DEFAULT NULL,
  `floors_house` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_floors` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_rooms` int(11) DEFAULT NULL,
  `type_building` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roof` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `living_area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kitchen_area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ceiling_height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heating` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `electricity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `water_pipes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bathroom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sewage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gas` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `land_area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `how_plot_fenced` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entrance_door` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `furniture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `window` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_from_windows` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `garbage_chute` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtypes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `commission` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_expiration_date` bigint(20) DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL,
  `number_contract` bigint(20) DEFAULT NULL,
  `stage_transaction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cards`
--

INSERT INTO `cards` (`id`, `user_id`, `agency_id`, `office_id`, `cards_contacts_id`, `type`, `sale_type`, `city`, `area`, `street`, `building`, `apartment`, `price`, `currency`, `landmark`, `owner_or_realtor`, `year_built`, `floors_house`, `number_of_floors`, `number_rooms`, `type_building`, `roof`, `total_area`, `living_area`, `kitchen_area`, `ceiling_height`, `condition`, `heating`, `electricity`, `water_pipes`, `bathroom`, `sewage`, `internet`, `gas`, `security`, `land_area`, `how_plot_fenced`, `entrance_door`, `furniture`, `window`, `view_from_windows`, `garbage_chute`, `layout`, `subtypes`, `description`, `comment`, `commission`, `contract_expiration_date`, `is_archived`, `number_contract`, `stage_transaction`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '1', 'centre', 'subtypes', 'the test description', 'test comment', NULL, NULL, 0, NULL, NULL, '2018-12-25 15:43:11', '2018-12-25 15:43:11'),
(2, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '1', 'centre', 'subtypes', 'the test description', 'test comment', NULL, NULL, 0, NULL, NULL, '2018-12-25 15:43:36', '2018-12-25 15:43:36'),
(3, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '0', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2018-12-25 15:45:04', '2018-12-25 15:45:04'),
(4, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '1', 'centre', 'subtypes', 'the test description', 'test comment', NULL, NULL, 0, NULL, NULL, '2018-12-25 15:45:35', '2018-12-25 15:45:35'),
(5, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '1', 'centre', 'subtypes', 'the test description', 'test comment', NULL, NULL, 0, NULL, NULL, '2018-12-25 15:48:03', '2018-12-25 15:48:03'),
(6, 1, 1, 1, 3, 'sale', 'object', 'Krivoy Rog', 'Dnepr obl', 'Petra Doroshenka', '5', '155', '21000', 'usd', NULL, 'owner', 1970, '9', NULL, 3, 'panel', 'have', '75', '50', '7', '2.50', 'good', 'central', 'have', 'have', 'have', 'have', 'have', 'have', 'have', '150', 'none', 'metal', 'have', NULL, 'park', '1', 'centre', 'subtypes', 'the test description', 'test comment', NULL, NULL, 0, 551313, NULL, '2019-01-17 17:34:05', '2019-01-17 17:34:05');

-- --------------------------------------------------------

--
-- Структура таблицы `cards_contacts`
--

CREATE TABLE IF NOT EXISTS `cards_contacts` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `decision_makers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animals` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kind_of_activity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leisure` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  `is_realtor` tinyint(1) DEFAULT NULL,
  `is_partner` tinyint(1) DEFAULT NULL,
  `is_client` tinyint(1) DEFAULT NULL,
  `is_married` tinyint(1) DEFAULT NULL,
  `work_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `is_black_list` tinyint(1) DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cards_contacts`
--

INSERT INTO `cards_contacts` (`id`, `name`, `email`, `comment`, `decision_makers`, `animals`, `kind_of_activity`, `leisure`, `years`, `is_realtor`, `is_partner`, `is_client`, `is_married`, `work_place`, `car`, `children`, `is_black_list`, `agency_id`, `created_at`, `updated_at`) VALUES
(3, 'test', 'test@test.ua', 'test', 'test', 'test', 'test', 'test', 20, 1, 0, 1, 1, 'test', 'test', 2, 0, 1, '2018-12-25 15:33:14', '2019-02-23 14:10:16');

-- --------------------------------------------------------

--
-- Структура таблицы `cards_contacts_phones`
--

CREATE TABLE IF NOT EXISTS `cards_contacts_phones` (
  `id` int(10) unsigned NOT NULL,
  `cards_contacts_id` int(10) unsigned NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cards_contacts_phones`
--

INSERT INTO `cards_contacts_phones` (`id`, `cards_contacts_id`, `agency_id`, `phone`, `created_at`, `updated_at`) VALUES
(8, 3, 1, '+380964737815', '2019-02-23 14:03:42', '2019-02-23 14:03:42');

-- --------------------------------------------------------

--
-- Структура таблицы `cards_files`
--

CREATE TABLE IF NOT EXISTS `cards_files` (
  `id` int(10) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_id` int(10) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cards_files`
--

INSERT INTO `cards_files` (`id`, `type`, `card_id`, `file_id`, `created_at`, `updated_at`) VALUES
(2, 'image/jpeg', 6, 2, '2019-01-17 17:34:05', '2019-01-17 17:34:05');

-- --------------------------------------------------------

--
-- Структура таблицы `card_categories`
--

CREATE TABLE IF NOT EXISTS `card_categories` (
  `id` int(10) unsigned NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fields` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `card_categories`
--

INSERT INTO `card_categories` (`id`, `value`, `title`, `fields`, `created_at`, `updated_at`) VALUES
(1, 'apartments', 'Квартиры', NULL, '2018-12-03 15:46:51', '2018-12-03 15:46:51'),
(2, 'houses_cottages', 'Дома и дачи', NULL, '2018-12-03 15:46:51', '2018-12-03 15:46:51'),
(3, 'garages', 'Гаражи', NULL, '2018-12-03 15:46:51', '2018-12-03 15:46:51'),
(4, 'area', 'Участки', NULL, '2018-12-03 15:46:51', '2018-12-03 15:46:51'),
(5, 'commercial', 'Коммерческая', NULL, '2018-12-03 15:46:51', '2018-12-03 15:46:51');

-- --------------------------------------------------------

--
-- Структура таблицы `card_subcategories`
--

CREATE TABLE IF NOT EXISTS `card_subcategories` (
  `id` int(10) unsigned NOT NULL,
  `card_categories_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) unsigned NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `currencies`
--

INSERT INTO `currencies` (`id`, `value`, `title`, `created_at`, `updated_at`) VALUES
(1, 'rub', 'RU', NULL, NULL),
(2, 'eur', 'EUR', NULL, NULL),
(3, 'usd', 'USD', NULL, NULL),
(4, 'uah', 'ГРН', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `name`, `extension`, `hash`, `type`, `created_at`, `updated_at`) VALUES
(1, 'image123.JPG', 'JPG', 'FuYC2XVMSdn246TY6idCOQNoS7hEMOZl9z9IfcBR.jpeg', 'image/jpeg', '2018-12-03 06:26:35', '2018-12-03 06:26:35'),
(2, 'image123.JPG', 'JPG', 'jFOGN9FGQfKbCgO5LCbkLiCn3sfeFSfgI65qSgOZ.jpeg', 'image/jpeg', '2018-12-03 06:29:52', '2018-12-03 06:29:52'),
(3, 'regexp.pdf', 'pdf', '3zl5XbyOelDN3kG9a0cJk9OZfD6D6MJdWIDMKZG0.pdf', 'application/pdf', '2018-12-03 06:31:34', '2018-12-03 06:31:34'),
(4, 'regexp.pdf', 'pdf', 'TknNvgqXY0fTIrVPTFPX54XHKqWFzQzfo2UqL8Gq.pdf', 'application/pdf', '2018-12-03 06:38:46', '2018-12-03 06:38:46'),
(5, 'Макс Корж – Горы по колено.mp3', 'mp3', 'xa1m8k77qwOHrTtrHCQnQ7gOM7tvdnIF0h4eehmq.mp3', 'application/octet-stream', '2019-02-09 14:58:33', '2019-02-09 14:58:33'),
(6, 'image123.JPG', 'JPG', 'fKbIiWVLNOnba9hJR90zLn9gMORE4sSPsy0cjCaO.JPG', 'image/jpeg', '2019-02-09 14:59:32', '2019-02-09 14:59:32'),
(7, '1.css', 'css', 'aqgdVpBcYVRsuMEgE1Rjq7TGND5mbiz1vWLEpwta.css', 'text/plain', '2019-02-09 15:42:39', '2019-02-09 15:42:39'),
(8, '123.amr', 'amr', 'TwmEVDXHImRKDA4Mhkj4HMflUsXVK14dl91EcZD1.amr', 'application/octet-stream', '2019-02-09 15:45:29', '2019-02-09 15:45:29'),
(9, 'call_13-08-07_OUT_0678303061', '', 'W8VTrKnV9Bujc46DhmyKOshRlQ3FtIzxBaD3IB7H', 'application/octet-stream', '2019-02-09 16:24:15', '2019-02-09 16:24:15');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(6, '2016_06_01_000004_create_oauth_clients_table', 2),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(8, '2018_12_03_075948_create_files_table', 3),
(9, '2018_12_03_163331_create_roles_table', 4),
(10, '2018_12_03_163906_add_users_field_role_id', 4),
(11, '2018_12_03_170235_create_card_categories_table', 5),
(12, '2018_12_07_171954_create_currencies_table', 6),
(13, '2018_12_09_120726_add_users_field_surname', 7),
(14, '2018_12_09_120804_add_users_field_middle_name', 7),
(16, '2018_12_09_120848_add_users_field_time_zone', 8),
(18, '2018_12_09_130006_create_users_details_table', 9),
(21, '2018_12_09_132914_create_users_phones_table', 10),
(22, '2018_12_09_132957_create_social_networks_table', 10),
(23, '2018_12_09_132957_create_users_social_networks_table', 10),
(24, '2018_12_10_155411_add_users_details_field_profile_image_id', 11),
(25, '2018_12_15_141213_create_agencies_table', 12),
(26, '2018_12_15_141231_create_offices_table', 12),
(28, '2018_12_15_141246_create_cards_table', 12),
(29, '2018_12_15_141254_create_cards_files_table', 12),
(30, '2018_12_15_145505_add_users_field_agency_id', 13),
(31, '2018_12_15_145520_add_users_field_office_id', 13),
(32, '2018_12_15_145537_add_users_field_offices_partition_id', 13),
(33, '2018_12_23_134759_create_cards_contacts_table', 14),
(34, '2018_12_23_135120_add_cards_field_cards_contacts_id', 14),
(35, '2018_12_23_135144_create_cards_contacts_phones_table', 14),
(36, '2018_12_15_141238_create_offices_partitions_table', 15),
(37, '2018_12_26_151342_add_users_details_field_birthday', 16),
(38, '2018_12_29_151749_add_cards_field_stage_transaction', 17),
(40, '2018_12_29_151844_add_cards_field_number_contract', 17),
(41, '2018_12_29_151933_add_cards_field_contract_expiration_date', 17),
(42, '2018_12_29_152953_add_cards_field_is_archived', 18),
(43, '2018_12_29_151815_add_cards_field_commission', 19),
(44, '2019_01_03_094516_add_cards_contacts_column_agency_id', 20),
(45, '2019_01_03_094548_add_cards_contacts_phones_column_agency_id', 20),
(46, '2019_01_08_194312_add_cards_contacts_field_children', 21),
(47, '2019_01_08_194323_add_cards_contacts_field_car', 21),
(48, '2019_01_08_194337_add_cards_contacts_field_work_place', 21),
(49, '2019_01_08_194350_add_cards_contacts_field_is_married', 21),
(50, '2019_01_08_194401_add_cards_contacts_field_is_client', 21),
(51, '2019_01_08_194412_add_cards_contacts_field_is_partner', 21),
(52, '2019_01_08_194423_add_cards_contacts_field_is_realtor', 21),
(53, '2019_01_08_194434_add_cards_contacts_field_years', 21),
(54, '2019_01_08_194444_add_cards_contacts_field_leisure', 21),
(55, '2019_01_08_194455_add_cards_contacts_field_kind_of_activity', 21),
(56, '2019_01_08_194515_add_cards_contacts_field_animals', 21),
(57, '2019_01_08_194525_add_cards_contacts_field_decision_makers', 21),
(58, '2019_01_08_195114_add_cards_contacts_field_is_black_list', 21),
(60, '2019_01_21_174752_create_tasks_table', 22),
(61, '2019_01_26_132953_create_card_subcategories_table', 23),
(62, '2019_01_26_164138_add_card_categories_field_fields', 24),
(63, '2019_01_26_164649_add_cards_field_number_of_floors', 24),
(64, '2019_01_29_192002_change_cards_field_garbage_chute', 25),
(65, '2019_02_05_190847_change_cards_field_comment', 26),
(66, '2019_02_05_190908_change_cards_field_description', 26),
(67, '2019_02_14_162656_add_cards_contacts_field_comment', 27),
(69, '2019_02_23_155442_change_cards_contacts_phones_column_phone', 28);

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_access_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('0ccecb298b5dacae95879a9b68aed7b4521371e728d309f4775ea56666d9cfd46d53d7eb07456920', 1, 3, NULL, '["*"]', 0, '2019-01-30 06:09:28', '2019-01-30 06:09:28', '2020-01-30 08:09:28'),
('1286fd6813d69d1ed16f7b88324fbd95ceb6d05b3faa57b03a06bc2d9c904391612a169584bb2e99', 1, 3, NULL, '["*"]', 0, '2019-02-04 16:19:04', '2019-02-04 16:19:04', '2020-02-04 18:19:04'),
('140cd285737069c25de1782564eac407992b90144cefb32a847971449a1796c2763c21d1c46cfb05', 1, 3, NULL, '["*"]', 0, '2018-12-03 06:32:29', '2018-12-03 06:32:29', '2019-12-03 08:32:29'),
('3ad28455beab20735d15ed39f69f687d50b08a3eb0d192f11997a3e44aaad16e517cd047aebc9259', 1, 3, NULL, '["*"]', 0, '2019-02-10 09:02:20', '2019-02-10 09:02:20', '2020-02-10 11:02:20'),
('864a3f55fb6a4d1174e141721069a6115a2e215428a1304ee18bde3d518c3c4a826bbfc7408ca420', 1, 3, NULL, '["*"]', 0, '2019-02-04 16:27:37', '2019-02-04 16:27:37', '2020-02-04 18:27:37'),
('bb5a96dd8fafa6392987b91b6803fadcd0c64fc45b3ee4a74e3e87b2465e8072f76baa3598f87002', 1, 3, NULL, '["*"]', 0, '2018-12-02 17:23:30', '2018-12-02 17:23:30', '2019-12-02 19:23:30'),
('c4e107c3a0c14347327ab36207303d64d3b4db708c62438694ddec0a8b21d01b814bdd2ecb676c58', 1, 3, NULL, '["*"]', 0, '2018-12-03 06:37:34', '2018-12-03 06:37:34', '2019-12-03 08:37:34'),
('d1d3a7c5d21ef40b4c4c26d9653aca175e101c4f481e17e66201da39c4b56cdf0c1265bbf3d99b94', 1, 3, NULL, '["*"]', 0, '2018-12-02 17:25:47', '2018-12-02 17:25:47', '2019-12-02 19:25:47'),
('fafd8285677365a0361f50d8f26b6aa9f1b5f741560290da146d6b83eb5205c41b5f4ccba493342b', 1, 3, NULL, '["*"]', 0, '2019-02-07 05:33:54', '2019-02-07 05:33:54', '2020-02-07 07:33:54');

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_auth_codes`
--

CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'CRM-backend Personal Access Client', 'KbmgP8AXtXtIsRw069va6fVipCEuEgrdZEIM564c', 'http://localhost', 1, 0, 0, '2018-12-02 16:52:09', '2018-12-02 16:52:09'),
(2, NULL, 'CRM-backend Password Grant Client', '7hIltm6Hq3zA98XJ8ExrdXwgzaRwPk7RaqvRvWUs', 'http://localhost', 0, 1, 0, '2018-12-02 16:52:09', '2018-12-02 16:52:09'),
(3, NULL, 'crm-frontend', 'lAMSjiEobNtUyOExcvdz1qzvXLZgcN7dD95JzHWv', 'http://localhost', 0, 1, 0, '2018-12-02 16:56:24', '2018-12-02 16:56:24');

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_personal_access_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2018-12-02 16:52:09', '2018-12-02 16:52:09');

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_refresh_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('263ca48f96cbf75d9e3c645eb1ee22d243ce6f3100115971c17db78ef662c60c56546b26fee77ed0', 'bb5a96dd8fafa6392987b91b6803fadcd0c64fc45b3ee4a74e3e87b2465e8072f76baa3598f87002', 0, '2019-12-02 19:23:30'),
('59f2ce7f32a00b54433af3d607aafc0b03859977641981789bece88ac78e94dd2053a62af79eb367', '140cd285737069c25de1782564eac407992b90144cefb32a847971449a1796c2763c21d1c46cfb05', 0, '2019-12-03 08:32:29'),
('5c6e228b8a76981665d3aba0cd6d378e2e538d3ab6a48149fd99db166501a5c391c4cb39e00af700', '0ccecb298b5dacae95879a9b68aed7b4521371e728d309f4775ea56666d9cfd46d53d7eb07456920', 0, '2020-01-30 08:09:28'),
('67e5465acd839dcf0246008dcd1f294f16ef003ebcc9d374a242ddce2a0066df63eb8ba839f9e998', '864a3f55fb6a4d1174e141721069a6115a2e215428a1304ee18bde3d518c3c4a826bbfc7408ca420', 0, '2020-02-04 18:27:37'),
('8c2e9fee4c9c92cc5a8291951581668574c064f7163e759d9b90419473c4988637fdf0e3483a41ec', '3ad28455beab20735d15ed39f69f687d50b08a3eb0d192f11997a3e44aaad16e517cd047aebc9259', 0, '2020-02-10 11:02:20'),
('91e1ef166e1ce22d5952fe858e6f4409d590f3ce2d4ad92251a41e7c9824eeea8b5d8153f7b74f6f', '1286fd6813d69d1ed16f7b88324fbd95ceb6d05b3faa57b03a06bc2d9c904391612a169584bb2e99', 0, '2020-02-04 18:19:04'),
('bfcc854468ca3c53df9395324e8e9fc0cafcfa7d48c7f9098ad7cf867f28bfc79affa989c1bd211b', 'fafd8285677365a0361f50d8f26b6aa9f1b5f741560290da146d6b83eb5205c41b5f4ccba493342b', 0, '2020-02-07 07:33:54'),
('cbfdbfde03607656b10b3b2e6f1c61ecca02e3079b307686e4a32db1e259b50bd0046a6617a3a55b', 'c4e107c3a0c14347327ab36207303d64d3b4db708c62438694ddec0a8b21d01b814bdd2ecb676c58', 0, '2019-12-03 08:37:34'),
('d93d16eab5b7e30b5f17bd9ec9758761c81ac1bdf2337710741b699e8e11692e1f57419e0720e520', 'd1d3a7c5d21ef40b4c4c26d9653aca175e101c4f481e17e66201da39c4b56cdf0c1265bbf3d99b94', 0, '2019-12-02 19:25:47');

-- --------------------------------------------------------

--
-- Структура таблицы `offices`
--

CREATE TABLE IF NOT EXISTS `offices` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apartment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `offices`
--

INSERT INTO `offices` (`id`, `title`, `city`, `area`, `street`, `building`, `apartment`, `user_id`, `agency_id`, `created_at`, `updated_at`) VALUES
(1, 'The first office', 'Kiev', 'Kiev', 'street', '15', '14', 1, 1, '2018-12-16 14:36:23', '2018-12-16 14:36:23');

-- --------------------------------------------------------

--
-- Структура таблицы `offices_partitions`
--

CREATE TABLE IF NOT EXISTS `offices_partitions` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `office_id` int(10) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actions` longtext COLLATE utf8mb4_unicode_ci,
  `fields` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `title`, `description`, `actions`, `fields`, `created_at`, `updated_at`) VALUES
(1, 'ROLE_ADMIN', 'Administrator', 'Это роль может все', '[{"cards":["see","edit","delete","add"]},{"agencies":["see","edit","delete","add"]},{"card_categories":["see","edit","delete","add"]},{"card_subcategories":["see","edit","delete","add"]},{"currencies":["see","edit","delete","add"]},{"offices":["see","edit","delete","add"]},{"offices_partitions":["see","edit","delete","add"]},{"roles":["see","edit","delete","add"]},{"social_networks":["see","edit","delete","add"]},{"tasks":["see","edit","delete","add"]}]', NULL, NULL, NULL),
(2, 'ROLE_AGENCY_DIRECTOR', 'ROLE_AGENCY_DIRECTOR', 'ROLE_AGENCY_DIRECTOR', '[{"cards":["see","edit","delete","add"]},{"agencies":["see","edit","delete","add"]},{"card_categories":["see","edit","delete","add"]},{"card_subcategories":["see","edit","delete","add"]},{"currencies":["see","edit","delete","add"]},{"offices":["see","edit","delete","add"]},{"offices_partitions":["see","edit","delete","add"]},{"roles":["see","edit","delete","add"]},{"social_networks":["see","edit","delete","add"]},{"tasks":["see","edit","delete","add"]}]', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `social_networks`
--

CREATE TABLE IF NOT EXISTS `social_networks` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `social_networks`
--

INSERT INTO `social_networks` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Telegram', '2018-12-09 11:48:21', '2018-12-09 11:48:21'),
(2, 'Facebook', '2018-12-09 11:48:31', '2018-12-09 11:48:31'),
(3, 'VK', '2018-12-09 11:48:36', '2018-12-09 11:48:36'),
(4, 'ICQ', '2018-12-09 11:52:30', '2018-12-09 11:52:30');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL,
  `cards_contacts_id` int(10) unsigned DEFAULT NULL,
  `card_id` int(10) unsigned DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `date_time` timestamp NULL DEFAULT NULL,
  `remind` timestamp NULL DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `responsibles` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `offices_partition_id` int(11) DEFAULT NULL,
  `role_id` tinyint(4) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `agency_id`, `office_id`, `offices_partition_id`, `role_id`, `name`, `email`, `middle_name`, `surname`, `time_zone`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 1, 'admin', 'a13xskrepchuk@gmail.com', NULL, NULL, NULL, '$2y$10$ApQp5DrpIA6yoPGs1GG4euVmJjfgBsX5iMZJNGjLyi/3wHbhh98wy', 'UXxL2Po1BZdYTZPGsLqpcLdW16NDpFxVEwVjUlKtGiV5J4uuLzeuYGY5tkZ0', '2018-12-02 17:19:37', '2018-12-02 17:19:37'),
(2, NULL, NULL, NULL, 1, 'Alex', 'al3xk@mail.ru', NULL, NULL, NULL, '$2y$10$R.632AYZ9ZDKKLvw3BosU.CbsZRAZUhzJB67yIG9OU8VvMDURgG8q', NULL, '2018-12-04 16:34:48', '2018-12-04 16:34:48'),
(20, 1, 1, 1, 1, 'Григорий', 'ka@lindenvalley.de', 'Александрович', 'Крепчук', NULL, '$2y$10$.OjVsmvQ7QTR8Rb2B0ibxemUELzKlwNSUzyq/H0rARkPDI3hB3NJy', NULL, '2018-12-11 16:29:27', '2019-02-07 05:58:50'),
(21, 1, 1, 1, 1, 'Григорий', '1ka1@lindenvalley.de', 'Александрович', 'Крепчук', NULL, '$2y$10$S6iFyy5R584sE6qKKtp6UOdGdJXJfkpCkygUARDk8wl4Vm0eJJpC6', NULL, '2018-12-24 15:46:50', '2018-12-24 15:46:50'),
(22, 1, 1, 1, 1, 'Rodion', '2ka2@lindenvalley.de', 'Rodion', 'Rodion', NULL, '$2y$10$GKLjFQD8n6689NvEsGCwd..vnXMvwKoxHxpMyBOyN6Z.Rs08v853K', NULL, '2018-12-26 13:17:03', '2018-12-26 13:17:03');

-- --------------------------------------------------------

--
-- Структура таблицы `users_details`
--

CREATE TABLE IF NOT EXISTS `users_details` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image_id` int(11) DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_details`
--

INSERT INTO `users_details` (`id`, `user_id`, `city`, `birthday`, `postal_code`, `profile_image_id`, `currency`, `created_at`, `updated_at`) VALUES
(4, 20, 'Krivoy Rog', NULL, '5000111', 1, '1', '2018-12-11 16:29:27', '2019-02-07 05:50:32'),
(5, 21, 'Krivoy Rog', NULL, '500011', 1, '1', '2018-12-24 15:46:50', '2018-12-24 15:46:50'),
(6, 22, 'Krivoy Rog', '31.10.1990', '500011', 1, '1', '2018-12-26 13:17:03', '2018-12-26 13:17:03');

-- --------------------------------------------------------

--
-- Структура таблицы `users_phones`
--

CREATE TABLE IF NOT EXISTS `users_phones` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_phones`
--

INSERT INTO `users_phones` (`id`, `user_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 22, '+380676305637', '2018-12-26 13:17:03', '2018-12-26 13:17:03'),
(2, 22, '+380668817929', '2018-12-26 13:17:03', '2018-12-26 13:17:03');

-- --------------------------------------------------------

--
-- Структура таблицы `users_social_networks`
--

CREATE TABLE IF NOT EXISTS `users_social_networks` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `social_network_id` int(10) unsigned NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agencies_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cards_user_id_foreign` (`user_id`),
  ADD KEY `cards_agency_id_foreign` (`agency_id`),
  ADD KEY `cards_office_id_foreign` (`office_id`);

--
-- Индексы таблицы `cards_contacts`
--
ALTER TABLE `cards_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cards_contacts_phones`
--
ALTER TABLE `cards_contacts_phones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cards_contacts_phones_cards_contacts_id_foreign` (`cards_contacts_id`);

--
-- Индексы таблицы `cards_files`
--
ALTER TABLE `cards_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cards_files_card_id_foreign` (`card_id`),
  ADD KEY `cards_files_file_id_foreign` (`file_id`);

--
-- Индексы таблицы `card_categories`
--
ALTER TABLE `card_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_categories_value_unique` (`value`);

--
-- Индексы таблицы `card_subcategories`
--
ALTER TABLE `card_subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_value_unique` (`value`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Индексы таблицы `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Индексы таблицы `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Индексы таблицы `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Индексы таблицы `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offices_user_id_foreign` (`user_id`),
  ADD KEY `offices_agency_id_foreign` (`agency_id`);

--
-- Индексы таблицы `offices_partitions`
--
ALTER TABLE `offices_partitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offices_partitions_user_id_foreign` (`user_id`),
  ADD KEY `offices_partitions_office_id_foreign` (`office_id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Индексы таблицы `social_networks`
--
ALTER TABLE `social_networks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `users_details`
--
ALTER TABLE `users_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_details_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `users_phones`
--
ALTER TABLE `users_phones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_phones_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `users_social_networks`
--
ALTER TABLE `users_social_networks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_social_networks_user_id_foreign` (`user_id`),
  ADD KEY `users_social_networks_social_network_id_foreign` (`social_network_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `cards_contacts`
--
ALTER TABLE `cards_contacts`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `cards_contacts_phones`
--
ALTER TABLE `cards_contacts_phones`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `cards_files`
--
ALTER TABLE `cards_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `card_categories`
--
ALTER TABLE `card_categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `card_subcategories`
--
ALTER TABLE `card_subcategories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT для таблицы `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `offices`
--
ALTER TABLE `offices`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `offices_partitions`
--
ALTER TABLE `offices_partitions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `social_networks`
--
ALTER TABLE `social_networks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `users_details`
--
ALTER TABLE `users_details`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `users_phones`
--
ALTER TABLE `users_phones`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `users_social_networks`
--
ALTER TABLE `users_social_networks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `agencies`
--
ALTER TABLE `agencies`
  ADD CONSTRAINT `agencies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cards_contacts_phones`
--
ALTER TABLE `cards_contacts_phones`
  ADD CONSTRAINT `cards_contacts_phones_cards_contacts_id_foreign` FOREIGN KEY (`cards_contacts_id`) REFERENCES `cards_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cards_files`
--
ALTER TABLE `cards_files`
  ADD CONSTRAINT `cards_files_card_id_foreign` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `offices`
--
ALTER TABLE `offices`
  ADD CONSTRAINT `offices_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `offices_partitions`
--
ALTER TABLE `offices_partitions`
  ADD CONSTRAINT `offices_partitions_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offices_partitions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_details`
--
ALTER TABLE `users_details`
  ADD CONSTRAINT `users_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_phones`
--
ALTER TABLE `users_phones`
  ADD CONSTRAINT `users_phones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_social_networks`
--
ALTER TABLE `users_social_networks`
  ADD CONSTRAINT `users_social_networks_social_network_id_foreign` FOREIGN KEY (`social_network_id`) REFERENCES `social_networks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_social_networks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
