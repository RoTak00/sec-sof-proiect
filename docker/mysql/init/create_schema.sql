USE app;

CREATE TABLE `website_settings` (
  `setting_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL,
  `value` text,
  `setting_category` varchar(127) DEFAULT '',
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `name` (`name`)
)