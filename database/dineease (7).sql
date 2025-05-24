-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 07:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dineease`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `admin_create_currency` (IN `p_name` LONGTEXT, IN `p_currency_code` VARCHAR(255), IN `p_exchange_rate` DECIMAL(10,6), IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_currency_symbol` LONGTEXT, IN `p_admin_users_id` VARCHAR(255))   BEGIN
INSERT INTO currencies(company_id, currency_code, name, exchange_rate, currency_symbol, active, created_by) VALUES(
p_company_id,
p_currency_code,
p_name,
p_exchange_rate,
p_currency_symbol,
p_active,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
);
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_hotel` (IN `p_name` VARCHAR(255), IN `p_logo` LONGTEXT, IN `p_verification` TINYINT, IN `p_free_count` INT, IN `p_time_zone` VARCHAR(255), IN `p_plus_days_adjust` INT, IN `p_minus_days_adjust` INT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255), IN `p_restricted_restaurants` INT)   BEGIN
INSERT INTO hotels(name, logo_url, verification_type, free_count, time_zone, plus_days_adjust, minus_days_adjust, active, company_id, created_by, restricted_restaurants) VALUES 
(
p_name,
p_logo,
p_verification,
p_free_count,
p_time_zone,
p_plus_days_adjust,
p_minus_days_adjust,
p_active,
p_company_id,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id),
p_restricted_restaurants
);
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_meal_type` (IN `p_label` LONGTEXT, IN `p_en` LONGTEXT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255))   BEGIN

INSERT INTO meal_types(label, active, company_id, created_by) VALUES 
(
p_label,
p_active,
p_company_id,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
);

INSERT INTO meal_types_translation(meal_types_id, language_code, name)
VALUES (
last_insert_id(),
'en',
p_en
);
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_menu_links` (IN `p_label` VARCHAR(255), IN `p_menu` LONGTEXT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255))   BEGIN

INSERT INTO  menu_links(company_id, menu_url, label, created_by) VALUES 
(
p_company_id,
p_menu,
p_label,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
);
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_restaurant` (IN `p_name` VARCHAR(255), IN `p_logo` LONGTEXT, IN `p_hotel` INT, IN `p_capacity` INT, IN `p_always_paid_free` TINYINT, IN `p_cuisine` LONGTEXT, IN `p_about` LONGTEXT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255))   BEGIN
INSERT INTO restaurants(name, capacity, active, hotel_id, logo_url, always_paid_free, company_id, created_by) VALUES
(
p_name,
p_capacity,
p_active,
p_hotel,
p_logo,
p_always_paid_free,
p_company_id,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
);
INSERT INTO restaurants_translations(restaurants_id, language_code, cuisine, about) VALUES 
(
last_insert_id(),
'en',
p_cuisine,
p_about
);

SELECT last_insert_id();

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_translation_for_meal_types` (IN `p_language_code` VARCHAR(3), IN `p_name` VARCHAR(255), IN `p_meal_types_id` INT, IN `p_company_id` INT, IN `p_admin_users_id` INT)   BEGIN

DECLARE language_code_exist VARCHAR(255);
SELECT language_code INTO language_code_exist FROM meal_types_translation WHERE 
meal_types_id = p_meal_types_id AND 
language_code = p_language_code;

IF language_code_exist IS NOT NULL THEN 

UPDATE meal_types_translation
 SET name = p_name
WHERE meal_types_id = p_meal_types_id AND 
language_code = p_language_code;

UPDATE meal_types 
 SET updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE meal_types_id = p_meal_types_id AND company_id = p_company_id;

ELSE

INSERT INTO meal_types_translation(meal_types_id, name, language_code)
VALUES(p_meal_types_id, p_name, p_language_code);

UPDATE meal_types 
 SET updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE meal_types_id = p_meal_types_id AND company_id = p_company_id;

END IF;
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_create_translation_for_restaurants` (IN `p_language_code` VARCHAR(3), IN `p_cuisine` VARCHAR(255), IN `p_about` LONGTEXT, IN `p_restaurants_id` INT, IN `p_company_id` INT, IN `p_admin_users_id` INT)   BEGIN

DECLARE language_code_exist VARCHAR(255);
SELECT language_code INTO language_code_exist FROM restaurants_translations WHERE 
restaurants_id = p_restaurants_id AND 
language_code = p_language_code;


IF language_code_exist IS NOT NULL THEN 

UPDATE restaurants_translations
 SET cuisine = p_cuisine,
 about = p_about
WHERE restaurants_id = p_restaurants_id AND 
language_code = p_language_code;

UPDATE restaurants 
 SET updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE restaurants_id = p_restaurants_id AND company_id = p_company_id;

ELSE

INSERT INTO restaurants_translations(restaurants_id, cuisine, about , language_code)
VALUES(p_restaurants_id, p_cuisine, p_about , p_language_code);

UPDATE restaurants 
 SET updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE restaurants_id = p_restaurants_id AND company_id = p_company_id;
END IF;
SELECT last_insert_id();

END$$

CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `admin_edit_currency` (IN `p_name` LONGTEXT, IN `p_currency_code` VARCHAR(255), IN `p_exchange_rate` DECIMAL(10,6), IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_currency_symbol` LONGTEXT, IN `p_admin_users_id` VARCHAR(255), IN `p_currencies_id` INT)   BEGIN
UPDATE currencies SET 
currency_code = p_currency_code,
name = p_name,
exchange_rate = p_exchange_rate,
currency_symbol = p_currency_symbol,
active = p_active,
updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE company_id = p_company_id AND currencies_id = p_currencies_id; 
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `admin_edit_hotel` (IN `p_name` VARCHAR(255), IN `p_logo` LONGTEXT, IN `p_verification` TINYINT, IN `p_free_count` INT, IN `p_time_zone` VARCHAR(255), IN `p_plus_days_adjust` INT, IN `p_minus_days_adjust` INT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255), IN `p_restricted_restaurants` INT, IN `p_hotel_id` INT)   BEGIN

UPDATE hotels SET 
name = p_name,
verification_type = p_verification,
free_count = p_free_count,
time_zone = p_time_zone,
plus_days_adjust = p_plus_days_adjust,
minus_days_adjust = p_minus_days_adjust,
active = p_active,
restricted_restaurants = p_restricted_restaurants,
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id),
updated_at = NOW()
WHERE hotel_id = p_hotel_id AND company_id = p_company_id;

IF p_logo IS NOT NULL THEN
    UPDATE hotels SET logo_url = p_logo WHERE hotel_id = p_hotel_id AND company_id = p_company_id;
END IF;
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_edit_meal_type` (IN `p_label` LONGTEXT, IN `p_en` LONGTEXT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255), IN `p_meal_types_id` INT)   BEGIN

UPDATE meal_types SET
label = p_label,
active = p_active,
updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE company_id = p_company_id AND meal_types_id = p_meal_types_id; 

UPDATE meal_types_translation SET 
name = p_en
WHERE meal_types_id = p_meal_types_id and language_code = "en";
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_edit_menu_links` (IN `p_menu_links_id` INT, IN `p_menu` LONGTEXT, IN `p_label` VARCHAR(255), IN `p_admin_users_id` VARCHAR(255))   BEGIN
UPDATE menu_links SET 
  label = p_label,
  updated_at = NOW(),
  updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE menu_links_id = p_menu_links_id;
IF p_menu IS NOT NULL THEN
    UPDATE menu_links SET MENU_URL = p_menu WHERE menu_links_id = p_menu_links_id;
END IF;
SELECT last_insert_id();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_edit_restaurant` (IN `p_name` VARCHAR(255), IN `p_logo` LONGTEXT, IN `p_hotel` INT, IN `p_capacity` INT, IN `p_always_paid_free` TINYINT, IN `p_cuisine` VARCHAR(255), IN `p_about` LONGTEXT, IN `p_active` TINYINT, IN `p_restaurants_id` INT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255))   BEGIN

UPDATE restaurants SET 
  name = p_name,
  hotel_id = p_hotel,
  capacity = p_capacity,
  always_paid_free = p_always_paid_free,
  active = p_active,
  updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id),
  updated_at = NOW()
WHERE restaurants_id = p_restaurants_id AND company_id = p_company_id;  

IF p_logo IS NOT NULL THEN
    UPDATE restaurants SET logo_url = p_logo WHERE restaurants_id = p_restaurants_id AND company_id = p_company_id;
END IF;

UPDATE restaurants_translations SET
  cuisine = p_cuisine,
  about = p_about 
WHERE restaurants_id = p_restaurants_id AND language_code = 'en';

SELECT last_insert_id();

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_edit_translation_for_meal_types` (IN `p_name` VARCHAR(255), IN `p_meal_types_id` INT, IN `p_company_id` INT, IN `p_admin_users_id` INT, IN `p_meal_types_translation_id` INT)   BEGIN

UPDATE meal_types_translation SET 
 name = p_name 
WHERE meal_types_translation_id = p_meal_types_translation_id;
 
UPDATE meal_types 
 SET updated_at = NOW(),
updated_by = (SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
WHERE meal_types_id = p_meal_types_id AND company_id = p_company_id;

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `admin_get_currencies` (IN `p_company_id` VARCHAR(255))   BEGIN
SELECT * FROM currencies WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `admin_get_hotels` (IN `p_company_id` VARCHAR(255))   BEGIN
SELECT * FROM hotels WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_hotel_name_id` (IN `p_company_id` INT)   BEGIN
SELECT name, hotel_id FROM hotels WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `admin_get_meal_types` (IN `p_company_id` VARCHAR(255))   BEGIN
 SELECT 
    mt.*, 
    CASE 
    WHEN COUNT(mtt.language_code) = 0 THEN NULL
   ELSE CONCAT(
  '[', 
  GROUP_CONCAT(
    CONCAT(
      '{"meal_types_translation_id":"', COALESCE(mtt.meal_types_translation_id, ''), '",',
      '"language_code":"', COALESCE(mtt.language_code, ''), '",',
      '"name":"', REPLACE(COALESCE(mtt.name, ''), '"', '\\"'), '"}'
    ) 
    SEPARATOR ','
  ),
  ']'
)
  END AS translations
    FROM 
        meal_types mt
    LEFT JOIN 
        meal_types_translation mtt
        ON mt.meal_types_id = mtt.meal_types_id
    WHERE
    mt.company_id = p_company_id 
    GROUP BY mt.meal_types_id
ORDER BY mt.meal_types_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_menu_links` (IN `p_company_id` INT)   BEGIN
SELECT menu_links_id, label, created_at, created_by, updated_at, updated_by FROM menu_links 
WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_menu_links_url` (IN `p_menu_links_id` INT)   BEGIN
SELECT menu_url FROM menu_links WHERE menu_links_id = p_menu_links_id;
END$$

CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `admin_get_restaurants` (IN `p_company_id` VARCHAR(255))   BEGIN
 SELECT 
    r.restaurants_id, 
    r.name,
    r.capacity,
    r.created_at,
    r.active,
    r.logo_url,
    r.always_paid_free,
    r.created_by,
    r.updated_at,
    r.updated_by,
    (SELECT name FROM hotels h WHERE h.hotel_id = r.hotel_id) as hotel,
    CASE 
    WHEN COUNT(rt.language_code) = 0 THEN NULL
   ELSE CONCAT(
  '[', 
  GROUP_CONCAT(
    CONCAT(
      '{"restaurant_translations_id":"', COALESCE(rt.restaurant_translations_id, ''), '",',
      '"language_code":"', COALESCE(rt.language_code, ''), '",',
      '"about":"', COALESCE(rt.about, ''), '",',
      '"cuisine":"', REPLACE(COALESCE(rt.cuisine, ''), '"', '\\"'), '"}'
    ) 
    SEPARATOR ','
  ),
  ']'
)
  END AS translations
    FROM 
        restaurants r
    LEFT JOIN 
        restaurants_translations rt
        ON r.restaurants_id = rt.restaurants_id
    WHERE
    r.company_id = p_company_id 
    GROUP BY r.restaurants_id
ORDER BY r.restaurants_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_restaurants_hotels` (IN `p_company_id` INT)   BEGIN
SELECT
 r.name,
 r.restaurants_id,
 r.hotel_id,
 (SELECT name FROM hotels WHERE hotel_id = r.hotel_id) 
     FROM restaurants r WHERE r.company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_restaurant_name_id` (IN `p_company_id` INT)   BEGIN
SELECT
 r.name,
 r.restaurants_id,
 r.hotel_id,
 (SELECT name FROM hotels WHERE hotel_id = r.hotel_id) 
     FROM restaurants r WHERE r.company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_get_restaurant_times` (IN `p_company_id` INT, IN `p_restaurant_id` INT, IN `p_from` VARCHAR(255), IN `p_to` VARCHAR(255))   BEGIN

SELECT 
restaurant_pricing_times_id,
(SELECT name FROM restaurants WHERE restaurants_id = rpt.restaurant_id) as restaurant,
restaurant_id,
(SELECT name FROM hotels WHERE hotel_id = rpt.hotel_id) as hotel,
hotel_id,
(SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id) as tz,
concat(rpt.year, '-', rpt.month, '-', rpt.day) as date,
DATE_FORMAT(CONVERT_TZ(time, '+00:00', (SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id)), '%H:%i:%s') AS time,
rpt.per_person,
rpt.reservation_by_room,
(SELECT label FROM meal_types WHERE meal_types_id = rpt.meal_type) as meal_type,
rpt.meal_type as meal_types_id,
rpt.extra_seats,
(SELECT concat(currency_code, '|-|', currency_symbol, '|-|', exchange_rate) FROM  currencies WHERE currencies_id = rpt.currency_id) as currency,
rpt.currency_id,
rpt.price,
created_at,
created_by,
updated_at,
updated_by,
(SELECT label FROM menu_links WHERE menu_url = rpt.menu_url) as menu_link
FROM restaurant_pricing_times rpt WHERE 
rpt.company_id = p_company_id AND restaurant_id = p_restaurant_id AND
(p_from IS NULL OR DATE(concat(rpt.year, '-', rpt.month, '-', rpt.day)) >= DATE(p_from)) AND
(p_to IS NULL OR DATE(concat(rpt.year, '-', rpt.month, '-', rpt.day)) <= DATE(p_to));

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `admin_login` (IN `p_id` LONGTEXT, IN `p_company_id` INT)   BEGIN
    SELECT 
        au.user_name, 
        au.password, 
        au.admin_users_id,
        ap.* 
    FROM 
        admin_users au
    JOIN 
        admin_privileges ap ON au.admin_users_id = ap.admin_users_id
    WHERE 
        (au.user_name = p_id OR au.email = p_id OR au.phone = p_id) 
        AND au.company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `admin_view_menu` (IN `p_menu_links_id` INT, IN `p_company_id` INT)   BEGIN
SELECT menu_url FROM menu_links WHERE 
menu_links_id = p_menu_links_id AND
company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `check_paid_info` (IN `p_hotel_id` VARCHAR(255), IN `p_room_number` VARCHAR(255), IN `p_guest_reservations_id` INT, IN `p_restaurant_id` VARCHAR(255), IN `p_company_id` VARCHAR(255))   back_door:BEGIN

DECLARE s_hotel_free_count TINYINT;
DECLARE s_restricted_restaurants TINYINT;
DECLARE s_restaurant_hotel_id TINYINT;
DECLARE s_always_paid_free TINYINT;
DECLARE s_count INT;
DECLARE s_board_free_count INT;
DECLARE s_board_type INT;
DECLARE s_pax INT;

SELECT hotel_id, always_paid_free INTO s_restaurant_hotel_id, s_always_paid_free FROM restaurants WHERE restaurants_id = p_restaurant_id;
SELECT restricted_restaurants, free_count INTO s_restricted_restaurants, s_hotel_free_count FROM hotels WHERE hotel_id = p_hotel_id;

IF s_always_paid_free = 1 THEN 
    SELECT 'alwaysPaid' as result;
    leave back_door;
ELSEIF s_always_paid_free = 0 THEN 
     SELECT 'alwaysFree' as result;
     leave back_door;
END IF;

IF (s_restricted_restaurants = 1) AND (s_restaurant_hotel_id != p_hotel_id) THEN 
    SELECT 'restrictrd' as result;
    leave back_door;
ELSEIF (s_restricted_restaurants = 2) AND (s_restaurant_hotel_id != p_hotel_id) THEN 
    SELECT 'crossHotelPaid' as result;
    leave back_door;
END IF;

SELECT COUNT(DISTINCT day,time)
 INTO s_count
 FROM reservations WHERE 
 canceled = 0 
 AND guest_reservations_id = p_guest_reservations_id;

SELECT board_type, pax INTO s_board_type, s_pax FROM guest_reservations WHERE guest_reservations_id = p_guest_reservations_id;
 
SELECT free_count INTO s_board_free_count FROM board_type_rules WHERE 
    company_id = p_company_id 
    AND hotel_id = p_hotel_id
    AND board_id = s_board_type;

IF s_board_free_count IS NULL THEN 
    SELECT 'success' as result, GREATEST((s_hotel_free_count - s_count), 0) as remaining;
ELSE
    SELECT 'success' as result, GREATEST((s_board_free_count - s_count), 0) as remaining;
END IF;

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `check_room` (IN `p_room_number` VARCHAR(255), IN `p_hotel_id` VARCHAR(255), IN `p_company_id` VARCHAR(255))   BEGIN

DECLARE s_time_zone VARCHAR(255);
SELECT time_zone INTO s_time_zone  FROM hotels WHERE hotel_id = p_hotel_id;

SELECT 
 room_number
 FROM guest_reservations WHERE
 (DATE(CONVERT_TZ(departure_date, '+00:00', s_time_zone)) >= DATE(CONVERT_TZ(NOW(), '+00:00', s_time_zone))) AND
 company_id = p_company_id
 AND (room_number = p_room_number) AND
 (hotel_id = p_hotel_id);
 
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `create_hotel` (IN `p_name` VARCHAR(255), IN `p_logo` LONGBLOB, IN `p_verification` TINYINT, IN `p_free_count` INT, IN `p_time_zone` VARCHAR(255), IN `p_plus_days_adjust` INT, IN `p_minus_days_adjust` INT, IN `p_active` TINYINT, IN `p_company_id` INT, IN `p_admin_users_id` VARCHAR(255))   BEGIN
INSERT INTO hotels(name, logo, verification_type, free_count, time_zone, plus_days_adjust, minus_days_adjust, active, company_id, created_by) VALUES 
(
p_name,
p_logo,
p_verification,
p_free_count,
p_time_zone,
p_plus_days_adjust,
p_minus_days_adjust,
p_active,
p_company_id,
(SELECT CONCAT(user_name, ' (' , display_name , ')') FROM admin_users user WHERE admin_users_id = p_admin_users_id)
);
SELECT last_insert_id();
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `create_new_log` (IN `p_level` VARCHAR(255), IN `p_message` LONGTEXT, IN `p_metadata` LONGTEXT, IN `p_timestamp` TIMESTAMP, IN `p_token` VARCHAR(255))   BEGIN
INSERT INTO logs(level, message, metadata, time_stamp,token) VALUES (p_level,p_message,p_metadata,p_timestamp,p_token);

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `create_reservation` (IN `p_guest_reservations_id` INT, IN `p_room_number` VARCHAR(255), IN `p_pax` INT, IN `p_names` LONGTEXT, IN `p_restaurant_id` INT, IN `p_desired_date` VARCHAR(255), IN `p_time` VARCHAR(255), IN `p_company_id` INT, IN `p_hotel_id` INT, IN `p_created_by` LONGTEXT, IN `p_currencies_id` INT, IN `p_paid` TINYINT, IN `p_taxes` LONGTEXT, IN `p_discounts` LONGTEXT, IN `p_per_person` TINYINT, IN `p_reservation_by_room` TINYINT, IN `p_exchange_rate` DECIMAL(10,6), IN `p_original_price` DECIMAL(10,5), IN `p_sub_total` DECIMAL(10,5), IN `p_after_tax` DECIMAL(10,5), IN `p_total_ammount_due` DECIMAL(10,5), IN `p_time_zone` VARCHAR(45), IN `p_meal_types_id` INT, IN `p_menus_id` INT)   back_door:BEGIN

DECLARE done INT DEFAULT 0;
DECLARE s_names LONGTEXT;
DECLARE s_pax INT;
DECLARE cur CURSOR FOR 
    SELECT names, pax 
    FROM reservations
    WHERE guest_reservations_id = p_guest_reservations_id 
    AND room_number = p_room_number
    AND day = p_desired_date
    AND time = p_time
    AND company_id = p_company_id
    AND guest_hotel_id = p_hotel_id
    AND restaurant_id = p_restaurant_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
OPEN cur;
read_loop: LOOP
    FETCH cur INTO s_names, s_pax;
    IF done THEN
        LEAVE read_loop;
    END IF;
    SET @counter = 1;
    WHILE @counter <= s_pax DO
        SET @current_name = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(s_names, ' |-| ', @counter), ' |-| ', -1));
        IF p_names LIKE CONCAT('%', @current_name, '%') THEN
            SELECT 'alreadyReserved' as result;    
            LEAVE back_door;
        END IF;
        SET @counter = @counter + 1;
    END WHILE;
END LOOP;
CLOSE cur;

INSERT INTO reservations(
    guest_reservations_id, 
    room_number, 
    pax, 
    names, 
    restaurant_id, 
    day,
    time, 
    company_id, 
    guest_hotel_id, 
    restaurant_hotel_id,
    created_by, 
    currencies_id,
    paid,
    taxes,
    discounts,
    per_person,
    reservation_by_room,
    always_paid_free,
    exchange_rate,
    original_price,
    sub_total,
    after_tax,
    total_ammount_due,
    time_zone,
    meal_types_id,
    menus_id
) VALUES (
    p_guest_reservations_id, 
    p_room_number, 
    p_pax, 
    p_names, 
    p_restaurant_id, 
    p_desired_date,
    p_time, 
    p_company_id, 
    p_hotel_id, 
    (SELECT hotel_id FROM restaurants WHERE restaurants_id = p_restaurant_id),
    p_created_by, 
    p_currencies_id,
    p_paid,
    p_taxes,
    p_discounts,
	p_per_person,
    p_reservation_by_room,
    (SELECT always_paid_free FROM restaurants WHERE restaurants_id = p_restaurant_id),
    p_exchange_rate,
    p_original_price,
    p_sub_total,
    p_after_tax,
    p_total_ammount_due,
    p_time_zone,
    p_meal_types_id,
    p_menus_id
);

SET @last_id = LAST_INSERT_ID();
SET @sha = SHA2(@last_id,256);
UPDATE reservations
    SET qrcode = @sha
    WHERE reservations_id = @last_id;
SELECT @sha as result;

SELECT 1;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_admin_user` (IN `p_admins_users_id` INT)   BEGIN
SELECT user_name, email, phone, display_name, admin, created_at FROM admin_users WHERE admin_users_id = p_admins_users_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_arrival_departure` (IN `p_guest_reservations_id` VARCHAR(255))   BEGIN
SELECT 
       DATE_FORMAT(arrival_date, '%Y-%m-%d') AS arrival_date,
       DATE_FORMAT(departure_date, '%Y-%m-%d') AS departure_date
 FROM guest_reservations WHERE guest_reservations_id = p_guest_reservations_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_available_date` (IN `p_restaurant_id` VARCHAR(255), IN `p_desired_date` VARCHAR(255), IN `p_company_id` VARCHAR(255), IN `p_language_code` VARCHAR(45), IN `p_paid` TINYINT)   back_door:BEGIN
    SELECT 
        DATE_FORMAT(CONVERT_TZ(time, '+00:00', (SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id)), '%H:%i:%s') AS time,
        time as original_time,
        (SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id) as time_zone,
        price,
        per_person,
        (SELECT currency_code FROM currencies fc WHERE fc.currencies_id = currency_id) AS currency,
        (SELECT exchange_rate FROM currencies fc WHERE fc.currencies_id = currency_id) AS exchange_rate,
        (SELECT currencies_id FROM currencies fc WHERE fc.currencies_id = currency_id) AS currencies_id,
        (SELECT get_available(p_company_id, rpt.hotel_id, p_restaurant_id, p_desired_date, time)) AS remaining,
        rpt.restaurant_pricing_times_id,
        (SELECT get_meal_type(rpt.meal_type, p_language_code, p_company_id)) as meal_type_name,
        rpt.meal_type, 
        reservation_by_room,
        menus_id,
        IF(p_paid = 1, (SELECT get_taxes_for_pricing_time(rpt.restaurant_pricing_times_id, p_language_code)), NULL) as taxes,
        IF(p_paid = 1, (SELECT get_discounts_for_pricing_time(rpt.restaurant_pricing_times_id, p_language_code)), NULL) as discounts
    FROM 
        restaurant_pricing_times rpt
    WHERE 
        rpt.restaurant_id = p_restaurant_id 
        AND CONCAT(rpt.year, '-', rpt.month, '-', rpt.day) = p_desired_date 
        AND rpt.company_id = p_company_id
        AND CONVERT_TZ(STR_TO_DATE(CONCAT(rpt.year, '-', rpt.month, '-', rpt.day, ' ', rpt.time), '%Y-%m-%d %H:%i:%s'), '+00:00', (SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id)) > CONVERT_TZ(NOW(), '+00:00', (SELECT time_zone FROM hotels WHERE hotel_id = rpt.hotel_id))
    ORDER BY 
        rpt.time ASC;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_company` (IN `p_company_uuid` LONGTEXT)   BEGIN
SELECT company_id, company_name, logo_url FROM companies WHERE company_uuid = p_company_uuid LIMIT 1;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_confirm_qr` (IN `p_qrcode` LONGTEXT, IN `p_language_code` VARCHAR(45))   BEGIN

SELECT 
rs.room_number,
rs.pax,
(SELECT name FROM hotels WHERE hotel_id = rs.restaurant_hotel_id) as restaurant_hotel_name,
(SELECT name FROM hotels WHERE hotel_id = rs.guest_hotel_id) as guest_hotel_name,
names,
(SELECT name FROM restaurants WHERE restaurants_id = rs.restaurant_id) as restaurant,
DATE_FORMAT(day, '%Y-%m-%d') as day,
DATE_FORMAT(CONVERT_TZ(time, '+00:00', (SELECT time_zone FROM hotels WHERE hotel_id = rs.restaurant_hotel_id)),'%H:%i:%s') as time,
DATE_FORMAT(created_at,'%Y-%m-%d %H:%i:%s') AS created_at,
(SELECT time_zone FROM hotels WHERE hotel_id = rs.restaurant_hotel_id) as tz,
paid,
(SELECT currency_code FROM currencies WHERE currencies_id = rs.currencies_id) as currency,
(SELECT company_name FROM companies WHERE company_id = rs.company_id) as company_name,
(SELECT logo_url FROM companies WHERE company_id = rs.company_id) as logo_url,
price,
original_price,
sub_total,
after_tax,
total_ammount_due,
taxes,
discounts,
(SELECT get_meal_type(meal_types_id, p_language_code, company_id)) as meal_type_name,
menus_id
FROM reservations rs WHERE qrcode = p_qrcode;

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_hotels` (IN `p_company_id` VARCHAR(255), IN `p_active` TINYINT)   BEGIN
SELECT * FROM hotels WHERE company_id = p_company_id AND active = p_active;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `get_menu` (IN `p_language_code` VARCHAR(45), IN `p_company_id` INT, IN `p_restaurant_pricing_times_id` INT)   BEGIN
    SELECT 
        mi.menus_items_id,
        it.name AS item_name,
        it.description AS item_description,
        mc_trans.name AS category_name,
        mc.menu_categories_id AS categories_id,
        mc.background_url AS menu_categories_background_url,
        ms.background_url AS menu_subcategories_background_url,
        ms_trans.name AS subcategory_name,
        i.items_id AS items_id,
        ms.menu_subcategories_id AS subcategories_id,
        mi.price,
        c.currency_code,
        (SELECT calculate_price FROM restaurant_pricing_times WHERE restaurant_pricing_times_id = p_restaurant_pricing_times_id) AS calculate_price
    FROM menus_items mi
    JOIN menus m ON mi.menus_id = m.menus_id AND m.company_id = p_company_id
    JOIN items i ON mi.items_id = i.items_id
    JOIN items_translation it ON i.items_id = it.items_id 
        AND it.language_code = p_language_code
    JOIN menu_categories mc ON i.menu_categories_id = mc.menu_categories_id 
        AND mc.company_id = p_company_id
    JOIN menu_categories_translation mc_trans ON mc.menu_categories_id = mc_trans.menu_categories_id 
        AND mc_trans.language_code = p_language_code
    LEFT JOIN menu_subcategories ms ON i.menu_subcategories_id = ms.menu_subcategories_id 
        AND ms.company_id = p_company_id
    LEFT JOIN menu_subcategories_translation ms_trans ON ms.menu_subcategories_id = ms_trans.menu_subcategories_id 
        AND ms_trans.language_code = p_language_code
    JOIN currencies c ON mi.currencies_id = c.currencies_id
    WHERE mi.menus_id = (SELECT menus_id FROM restaurant_pricing_times WHERE restaurant_pricing_times_id = p_restaurant_pricing_times_id);
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `get_menus_urls_or_viewer` (IN `start_date` DATE, IN `end_date` DATE, IN `p_restaurant_id` INT, IN `p_company_id` INT, IN `p_language_code` VARCHAR(10))   BEGIN

 DECLARE current__date DATE; 
 DECLARE menu_url_exists INT;
 
 CREATE TEMPORARY TABLE IF NOT EXISTS temp_menu_url_check (
    ref INT,
	date VARCHAR(255),
    time TIME,
    timezone VARCHAR(255),
    meal_type VARCHAR(255),
    is_menu_viewer TINYINT
 );
 
 
 SET current__date = start_date;

    
    WHILE current__date <= end_date DO
        
        INSERT INTO temp_menu_url_check (ref, date, time, timezone, meal_type, is_menu_viewer)
        SELECT 
            rpt.restaurant_pricing_times_id,
            CONCAT(rpt.year, '-', rpt.month, '-', rpt.day) AS date,
            DATE_FORMAT(CONVERT_TZ(rpt.time, '+00:00', (SELECT time_zone FROM hotels h WHERE h.hotel_id = rpt.hotel_id)),'%H:%i:%s') as time,
            (SELECT time_zone FROM hotels h WHERE h.hotel_id = rpt.hotel_id) as timezone,
            (SELECT get_meal_type(rpt.meal_type, p_language_code, p_company_id)) as meal_type,
            IFNULL(rpt.menus_id IS NOT NULL, FALSE) as is_menu_viewer
        FROM restaurant_pricing_times rpt
        WHERE rpt.restaurant_id = p_restaurant_id
          AND rpt.company_id = p_company_id
          AND rpt.year = YEAR(current__date)
          AND rpt.month = MONTH(current__date)
          AND rpt.day = DAY(current__date)
          AND rpt.menu_url IS NOT NULL;

        
        SET current__date = DATE_ADD(current__date, INTERVAL 1 DAY);
    END WHILE;

    
    SELECT * FROM temp_menu_url_check;

    
    DROP TEMPORARY TABLE IF EXISTS temp_menu_url_check;

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_menu_pdf_url` (IN `p_reference_id` VARCHAR(255))   BEGIN
    SELECT menu_url FROM restaurant_pricing_times  WHERE restaurant_pricing_times_id = p_reference_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `get_menu_urls_period` (IN `start_date` DATE, IN `end_date` DATE, IN `p_restaurant_id` INT, IN `p_company_id` INT, IN `p_language_code` VARCHAR(10))   BEGIN

 DECLARE current__date DATE; 
 DECLARE menu_url_exists INT;
 
 CREATE TEMPORARY TABLE IF NOT EXISTS temp_menu_url_check (
    ref INT,
	date VARCHAR(255),
    time TIME,
    timezone VARCHAR(255),
    meal_type VARCHAR(255)
 );
 
 
 SET current__date = start_date;

    
    WHILE current__date <= end_date DO
        
        INSERT INTO temp_menu_url_check (ref, date, time, timezone, meal_type)
        SELECT 
            rpt.restaurant_pricing_times_id,
            CONCAT(rpt.year, '-', rpt.month, '-', rpt.day) AS date,
            DATE_FORMAT(CONVERT_TZ(rpt.time, '+00:00', (SELECT time_zone FROM hotels h WHERE h.hotel_id = rpt.hotel_id)),'%H:%i:%s') as time,
            (SELECT time_zone FROM hotels h WHERE h.hotel_id = rpt.hotel_id) as timezone,
            (SELECT mtt.name AS meal_name FROM meal_types mt JOIN meal_types_translation mtt ON mt.meal_types_id = mtt.meal_types_id WHERE 
            mt.meal_types_id = rpt.meal_type 
            AND mt.company_id = p_company_id
            AND mtt.language_code = p_language_code LIMIT 1) as meal_type
        FROM restaurant_pricing_times rpt
        WHERE rpt.restaurant_id = p_restaurant_id
          AND rpt.company_id = p_company_id
          AND rpt.year = YEAR(current__date)
          AND rpt.month = MONTH(current__date)
          AND rpt.day = DAY(current__date)
          AND rpt.menu_url IS NOT NULL;

        
        SET current__date = DATE_ADD(current__date, INTERVAL 1 DAY);
    END WHILE;

    
    SELECT * FROM temp_menu_url_check;

    
    DROP TEMPORARY TABLE IF EXISTS temp_menu_url_check;

END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_names` (IN `p_guest_reservations_id` VARCHAR(255))   BEGIN
SELECT GROUP_CONCAT(guest_name SEPARATOR ' |-| ') AS names FROM guest_details WHERE guest_reservations_id = p_guest_reservations_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_pdf` (IN `p_restaurants_id` VARCHAR(255))   BEGIN
SELECT menu_pdf FROM restaurants  WHERE restaurants_id = p_restaurants_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_pick_dates` (IN `p_guest_reservations_id` VARCHAR(255), IN `p_restaurants_id` VARCHAR(255), IN `p_company_id` VARCHAR(255))   BEGIN

DECLARE s_time_zone LONGTEXT;
DECLARE s_hotel_id INT;

SELECT hotel_id, time_zone INTO s_hotel_id, s_time_zone FROM hotels WHERE hotel_id = (SELECT hotel_id FROM restaurants where restaurants_id = p_restaurants_id) ;


SELECT 
	   DATE_FORMAT(CONVERT_TZ(DATE_ADD(NOW(), INTERVAL (SELECT plus_days_adjust FROM hotels WHERE hotel_id = s_hotel_id AND company_id = p_company_id) DAY),'+00:00', s_time_zone), '%Y-%m-%d') AS start_date,
	   DATE_FORMAT(CONVERT_TZ(DATE_SUB(departure_date, INTERVAL (SELECT minus_days_adjust FROM hotels WHERE hotel_id = s_hotel_id AND company_id = p_company_id) DAY),'+00:00', s_time_zone), '%Y-%m-%d') AS end_date,
       s_time_zone as tz
 FROM guest_reservations WHERE guest_reservations_id = p_guest_reservations_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `get_restaurants` (IN `p_company_id` VARCHAR(255), IN `p_active` TINYINT, IN `p_language` VARCHAR(2), IN `p_hotel_id` INT)   back_door:BEGIN

DECLARE restricted TINYINT;
SELECT restricted_restaurants INTO restricted FROM hotels WHERE hotel_id = p_hotel_id;

IF restricted = 1 THEN 

SELECT r.restaurants_id, r.name, rt.cuisine, rt.about, r.logo_url, r.capacity, r.hotel_id, r.always_paid_free, 
(SELECT name FROM hotels WHERE hotel_id = r.hotel_id) as hotel_name
FROM restaurants r
INNER JOIN restaurant_translations rt ON r.restaurants_id = rt.restaurants_id AND rt.language_code = p_language AND r.company_id = p_company_id AND r.active = p_active AND r.hotel_id = p_hotel_id;

ELSEIF restricted = 0 THEN 

SELECT r.restaurants_id, r.name, rt.cuisine, rt.about, r.logo_url, r.capacity, r.hotel_id, r.always_paid_free, 
(SELECT name FROM hotels WHERE hotel_id = r.hotel_id) as hotel_name
FROM restaurants r
INNER JOIN restaurant_translations rt ON r.restaurants_id = rt.restaurants_id AND rt.language_code = p_language AND  company_id = p_company_id AND active = p_active;

ELSEIF restricted = 2 THEN 

SELECT r.restaurants_id, r.name, rt.cuisine, rt.about, r.logo_url, r.capacity, r.hotel_id, r.always_paid_free, 
(SELECT name FROM hotels WHERE hotel_id = r.hotel_id) as hotel_name,
(SELECT restricted_restaurants FROM hotels WHERE hotel_id = p_hotel_id) as restricted_restaurants
FROM restaurants r
INNER JOIN restaurant_translations rt ON r.restaurants_id = rt.restaurants_id AND rt.language_code = p_language AND  company_id = p_company_id AND active = p_active;

END IF;


END$$

CREATE DEFINER=`root`@`%` PROCEDURE `get_statics` (IN `company_id` VARCHAR(255))   BEGIN
select 1;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `get_tax` (IN `p_taxes_id` INT, IN `p_language_code` VARCHAR(45))   BEGIN

 DECLARE v_default_language VARCHAR(45) DEFAULT 'en';

    SELECT 
        t.percentage,
        t.value,
        COALESCE(tt1.name, tt2.name) AS name, 
        COALESCE(tt1.description, tt2.description) AS description 
    FROM 
        taxes t
    LEFT JOIN 
        taxes_translation tt1
    ON 
        t.taxes_id = tt1.taxes_id
        AND tt1.language_code = p_language_code
    LEFT JOIN 
        taxes_translation tt2
    ON 
        t.taxes_id = tt2.taxes_id
        AND tt2.language_code = v_default_language
    WHERE 
        t.taxes_id = p_taxes_id
        AND t.active = 1 
    LIMIT 1; 
    
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `rows_arrival_departure` (IN `p_guest_reservations_id` VARCHAR(255))   BEGIN
SELECT arrival_date, departure_date FROM guest_reservations WHERE guest_reservations_id = p_guest_reservations_id;
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `save_user_changes` (IN `p_display_Name` VARCHAR(255), IN `p_phone` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_admin_users_id` VARCHAR(255))   BEGIN
UPDATE admin_users SET
email = p_email,
phone = p_phone,
display_name = p_display_Name WHERE admin_users_id = p_admin_users_id;
SELECT display_name, email, phone FROM admin_users WHERE admin_users_id = p_admin_users_id;
END$$

CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `TEMP_ONE` (IN `p_language_code` VARCHAR(45), IN `p_company_id` INT, IN `p_restaurant_pricing_times_id` INT)   BEGIN
SELECT
  *
 FROM menus_items mi 
  JOIN menus m ON mi.menus_id = m.menus_id AND m.company_id = p_company_id
 WHERE mi.menus_id = (SELECT menus_id from restaurant_pricing_times WHERE restaurant_pricing_times_id =  p_restaurant_pricing_times_id);
END$$

CREATE DEFINER=`hazem10`@`%` PROCEDURE `verify_room` (IN `p_room_number` VARCHAR(255), IN `p_hotel_id` VARCHAR(255), IN `p_date` DATE, IN `p_company_id` VARCHAR(255))   back_door:BEGIN
DECLARE flag TINYINT;
DECLARE hotel_free_count TINYINT;

DECLARE s_guest_reservations_id LONGTEXT;
DECLARE s_reservation_id LONGTEXT;
DECLARE s_board_type LONGTEXT;
DECLARE s_departure_date DATE;

DECLARE s_count INT;
DECLARE given_free_count INT;

DECLARE s_time_zone LONGTEXT;

SELECT time_zone INTO s_time_zone FROM hotels WHERE hotel_id = p_hotel_id;

SELECT verification_type, free_count into flag, hotel_free_count FROM hotels WHERE hotel_id = p_hotel_id;

SELECT guest_reservations_id, reservation_id, board_type, departure_date INTO s_guest_reservations_id, s_reservation_id, s_board_type, s_departure_date  FROM  guest_reservations WHERE 
     room_number = p_room_number
     AND guest_reservations.hotel_id = p_hotel_id
     AND ( CONVERT_TZ(guest_reservations.arrival_date,'+00:00',s_time_zone) <= CONVERT_TZ(NOW(),'+00:00',s_time_zone) ) 
     AND ( CONVERT_TZ(guest_reservations.departure_date,'+00:00',s_time_zone) >= CONVERT_TZ(NOW(),'+00:00',s_time_zone) )
     AND company_id = p_company_id LIMIT 1;

IF s_guest_reservations_id IS NULL THEN 
   SELECT 'noReservation' as result;
   leave back_door;
END IF;

SELECT COUNT(DISTINCT day,time)
 INTO s_count
 FROM reservations WHERE 
 canceled = 0 
 AND guest_reservations_id = s_guest_reservations_id;

SELECT free_count INTO given_free_count FROM board_type_rules WHERE 
company_id = p_company_id 
AND hotel_id = p_hotel_id
AND board_id = s_board_type;

IF flag THEN
    IF s_departure_date = p_date THEN 
		IF given_free_count IS NULL THEN 
            SELECT GREATEST((hotel_free_count - s_count), 0) as remaining, s_guest_reservations_id AS guest_reservations_id;
        ELSE
            SELECT GREATEST((given_free_count - s_count), 0) as remaining, s_guest_reservations_id AS guest_reservations_id;
        END IF;
    ELSE
        SELECT 'wrongDate' as result;
    END IF;
ELSE
    IF (SELECT birth_date FROM guest_details WHERE guest_reservations_id = s_guest_reservations_id AND birth_date = p_date) IS NULL THEN
        SELECT 'wrongDate' as result;
    ELSE
        IF given_free_count IS NULL THEN 
            SELECT GREATEST((hotel_free_count - s_count), 0) as remaining, s_guest_reservations_id AS guest_reservations_id;
        ELSE
            SELECT GREATEST((given_free_count - s_count), 0) as remaining, s_guest_reservations_id AS guest_reservations_id;
        END IF;
    END IF;
END IF;

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `verify_room_date` (IN `p_room_number` VARCHAR(255), IN `p_hotel_id` VARCHAR(255), IN `p_date` DATE, IN `p_company_id` VARCHAR(255))   back_door:BEGIN

DECLARE flag TINYINT;
DECLARE s_time_zone LONGTEXT;
DECLARE s_guest_reservations_id LONGTEXT;
DECLARE s_departure_date DATE;

SELECT verification_type INTO flag FROM hotels WHERE hotel_id = p_hotel_id;
SELECT time_zone INTO s_time_zone  FROM hotels WHERE hotel_id = p_hotel_id;

SELECT guest_reservations_id, departure_date INTO s_guest_reservations_id, s_departure_date FROM guest_reservations WHERE 
    room_number = p_room_number AND
    guest_reservations.hotel_id = p_hotel_id AND 
    ( CONVERT_TZ(guest_reservations.arrival_date,'+00:00',s_time_zone) <= CONVERT_TZ(NOW(),'+00:00',s_time_zone) ) AND 
    ( CONVERT_TZ(guest_reservations.departure_date,'+00:00',s_time_zone) >= CONVERT_TZ(NOW(),'+00:00',s_time_zone) ) AND
    company_id = p_company_id LIMIT 1;

IF s_guest_reservations_id IS NULL THEN 
   SELECT 'noReservation' as result;
   leave back_door;
END IF;

IF flag THEN
    IF s_departure_date = p_date THEN 
        SELECT 'success' as result, s_guest_reservations_id AS guest_reservations_id;
    ELSE
        SELECT 'wrongDate' as result;
    END IF;
ELSE
    IF p_date NOT IN (SELECT birth_date FROM guest_details WHERE guest_reservations_id = s_guest_reservations_id) THEN
        SELECT 'wrongDate' as result;
    ELSE
        SELECT 'success' as result, s_guest_reservations_id AS guest_reservations_id;
     END IF;
END IF;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`%` FUNCTION `get_available` (`p_company_id` VARCHAR(255), `p_hotel_id` VARCHAR(255), `p_restaurant_id` VARCHAR(255), `p_desired_date` VARCHAR(255), `p_time` VARCHAR(255)) RETURNS INT(11)  BEGIN
DECLARE s_time_zone LONGTEXT;
DECLARE s_capacity INT;
DECLARE s_extra_seats INT;
DECLARE remaining INT ;

SELECT time_zone INTO s_time_zone FROM hotels WHERE hotel_id = p_hotel_id;
SELECT capacity INTO s_capacity FROM restaurants WHERE restaurants_id = p_restaurant_id;
SELECT extra_seats INTO s_extra_seats FROM restaurant_pricing_times WHERE
    restaurant_id = p_restaurant_id 
    AND concat(year,'-',month,'-',day) = p_desired_date
    AND time = p_time
    AND company_id = p_company_id;

SELECT GREATEST( (s_capacity + s_extra_seats) - COALESCE(SUM(pax),0), 0) INTO remaining
FROM reservations rs WHERE 
    rs.restaurant_id = p_restaurant_id 
    AND rs.company_id = p_company_id 
    AND rs.restaurant_hotel_id = p_hotel_id 
    AND rs.day = p_desired_date 
    AND rs.time = p_time
    AND rs.canceled = 0 AND rs.ended = 0;
    
RETURN remaining;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `get_discounts_for_pricing_time` (`p_restaurant_pricing_times_id` INT, `p_language_code` VARCHAR(45)) RETURNS TEXT CHARSET utf8 COLLATE utf8_general_ci DETERMINISTIC READS SQL DATA BEGIN

DECLARE v_discounts TEXT;

 SELECT 
    CONCAT(
        '[',
        GROUP_CONCAT(
            CONCAT(
                '{',
                    '"percentage":"', d.percentage, '",',
                    '"value":"', d.value, '",',
                    '"name":"', REPLACE(COALESCE(dt1.name, dt2.name), '"', '\\"'), '",',
                    '"description":"', REPLACE(COALESCE(dt1.description, dt2.description), '"', '\\"'), '"',
                '}'
            )
        ),
        ']'
    ) INTO v_discounts
    FROM 
        restaurant_pricing_times_discounts rptd
    JOIN 
        discounts d ON rptd.discounts_id = d.discounts_id
    LEFT JOIN 
        discounts_translation dt1 ON d.discounts_id = dt1.discounts_id AND dt1.language_code = p_language_code
    LEFT JOIN 
        discounts_translation dt2 ON d.discounts_id = dt2.discounts_id AND dt2.language_code = 'en'
    WHERE 
        rptd.restaurant_pricing_times_id = p_restaurant_pricing_times_id
        AND d.active = 1; 

    
    RETURN v_discounts;

END$$

CREATE DEFINER=`root`@`%` FUNCTION `get_meal_type` (`p_meal_types_id` INT, `p_language_code` VARCHAR(45), `p_company_id` INT) RETURNS VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_default_language VARCHAR(45) DEFAULT 'en';
    DECLARE v_meal_name VARCHAR(255);
    SELECT 
        COALESCE(mtt.name, mtt1.name) INTO v_meal_name
    FROM 
        meal_types mt
    LEFT JOIN 
        meal_types_translation mtt
        ON mt.meal_types_id = mtt.meal_types_id
        AND mtt.language_code = p_language_code
    LEFT JOIN 
        meal_types_translation mtt1
        ON mt.meal_types_id = mtt1.meal_types_id
        AND mtt1.language_code = v_default_language
    WHERE 
        mt.meal_types_id = p_meal_types_id
        AND mt.company_id = p_company_id
        AND mt.active = 1
    LIMIT 1;
    RETURN v_meal_name;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `get_tax` (`p_company_id` INT, `p_language_code` VARCHAR(45)) RETURNS TEXT CHARSET utf8 COLLATE utf8_general_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_default_language VARCHAR(45) DEFAULT 'en';
    DECLARE v_taxes TEXT;

    
   SELECT
  CONCAT(
    '[',
    GROUP_CONCAT(
      CONCAT(
        '{',
          '"percentage":"', t.percentage, '",',
          '"value":"', t.value, '",',
          '"name":"', COALESCE(tt1.name, tt2.name), '",',
          '"description":"', COALESCE(tt1.description, tt2.description), '"',
        '}'
      )
    ),
    ']'
  )
INTO v_taxes
    FROM 
        taxes t
    LEFT JOIN taxes_translation tt1 ON t.taxes_id = tt1.taxes_id AND tt1.language_code = p_language_code
    LEFT JOIN taxes_translation tt2 ON t.taxes_id = tt2.taxes_id AND tt2.language_code = v_default_language
    WHERE 
        t.company_id = p_company_id 
        AND t.active = 1;

    
    RETURN v_taxes;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `get_taxes_for_pricing_time` (`p_restaurant_pricing_times_id` INT, `p_language_code` VARCHAR(45)) RETURNS TEXT CHARSET utf8 COLLATE utf8_general_ci DETERMINISTIC READS SQL DATA BEGIN
DECLARE v_taxes TEXT;

    
  SELECT 
    CONCAT(
        '[',
        GROUP_CONCAT(
            CONCAT(
                '{',
                    '"percentage":"', t.percentage, '",',
                    '"value":"', t.value, '",',
                    '"name":"', COALESCE(tt1.name, tt2.name), '",',
                    '"description":"', COALESCE(tt1.description, tt2.description), '"',
                '}'
            )
        ),
        ']'
    ) INTO v_taxes
    FROM 
        restaurant_pricing_times_taxes rptt
    JOIN 
        taxes t ON rptt.taxes_id = t.taxes_id
    LEFT JOIN 
        taxes_translation tt1 ON t.taxes_id = tt1.taxes_id AND tt1.language_code = p_language_code
    LEFT JOIN 
        taxes_translation tt2 ON t.taxes_id = tt2.taxes_id AND tt2.language_code = 'en'
    WHERE 
        rptt.restaurant_pricing_times_id = p_restaurant_pricing_times_id
        AND t.active = 1; 

    
    RETURN v_taxes;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_privileges`
--

CREATE TABLE `admin_privileges` (
  `admin_privileges_id` int(11) NOT NULL,
  `admin_users_id` int(11) DEFAULT NULL,
  `hotels_tab` tinyint(4) NOT NULL DEFAULT 0,
  `currencies_tab` tinyint(4) DEFAULT NULL,
  `meal_types_tab` tinyint(4) DEFAULT NULL,
  `restaurants_tab` tinyint(4) DEFAULT NULL,
  `restaurant_times_tab` tinyint(4) DEFAULT NULL,
  `menu_links_tab` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `session_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `expires` int(11) UNSIGNED NOT NULL,
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_users_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `admin` tinyint(4) DEFAULT 0,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `board_type_rules`
--

CREATE TABLE `board_type_rules` (
  `board_type_rules_id` int(11) NOT NULL,
  `board_name` varchar(255) DEFAULT NULL,
  `board_id` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `free_count` int(11) DEFAULT 0,
  `hotel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company_name` longtext DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `company_uuid` varchar(45) NOT NULL,
  `logo_url` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `currency_id`, `company_uuid`, `logo_url`) VALUES
(3, 'Test Hotel Group', 1, '6825c61d5f9dc', 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hotel-3377344_1280.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currencies_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(10,6) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(4) DEFAULT 1,
  `created_by` longtext DEFAULT NULL,
  `updated_by` longtext DEFAULT NULL,
  `currency_symbol` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currencies_id`, `company_id`, `currency_code`, `name`, `exchange_rate`, `created_at`, `updated_at`, `active`, `created_by`, `updated_by`, `currency_symbol`) VALUES
(9, 3, 'USD', 'US Dollar', 1.000000, '2025-05-15 10:46:53', NULL, 1, NULL, NULL, '$');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discounts_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `label` longtext DEFAULT NULL,
  `percentage` tinyint(4) DEFAULT 0,
  `value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` longtext DEFAULT NULL,
  `active` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts_translation`
--

CREATE TABLE `discounts_translation` (
  `discounts_translation_id` int(11) NOT NULL,
  `discounts_id` int(11) DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `name` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest_details`
--

CREATE TABLE `guest_details` (
  `guest_details_id` int(11) NOT NULL,
  `guest_reservations_id` int(11) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT 'NOT SPECIFIED',
  `guest_type` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `guest_details`
--

INSERT INTO `guest_details` (`guest_details_id`, `guest_reservations_id`, `guest_name`, `guest_type`, `birth_date`) VALUES
(6, 2, 'Guest Person1', 'main', '1974-05-15'),
(7, 2, 'Guest Person2', 'additional', '1970-05-15'),
(8, 3, 'Guest Person1', 'main', '1981-05-15'),
(9, 3, 'Guest Person2', 'additional', '1980-05-15'),
(10, 3, 'Guest Person3', 'additional', '1990-05-15'),
(11, 3, 'Guest Person4', 'additional', '1994-05-15'),
(12, 4, 'Guest Person1', 'main', '1982-05-15'),
(13, 5, 'Guest Person1', 'main', '1999-05-15'),
(14, 5, 'Guest Person2', 'additional', '2005-05-15'),
(15, 5, 'Guest Person3', 'additional', '2002-05-15'),
(16, 6, 'Guest Person1', 'main', '1968-05-15'),
(17, 6, 'Guest Person2', 'additional', '1992-05-15'),
(18, 6, 'Guest Person3', 'additional', '1986-05-15'),
(19, 6, 'Guest Person4', 'additional', '1991-05-15'),
(20, 7, 'Guest Person1', 'main', '1965-05-15'),
(21, 7, 'Guest Person2', 'additional', '1995-05-15'),
(22, 7, 'Guest Person3', 'additional', '1980-05-15'),
(23, 8, 'Guest Person1', 'main', '1989-05-15'),
(24, 9, 'Guest Person1', 'main', '1966-05-15'),
(25, 9, 'Guest Person2', 'additional', '1977-05-15'),
(26, 10, 'Guest Person1', 'main', '1999-05-15');

-- --------------------------------------------------------

--
-- Table structure for table `guest_reservations`
--

CREATE TABLE `guest_reservations` (
  `guest_reservations_id` int(11) NOT NULL,
  `reservation_id` varchar(255) NOT NULL,
  `room_number` varchar(255) NOT NULL,
  `arrival_date` date NOT NULL,
  `departure_date` date NOT NULL,
  `pax` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `board_type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `guest_reservations`
--

INSERT INTO `guest_reservations` (`guest_reservations_id`, `reservation_id`, `room_number`, `arrival_date`, `departure_date`, `pax`, `status`, `hotel_id`, `company_id`, `board_type`) VALUES
(2, 'RES62163', '203', '2025-05-15', '2025-05-22', 2, 'checked_in', 18, 3, 'BB'),
(3, 'RES45044', '202', '2025-05-15', '2025-05-22', 4, 'checked_in', 18, 3, 'BB'),
(4, 'RES21983', '103', '2025-05-15', '2025-05-28', 1, 'checked_in', 18, 3, 'BB'),
(5, 'RES18187', '103', '2025-05-15', '2025-05-24', 3, 'checked_in', 19, 3, 'BB'),
(6, 'RES35060', '202', '2025-05-15', '2025-05-22', 4, 'checked_in', 19, 3, 'BB'),
(7, 'RES17966', '101', '2025-05-15', '2025-05-22', 3, 'checked_in', 19, 3, 'BB'),
(8, 'RES27052', '203', '2025-05-15', '2025-05-22', 1, 'checked_in', 20, 3, 'BB'),
(9, 'RES84776', '303', '2025-05-15', '2025-05-22', 2, 'checked_in', 20, 3, 'BB'),
(10, 'RES25630', '202', '2025-05-15', '2025-05-22', 1, 'checked_in', 20, 3, 'BB');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `verification_type` tinyint(4) NOT NULL DEFAULT 0,
  `company_id` int(11) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `free_count` int(11) DEFAULT 0,
  `time_zone` varchar(45) DEFAULT '+02:00',
  `plus_days_adjust` int(11) DEFAULT 0,
  `minus_days_adjust` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `restricted_restaurants` tinyint(4) NOT NULL DEFAULT 0,
  `logo_url` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `name`, `verification_type`, `company_id`, `created_by`, `updated_at`, `updated_by`, `free_count`, `time_zone`, `plus_days_adjust`, `minus_days_adjust`, `created_at`, `active`, `restricted_restaurants`, `logo_url`) VALUES
(14, 'Grand Hotel', 0, 3, NULL, NULL, NULL, 3, '+00:00', 0, 0, '2025-05-15 13:46:53', 1, 0, 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hotel-3377344_1280.jpg'),
(18, 'Luxury Beach Resort', 0, 3, NULL, NULL, NULL, 3, '+00:00', 1, 1, '2025-05-15 14:06:10', 1, 0, 'https://cdn.pixabay.com/photo/2016/11/17/09/28/hotel-1831072_1280.jpg'),
(19, 'Mountain View Hotel', 1, 3, NULL, NULL, NULL, 3, '+00:00', 14, 0, '2025-05-15 14:06:10', 1, 0, 'https://cdn.pixabay.com/photo/2015/09/21/09/54/villa-cortine-palace-949552_1280.jpg'),
(20, 'City Center Hotel', 0, 3, NULL, NULL, NULL, 3, '+00:00', 14, 0, '2025-05-15 14:06:10', 1, 0, 'https://cdn.pixabay.com/photo/2020/10/18/09/16/bedroom-5664221_1280.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `items_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `menu_categories_id` int(11) DEFAULT NULL,
  `menu_subcategories_id` int(11) DEFAULT NULL,
  `items_transelation_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `label` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`items_id`, `company_id`, `menu_categories_id`, `menu_subcategories_id`, `items_transelation_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `label`) VALUES
(1, 3, 1, 1, NULL, NULL, 'system', NULL, NULL, 'Bruschetta'),
(2, 3, 1, 2, NULL, NULL, 'system', NULL, NULL, 'Caesar Salad'),
(3, 3, 2, 3, NULL, NULL, 'system', NULL, NULL, 'Grilled Salmon'),
(4, 3, 2, 4, NULL, NULL, 'system', NULL, NULL, 'Beef Tenderloin'),
(5, 3, 3, 5, NULL, NULL, 'system', NULL, NULL, 'Chocolate Cake'),
(6, 3, 4, 6, NULL, NULL, 'system', NULL, NULL, 'Espresso'),
(7, 3, 4, 7, NULL, NULL, 'system', NULL, NULL, 'Fresh Orange Juice');

-- --------------------------------------------------------

--
-- Table structure for table `items_translation`
--

CREATE TABLE `items_translation` (
  `items_translation_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `items_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items_translation`
--

INSERT INTO `items_translation` (`items_translation_id`, `name`, `description`, `language_code`, `items_id`) VALUES
(15, 'Bruschetta', 'Delicious Bruschetta', 'en', 1),
(16, 'Caesar Salad', 'Delicious Caesar Salad', 'en', 2),
(17, 'Grilled Salmon', 'Delicious Grilled Salmon', 'en', 3),
(18, 'Beef Tenderloin', 'Delicious Beef Tenderloin', 'en', 4),
(19, 'Chocolate Cake', 'Delicious Chocolate Cake', 'en', 5),
(20, 'Espresso', 'Delicious Espresso', 'en', 6),
(21, 'Fresh Orange Juice', 'Delicious Fresh Orange Juice', 'en', 7);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `level` varchar(255) DEFAULT NULL,
  `metadata` longtext DEFAULT NULL,
  `time_stamp` timestamp NULL DEFAULT NULL,
  `sql_time_stamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` longtext DEFAULT NULL,
  `message` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_types`
--

CREATE TABLE `meal_types` (
  `meal_types_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT 1,
  `label` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `meal_types`
--

INSERT INTO `meal_types` (`meal_types_id`, `company_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `active`, `label`) VALUES
(1, 3, '2025-05-19 13:08:51', 'system', NULL, NULL, 1, 'Breakfast'),
(2, 3, '2025-05-19 13:08:51', 'system', NULL, NULL, 1, 'Lunch'),
(3, 3, '2025-05-19 13:08:51', 'system', NULL, NULL, 1, 'Dinner'),
(18, 3, '2025-05-15 11:06:10', NULL, NULL, NULL, 1, 'B'),
(19, 3, '2025-05-15 11:06:10', NULL, NULL, NULL, 1, 'L'),
(20, 3, '2025-05-15 11:06:10', NULL, NULL, NULL, 1, 'D'),
(21, 3, '2025-05-15 11:06:10', NULL, NULL, NULL, 1, 'BR');

-- --------------------------------------------------------

--
-- Table structure for table `meal_types_translation`
--

CREATE TABLE `meal_types_translation` (
  `meal_types_translation_id` int(11) NOT NULL,
  `meal_types_id` int(11) DEFAULT NULL,
  `name` longtext DEFAULT NULL,
  `language_code` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `meal_types_translation`
--

INSERT INTO `meal_types_translation` (`meal_types_translation_id`, `meal_types_id`, `name`, `language_code`) VALUES
(7, 18, 'Breakfast', 'en'),
(8, 19, 'Lunch', 'en'),
(9, 20, 'Dinner', 'en'),
(10, 21, 'Brunch', 'en'),
(11, 1, 'Breakfast', 'en'),
(12, 2, 'Lunch', 'en'),
(13, 3, 'Dinner', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menus_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menus_id`, `company_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `label`) VALUES
(1, 3, '2025-05-19 13:10:45', 'system', NULL, NULL, 'Breakfast Menu'),
(2, 3, '2025-05-19 13:10:45', 'system', NULL, NULL, 'Lunch Menu'),
(3, 3, '2025-05-19 13:10:45', 'system', NULL, NULL, 'Dinner Menu');

-- --------------------------------------------------------

--
-- Table structure for table `menus_items`
--

CREATE TABLE `menus_items` (
  `menus_items_id` int(11) NOT NULL,
  `items_id` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `currencies_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `menus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menus_items`
--

INSERT INTO `menus_items` (`menus_items_id`, `items_id`, `price`, `currencies_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `menus_id`) VALUES
(11, 1, '50', 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(12, 2, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(13, 3, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(14, 4, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(15, 5, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(16, 6, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(17, 7, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 1),
(18, 1, '40', 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(19, 2, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(20, 3, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(21, 4, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(22, 5, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(23, 6, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(24, 7, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 2),
(25, 1, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(26, 2, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(27, 3, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(28, 4, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(29, 5, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(30, 6, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3),
(31, 7, NULL, 9, '2025-05-19 13:10:45', 'system', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `menu_categories_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `label` longtext DEFAULT NULL,
  `background_url` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`menu_categories_id`, `company_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `label`, `background_url`) VALUES
(1, 3, '2025-05-19 13:09:42', 'system', NULL, NULL, 'Appetizers', NULL),
(2, 3, '2025-05-19 13:09:42', 'system', NULL, NULL, 'Main Courses', NULL),
(3, 3, '2025-05-19 13:09:42', 'system', NULL, NULL, 'Desserts', NULL),
(4, 3, '2025-05-19 13:09:42', 'system', NULL, NULL, 'Beverages', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories_translation`
--

CREATE TABLE `menu_categories_translation` (
  `menu_categories_translation_id` int(11) NOT NULL,
  `menu_categories_id` int(11) DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menu_categories_translation`
--

INSERT INTO `menu_categories_translation` (`menu_categories_translation_id`, `menu_categories_id`, `language_code`, `name`) VALUES
(13, 1, 'en', 'Appetizers'),
(14, 2, 'en', 'Main Courses'),
(15, 3, 'en', 'Desserts'),
(16, 4, 'en', 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `menu_links`
--

CREATE TABLE `menu_links` (
  `menu_links_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `menu_url` longtext DEFAULT NULL,
  `label` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_subcategories`
--

CREATE TABLE `menu_subcategories` (
  `menu_subcategories_id` int(11) NOT NULL,
  `menu_categories_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `label` longtext DEFAULT NULL,
  `background_url` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menu_subcategories`
--

INSERT INTO `menu_subcategories` (`menu_subcategories_id`, `menu_categories_id`, `company_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `label`, `background_url`) VALUES
(1, 1, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Hot Appetizers', NULL),
(2, 1, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Cold Appetizers', NULL),
(3, 2, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Seafood', NULL),
(4, 2, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Meat', NULL),
(5, 3, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Cakes', NULL),
(6, 4, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Hot Drinks', NULL),
(7, 4, 3, '2025-05-19 13:10:09', 'system', NULL, NULL, 'Cold Drinks', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_subcategories_translation`
--

CREATE TABLE `menu_subcategories_translation` (
  `menu_subcategories_translation_id` int(11) NOT NULL,
  `menu_subcategories_id` int(11) DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `menu_subcategories_translation`
--

INSERT INTO `menu_subcategories_translation` (`menu_subcategories_translation_id`, `menu_subcategories_id`, `language_code`, `name`) VALUES
(13, 1, 'en', 'Hot Appetizers'),
(14, 2, 'en', 'Cold Appetizers'),
(15, 3, 'en', 'Seafood'),
(16, 4, 'en', 'Meat'),
(17, 5, 'en', 'Cakes'),
(18, 6, 'en', 'Hot Drinks'),
(19, 7, 'en', 'Cold Drinks');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservations_id` int(11) NOT NULL,
  `guest_reservations_id` int(11) DEFAULT NULL,
  `room_number` varchar(255) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `names` longtext DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `day` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `guest_hotel_id` int(11) DEFAULT NULL,
  `restaurant_hotel_id` int(11) DEFAULT NULL,
  `canceled` tinyint(4) DEFAULT 0,
  `ended` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `qrcode` longtext DEFAULT NULL,
  `created_by` longtext DEFAULT NULL,
  `canceled_by` longtext DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `currencies_id` int(11) DEFAULT NULL,
  `price` decimal(10,5) DEFAULT NULL,
  `exchange_rate` decimal(10,6) DEFAULT NULL,
  `paid` tinyint(4) DEFAULT NULL,
  `always_paid_free` tinyint(4) DEFAULT NULL,
  `taxes` longtext DEFAULT NULL,
  `discounts` longtext DEFAULT NULL,
  `original_price` decimal(10,5) DEFAULT NULL,
  `sub_total` decimal(10,5) DEFAULT NULL,
  `after_tax` decimal(10,5) DEFAULT NULL,
  `total_ammount_due` decimal(10,5) DEFAULT NULL,
  `per_person` tinyint(4) DEFAULT NULL,
  `reservation_by_room` tinyint(4) DEFAULT NULL,
  `time_zone` varchar(45) DEFAULT NULL,
  `meal_types_id` int(11) DEFAULT NULL,
  `menus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservations_id`, `guest_reservations_id`, `room_number`, `pax`, `names`, `restaurant_id`, `day`, `time`, `company_id`, `guest_hotel_id`, `restaurant_hotel_id`, `canceled`, `ended`, `created_at`, `qrcode`, `created_by`, `canceled_by`, `canceled_at`, `currencies_id`, `price`, `exchange_rate`, `paid`, `always_paid_free`, `taxes`, `discounts`, `original_price`, `sub_total`, `after_tax`, `total_ammount_due`, `per_person`, `reservation_by_room`, `time_zone`, `meal_types_id`, `menus_id`) VALUES
(55, 3, '202', 4, 'Guest Person1, Guest Person2, Guest Person3, Guest Person4', 17, '2025-05-16', '18:00:00', 3, 18, 19, 0, 0, '2025-05-15 14:06:10', 'QR6825caa24bad3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, NULL),
(56, 8, '203', 1, 'Guest Person1', 20, '2025-05-16', '18:00:00', 3, 20, 20, 0, 0, '2025-05-15 14:06:10', 'QR6825caa24bc5a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL),
(57, 2, '203', 2, 'Guest Person1, Guest Person2', 14, '2025-05-18', '18:00:00', 3, 18, 18, 0, 0, '2025-05-15 14:06:10', 'QR6825caa24bdec', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, NULL),
(59, 4, NULL, 1, NULL, 14, '2025-05-20', '12:30:00', 1, NULL, NULL, 0, 0, '2025-05-20 10:06:18', 'vFaroM4H8N9ys9yDaTN4', 'system', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 1, NULL),
(60, 4, NULL, 1, NULL, 14, '2025-05-24', '13:00:00', 1, NULL, NULL, 0, 0, '2025-05-24 16:06:25', 'QyAnPSQHXKTBUk8cpNmS', 'system', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 19, NULL),
(61, 4, NULL, 1, NULL, 14, '2025-05-24', '14:00:00', 1, NULL, NULL, 0, 0, '2025-05-24 16:52:56', 'FsQ6rQ53rWfClTsmxk3b', 'system', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 19, NULL),
(62, 4, NULL, NULL, NULL, 14, '2025-05-24', '08:00:00', 1, NULL, NULL, 0, 0, '2025-05-24 17:25:44', 'QmTv6oVScCu2KDAfIF3J', 'system', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UTC', 18, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations_taxes`
--

CREATE TABLE `reservations_taxes` (
  `reservations_taxes_id` int(11) NOT NULL,
  `reservations_id` int(11) DEFAULT NULL,
  `taxes_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurants_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `hotel_id` int(11) DEFAULT NULL,
  `logo_url` longtext DEFAULT NULL,
  `always_paid_free` tinyint(4) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurants_id`, `company_id`, `name`, `capacity`, `created_at`, `active`, `hotel_id`, `logo_url`, `always_paid_free`, `created_by`, `updated_at`, `updated_by`) VALUES
(13, 3, 'Fine Dining Restaurant', 50, '2025-05-15 10:46:53', 1, 14, 'https://cdn.pixabay.com/photo/2016/11/18/14/05/kitchen-1834858_1280.jpg', 0, NULL, NULL, NULL),
(14, 3, 'Ocean View Restaurant', 60, '2025-05-15 11:06:10', 1, 18, 'https://cdn.pixabay.com/photo/2018/07/14/15/27/cafe-3537801_1280.jpg', 0, NULL, NULL, NULL),
(15, 3, 'Garden Terrace', 45, '2025-05-15 11:06:10', 1, 18, 'https://cdn.pixabay.com/photo/2014/09/17/20/26/restaurant-449952_1280.jpg', 0, NULL, NULL, NULL),
(16, 3, 'Skyline Rooftop', 50, '2025-05-15 11:06:10', 1, 18, 'https://cdn.pixabay.com/photo/2020/01/30/12/27/celebration-4805518_1280.jpg', 0, NULL, NULL, NULL),
(17, 3, 'Ocean View Restaurant', 60, '2025-05-15 11:06:10', 1, 19, 'https://cdn.pixabay.com/photo/2018/07/14/15/27/cafe-3537801_1280.jpg', 0, NULL, NULL, NULL),
(18, 3, 'Garden Terrace', 45, '2025-05-15 11:06:10', 1, 19, 'https://cdn.pixabay.com/photo/2014/09/17/20/26/restaurant-449952_1280.jpg', 0, NULL, NULL, NULL),
(19, 3, 'Skyline Rooftop', 50, '2025-05-15 11:06:10', 1, 19, 'https://cdn.pixabay.com/photo/2020/01/30/12/27/celebration-4805518_1280.jpg', 0, NULL, NULL, NULL),
(20, 3, 'Ocean View Restaurant', 60, '2025-05-15 11:06:10', 1, 20, 'https://cdn.pixabay.com/photo/2018/07/14/15/27/cafe-3537801_1280.jpg', 0, NULL, NULL, NULL),
(21, 3, 'Garden Terrace', 45, '2025-05-15 11:06:10', 1, 20, 'https://cdn.pixabay.com/photo/2014/09/17/20/26/restaurant-449952_1280.jpg', 0, NULL, NULL, NULL),
(22, 3, 'Skyline Rooftop', 50, '2025-05-15 11:06:10', 1, 20, 'https://cdn.pixabay.com/photo/2020/01/30/12/27/celebration-4805518_1280.jpg', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants_translations`
--

CREATE TABLE `restaurants_translations` (
  `restaurant_translations_id` int(11) NOT NULL,
  `restaurants_id` int(11) DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `cuisine` longtext DEFAULT NULL,
  `about` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurants_translations`
--

INSERT INTO `restaurants_translations` (`restaurant_translations_id`, `restaurants_id`, `language_code`, `cuisine`, `about`) VALUES
(19, 13, 'en', 'International', 'A luxurious dining experience with the finest cuisine from around the world.'),
(20, 14, 'en', 'Seafood', 'Enjoy fresh seafood with a beautiful ocean view. Our restaurant offers the freshest catches prepared by world-class chefs.'),
(21, 15, 'en', 'Italian', 'Authentic Italian cuisine served in a beautiful garden setting. Perfect for a romantic dinner or family gathering.'),
(22, 16, 'en', 'International', 'Dine with a spectacular view of the city skyline. Our menu offers a variety of international dishes crafted with local ingredients.'),
(23, 17, 'en', 'Seafood', 'Enjoy fresh seafood with a beautiful ocean view. Our restaurant offers the freshest catches prepared by world-class chefs.'),
(24, 18, 'en', 'Italian', 'Authentic Italian cuisine served in a beautiful garden setting. Perfect for a romantic dinner or family gathering.'),
(25, 19, 'en', 'International', 'Dine with a spectacular view of the city skyline. Our menu offers a variety of international dishes crafted with local ingredients.'),
(26, 20, 'en', 'Seafood', 'Enjoy fresh seafood with a beautiful ocean view. Our restaurant offers the freshest catches prepared by world-class chefs.'),
(27, 21, 'en', 'Italian', 'Authentic Italian cuisine served in a beautiful garden setting. Perfect for a romantic dinner or family gathering.'),
(28, 22, 'en', 'International', 'Dine with a spectacular view of the city skyline. Our menu offers a variety of international dishes crafted with local ingredients.');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_pricing_times`
--

CREATE TABLE `restaurant_pricing_times` (
  `restaurant_pricing_times_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `year` varchar(45) DEFAULT NULL,
  `month` varchar(45) DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `per_person` tinyint(4) DEFAULT 1,
  `price` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `meal_type` longtext DEFAULT NULL,
  `reservation_by_room` tinyint(4) NOT NULL DEFAULT 1,
  `extra_seats` int(11) NOT NULL DEFAULT 0,
  `menu_url` longtext DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `menus_id` int(11) DEFAULT NULL,
  `calculate_price` tinyint(4) DEFAULT 1,
  `created_by` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurant_pricing_times`
--

INSERT INTO `restaurant_pricing_times` (`restaurant_pricing_times_id`, `company_id`, `restaurant_id`, `currency_id`, `year`, `month`, `day`, `time`, `per_person`, `price`, `created_at`, `meal_type`, `reservation_by_room`, `extra_seats`, `menu_url`, `hotel_id`, `menus_id`, `calculate_price`, `created_by`, `updated_at`, `updated_by`) VALUES
(278, 3, 14, 9, '2025', '05', '24', '07:00:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(279, 3, 14, 9, '2025', '05', '24', '07:30:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(280, 3, 14, 9, '2025', '05', '24', '08:00:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(281, 3, 14, 9, '2025', '05', '24', '08:30:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(282, 3, 14, 9, '2025', '05', '24', '09:00:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(283, 3, 14, 9, '2025', '05', '24', '09:30:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(284, 3, 14, 9, '2025', '05', '24', '10:00:00', 1, NULL, '2025-05-24 15:34:49', '18', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(285, 3, 14, 9, '2025', '05', '24', '12:00:00', 1, NULL, '2025-05-24 15:34:49', '19', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(286, 3, 14, 9, '2025', '05', '24', '12:30:00', 1, NULL, '2025-05-24 15:34:49', '19', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(287, 3, 14, 9, '2025', '05', '24', '13:00:00', 1, NULL, '2025-05-24 15:34:49', '19', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(288, 3, 14, 9, '2025', '05', '24', '13:30:00', 1, NULL, '2025-05-24 15:34:49', '19', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(289, 3, 14, 9, '2025', '05', '24', '14:00:00', 1, NULL, '2025-05-24 15:34:49', '19', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(290, 3, 14, 9, '2025', '05', '24', '18:00:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(291, 3, 14, 9, '2025', '05', '24', '18:30:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(292, 3, 14, 9, '2025', '05', '24', '19:00:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(293, 3, 14, 9, '2025', '05', '24', '19:30:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(294, 3, 14, 9, '2025', '05', '24', '20:00:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(295, 3, 14, 9, '2025', '05', '24', '20:30:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL),
(296, 3, 14, 9, '2025', '05', '24', '21:00:00', 1, NULL, '2025-05-24 15:34:49', '20', 1, 10, NULL, 18, NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_pricing_times_discounts`
--

CREATE TABLE `restaurant_pricing_times_discounts` (
  `restaurant_pricing_times_discounts_id` int(11) NOT NULL,
  `restaurant_pricing_times_id` int(11) DEFAULT NULL,
  `discounts_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_pricing_times_taxes`
--

CREATE TABLE `restaurant_pricing_times_taxes` (
  `restaurant_pricing_times_taxes_id` int(11) NOT NULL,
  `restaurant_pricing_times_id` int(11) DEFAULT NULL,
  `taxes_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `expires` int(11) UNSIGNED NOT NULL,
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `taxes_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `label` longtext DEFAULT NULL,
  `percentage` tinyint(4) DEFAULT 0,
  `value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` longtext DEFAULT NULL,
  `active` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes_translation`
--

CREATE TABLE `taxes_translation` (
  `taxes_translation_id` int(11) NOT NULL,
  `taxes_id` int(11) DEFAULT NULL,
  `language_code` varchar(45) DEFAULT NULL,
  `name` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE `users_session` (
  `session_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `expires` int(11) UNSIGNED NOT NULL,
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  ADD PRIMARY KEY (`admin_privileges_id`),
  ADD UNIQUE KEY `hotels_tab_UNIQUE` (`hotels_tab`),
  ADD KEY `fk_admin_privileges_1_idx` (`admin_users_id`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_users_id`),
  ADD KEY `company_fk_idx` (`company_id`);

--
-- Indexes for table `board_type_rules`
--
ALTER TABLE `board_type_rules`
  ADD PRIMARY KEY (`board_type_rules_id`),
  ADD KEY `hotel_fk_idx` (`hotel_id`),
  ADD KEY `company_fk_idx` (`company_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currencies_id`),
  ADD KEY `fk_currencies_1_idx` (`company_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discounts_id`),
  ADD KEY `fk_company_discounts_idx` (`company_id`);

--
-- Indexes for table `discounts_translation`
--
ALTER TABLE `discounts_translation`
  ADD PRIMARY KEY (`discounts_translation_id`),
  ADD KEY `fk_discounts_id_idx` (`discounts_id`);

--
-- Indexes for table `guest_details`
--
ALTER TABLE `guest_details`
  ADD PRIMARY KEY (`guest_details_id`),
  ADD KEY `fk_guest_details_1_idx` (`guest_reservations_id`);

--
-- Indexes for table `guest_reservations`
--
ALTER TABLE `guest_reservations`
  ADD PRIMARY KEY (`guest_reservations_id`),
  ADD UNIQUE KEY `reservation_id_UNIQUE` (`reservation_id`),
  ADD KEY `fk_guest_reservations_1_idx` (`company_id`),
  ADD KEY `fk_guest_reservations_2_idx` (`hotel_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `fk_hotels_1_idx` (`company_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`items_id`),
  ADD KEY `fk_items_2_idx` (`company_id`),
  ADD KEY `fk_items_3_idx` (`menu_categories_id`),
  ADD KEY `fk_items_4_idx` (`menu_subcategories_id`);

--
-- Indexes for table `items_translation`
--
ALTER TABLE `items_translation`
  ADD PRIMARY KEY (`items_translation_id`),
  ADD KEY `fk_items_transelation_1_idx` (`items_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `meal_types`
--
ALTER TABLE `meal_types`
  ADD PRIMARY KEY (`meal_types_id`),
  ADD KEY `fk_meal_types_1_idx` (`company_id`);

--
-- Indexes for table `meal_types_translation`
--
ALTER TABLE `meal_types_translation`
  ADD PRIMARY KEY (`meal_types_translation_id`),
  ADD KEY `fk_meal_types_translation_1_idx` (`meal_types_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menus_id`),
  ADD KEY `fk_menus_1_idx` (`company_id`);

--
-- Indexes for table `menus_items`
--
ALTER TABLE `menus_items`
  ADD PRIMARY KEY (`menus_items_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`menu_categories_id`),
  ADD KEY `fk_menu_categories_1_idx` (`company_id`);

--
-- Indexes for table `menu_categories_translation`
--
ALTER TABLE `menu_categories_translation`
  ADD PRIMARY KEY (`menu_categories_translation_id`),
  ADD KEY `fk_menu_categories_transelation_1_idx` (`menu_categories_id`);

--
-- Indexes for table `menu_links`
--
ALTER TABLE `menu_links`
  ADD PRIMARY KEY (`menu_links_id`);

--
-- Indexes for table `menu_subcategories`
--
ALTER TABLE `menu_subcategories`
  ADD PRIMARY KEY (`menu_subcategories_id`),
  ADD KEY `fk_menu_subcategories_1_idx` (`company_id`),
  ADD KEY `fk_menu_subcategories_2_idx` (`menu_categories_id`);

--
-- Indexes for table `menu_subcategories_translation`
--
ALTER TABLE `menu_subcategories_translation`
  ADD PRIMARY KEY (`menu_subcategories_translation_id`),
  ADD KEY `fk_menu_subcategories_transelation_1_idx` (`menu_subcategories_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservations_id`);

--
-- Indexes for table `reservations_taxes`
--
ALTER TABLE `reservations_taxes`
  ADD PRIMARY KEY (`reservations_taxes_id`),
  ADD KEY `fk_reservations_taxes_1_idx` (`reservations_id`),
  ADD KEY `fk_reservations_taxes_2_idx` (`taxes_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurants_id`),
  ADD KEY `fk_restaurants_1_idx` (`company_id`),
  ADD KEY `fk_restaurants_2_idx` (`hotel_id`);

--
-- Indexes for table `restaurants_translations`
--
ALTER TABLE `restaurants_translations`
  ADD PRIMARY KEY (`restaurant_translations_id`),
  ADD KEY `fk_restaurant_translations_1_idx` (`restaurants_id`);

--
-- Indexes for table `restaurant_pricing_times`
--
ALTER TABLE `restaurant_pricing_times`
  ADD PRIMARY KEY (`restaurant_pricing_times_id`);

--
-- Indexes for table `restaurant_pricing_times_discounts`
--
ALTER TABLE `restaurant_pricing_times_discounts`
  ADD PRIMARY KEY (`restaurant_pricing_times_discounts_id`),
  ADD KEY `fk_restaurant_pricing_times_discounts_1_idx` (`restaurant_pricing_times_id`);

--
-- Indexes for table `restaurant_pricing_times_taxes`
--
ALTER TABLE `restaurant_pricing_times_taxes`
  ADD PRIMARY KEY (`restaurant_pricing_times_taxes_id`),
  ADD KEY `fk_restaurant_pricing_times_taxes_1_idx` (`restaurant_pricing_times_id`),
  ADD KEY `fk_restaurant_pricing_times_taxes_2_idx` (`taxes_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`taxes_id`),
  ADD KEY `fk_taxes_1_idx` (`company_id`);

--
-- Indexes for table `taxes_translation`
--
ALTER TABLE `taxes_translation`
  ADD PRIMARY KEY (`taxes_translation_id`),
  ADD KEY `fk_taxes_translation_1_idx` (`taxes_id`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  MODIFY `admin_privileges_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `board_type_rules`
--
ALTER TABLE `board_type_rules`
  MODIFY `board_type_rules_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `currencies_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discounts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `discounts_translation`
--
ALTER TABLE `discounts_translation`
  MODIFY `discounts_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guest_details`
--
ALTER TABLE `guest_details`
  MODIFY `guest_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `guest_reservations`
--
ALTER TABLE `guest_reservations`
  MODIFY `guest_reservations_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items_translation`
--
ALTER TABLE `items_translation`
  MODIFY `items_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=858;

--
-- AUTO_INCREMENT for table `meal_types`
--
ALTER TABLE `meal_types`
  MODIFY `meal_types_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `meal_types_translation`
--
ALTER TABLE `meal_types_translation`
  MODIFY `meal_types_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menus_items`
--
ALTER TABLE `menus_items`
  MODIFY `menus_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `menu_categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_categories_translation`
--
ALTER TABLE `menu_categories_translation`
  MODIFY `menu_categories_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `menu_links`
--
ALTER TABLE `menu_links`
  MODIFY `menu_links_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_subcategories`
--
ALTER TABLE `menu_subcategories`
  MODIFY `menu_subcategories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_subcategories_translation`
--
ALTER TABLE `menu_subcategories_translation`
  MODIFY `menu_subcategories_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservations_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `reservations_taxes`
--
ALTER TABLE `reservations_taxes`
  MODIFY `reservations_taxes_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurants_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `restaurants_translations`
--
ALTER TABLE `restaurants_translations`
  MODIFY `restaurant_translations_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `restaurant_pricing_times`
--
ALTER TABLE `restaurant_pricing_times`
  MODIFY `restaurant_pricing_times_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `restaurant_pricing_times_discounts`
--
ALTER TABLE `restaurant_pricing_times_discounts`
  MODIFY `restaurant_pricing_times_discounts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `restaurant_pricing_times_taxes`
--
ALTER TABLE `restaurant_pricing_times_taxes`
  MODIFY `restaurant_pricing_times_taxes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `taxes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `taxes_translation`
--
ALTER TABLE `taxes_translation`
  MODIFY `taxes_translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  ADD CONSTRAINT `fk_admin_privileges_1` FOREIGN KEY (`admin_users_id`) REFERENCES `admin_users` (`admin_users_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD CONSTRAINT `comp_fk_214` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `board_type_rules`
--
ALTER TABLE `board_type_rules`
  ADD CONSTRAINT `company_fk` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `hotel_fk` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `currencies`
--
ALTER TABLE `currencies`
  ADD CONSTRAINT `fk_currencies_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `fk_company_discounts` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `discounts_translation`
--
ALTER TABLE `discounts_translation`
  ADD CONSTRAINT `fk_discounts_id` FOREIGN KEY (`discounts_id`) REFERENCES `discounts` (`discounts_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `guest_details`
--
ALTER TABLE `guest_details`
  ADD CONSTRAINT `fk_guest_details_1` FOREIGN KEY (`guest_reservations_id`) REFERENCES `guest_reservations` (`guest_reservations_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `guest_reservations`
--
ALTER TABLE `guest_reservations`
  ADD CONSTRAINT `fk_guest_reservations_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guest_reservations_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `fk_hotels_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_items_3` FOREIGN KEY (`menu_categories_id`) REFERENCES `menu_categories` (`menu_categories_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_items_4` FOREIGN KEY (`menu_subcategories_id`) REFERENCES `menu_subcategories` (`menu_subcategories_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items_translation`
--
ALTER TABLE `items_translation`
  ADD CONSTRAINT `fk_items_transelation_1` FOREIGN KEY (`items_id`) REFERENCES `items` (`items_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meal_types`
--
ALTER TABLE `meal_types`
  ADD CONSTRAINT `fk_meal_types_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meal_types_translation`
--
ALTER TABLE `meal_types_translation`
  ADD CONSTRAINT `fk_meal_types_translation_1` FOREIGN KEY (`meal_types_id`) REFERENCES `meal_types` (`meal_types_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD CONSTRAINT `fk_menu_categories_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu_categories_translation`
--
ALTER TABLE `menu_categories_translation`
  ADD CONSTRAINT `fk_menu_categories_transelation_1` FOREIGN KEY (`menu_categories_id`) REFERENCES `menu_categories` (`menu_categories_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu_subcategories`
--
ALTER TABLE `menu_subcategories`
  ADD CONSTRAINT `fk_menu_subcategories_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_menu_subcategories_2` FOREIGN KEY (`menu_categories_id`) REFERENCES `menu_categories` (`menu_categories_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu_subcategories_translation`
--
ALTER TABLE `menu_subcategories_translation`
  ADD CONSTRAINT `fk_menu_subcategories_transelation_1` FOREIGN KEY (`menu_subcategories_id`) REFERENCES `menu_subcategories` (`menu_subcategories_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reservations_taxes`
--
ALTER TABLE `reservations_taxes`
  ADD CONSTRAINT `fk_reservations_taxes_1` FOREIGN KEY (`reservations_id`) REFERENCES `reservations` (`reservations_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reservations_taxes_2` FOREIGN KEY (`taxes_id`) REFERENCES `taxes` (`taxes_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `fk_restaurants_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_restaurants_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `restaurants_translations`
--
ALTER TABLE `restaurants_translations`
  ADD CONSTRAINT `fk_restaurant_translations_1` FOREIGN KEY (`restaurants_id`) REFERENCES `restaurants` (`restaurants_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `restaurant_pricing_times_discounts`
--
ALTER TABLE `restaurant_pricing_times_discounts`
  ADD CONSTRAINT `fk_restaurant_pricing_times_discounts_1` FOREIGN KEY (`restaurant_pricing_times_id`) REFERENCES `restaurant_pricing_times` (`restaurant_pricing_times_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `restaurant_pricing_times_taxes`
--
ALTER TABLE `restaurant_pricing_times_taxes`
  ADD CONSTRAINT `fk_restaurant_pricing_times_taxes_1` FOREIGN KEY (`restaurant_pricing_times_id`) REFERENCES `restaurant_pricing_times` (`restaurant_pricing_times_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_restaurant_pricing_times_taxes_2` FOREIGN KEY (`taxes_id`) REFERENCES `taxes` (`taxes_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `fk_taxes_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `taxes_translation`
--
ALTER TABLE `taxes_translation`
  ADD CONSTRAINT `fk_taxes_translation_1` FOREIGN KEY (`taxes_id`) REFERENCES `taxes` (`taxes_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
