CREATE DATABASE IF NOT EXISTS `gicpctalaga` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gicpctalaga`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `newsletter_subscriptions`;
DROP TABLE IF EXISTS `prayer_requests`;
DROP TABLE IF EXISTS `visitor_logs`;
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `sermon_files`;
DROP TABLE IF EXISTS `gallery_images`;
DROP TABLE IF EXISTS `menu_items`;
DROP TABLE IF EXISTS `service_schedules`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `church_profile`;
DROP TABLE IF EXISTS `pastors`;
DROP TABLE IF EXISTS `ministries`;
DROP TABLE IF EXISTS `sermons`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `daily_verses`;
DROP TABLE IF EXISTS `news_updates`;
DROP TABLE IF EXISTS `galleries`;
DROP TABLE IF EXISTS `settings`;

CREATE TABLE `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `permissions` LONGTEXT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT UNSIGNED NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NULL,
  `address` VARCHAR(255) NULL,
  `photo` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `reset_token` VARCHAR(120) NULL,
  `reset_expires_at` DATETIME NULL,
  `last_login_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_email` (`email`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `church_profile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `church_name` VARCHAR(255) NOT NULL,
  `short_name` VARCHAR(120) NOT NULL,
  `tagline` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `hero_image` VARCHAR(255) NULL,
  `welcome_message` LONGTEXT NULL,
  `brief_intro` LONGTEXT NULL,
  `history` LONGTEXT NULL,
  `mission` LONGTEXT NULL,
  `vision` LONGTEXT NULL,
  `core_values` LONGTEXT NULL,
  `statement_of_faith` LONGTEXT NULL,
  `address` VARCHAR(255) NULL,
  `email` VARCHAR(150) NULL,
  `mobile_number` VARCHAR(50) NULL,
  `landline` VARCHAR(50) NULL,
  `google_map_embed` LONGTEXT NULL,
  `facebook_url` VARCHAR(255) NULL,
  `youtube_url` VARCHAR(255) NULL,
  `instagram_url` VARCHAR(255) NULL,
  `prayer_cta_text` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pastors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `position` VARCHAR(150) NOT NULL,
  `bio` LONGTEXT NULL,
  `photo` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pastors_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ministries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `description` LONGTEXT NULL,
  `image` VARCHAR(255) NULL,
  `leader_name` VARCHAR(150) NULL,
  `meeting_schedule` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ministries_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sermons` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `speaker` VARCHAR(150) NOT NULL,
  `sermon_date` DATE NOT NULL,
  `excerpt` LONGTEXT NULL,
  `content` LONGTEXT NULL,
  `youtube_url` VARCHAR(255) NULL,
  `featured_image` VARCHAR(255) NULL,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sermons_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sermon_files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sermon_id` INT UNSIGNED NOT NULL,
  `file_type` ENUM('audio', 'pdf') NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sermon_files_sermon` FOREIGN KEY (`sermon_id`) REFERENCES `sermons`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `summary` LONGTEXT NULL,
  `description` LONGTEXT NULL,
  `location` VARCHAR(255) NULL,
  `start_datetime` DATETIME NOT NULL,
  `end_datetime` DATETIME NULL,
  `image` VARCHAR(255) NULL,
  `registration_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `registration_limit` INT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_events_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `event_registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` INT UNSIGNED NOT NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `mobile_number` VARCHAR(50) NULL,
  `notes` LONGTEXT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_event_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `announcements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `summary` LONGTEXT NULL,
  `content` LONGTEXT NULL,
  `image` VARCHAR(255) NULL,
  `publish_date` DATE NOT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_announcements_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `daily_verses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `verse_text` LONGTEXT NOT NULL,
  `reference` VARCHAR(120) NOT NULL,
  `publish_date` DATE NOT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `news_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `category` VARCHAR(120) NULL,
  `summary` LONGTEXT NULL,
  `content` LONGTEXT NULL,
  `image` VARCHAR(255) NULL,
  `publish_date` DATE NOT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_news_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `galleries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `description` LONGTEXT NULL,
  `cover_image` VARCHAR(255) NULL,
  `album_date` DATE NOT NULL,
  `category` VARCHAR(120) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_galleries_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `gallery_images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_gallery_images_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `galleries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(120) NOT NULL,
  `setting_value` LONGTEXT NULL,
  `setting_group` VARCHAR(80) NOT NULL DEFAULT 'general',
  `label` VARCHAR(150) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `menu_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(80) NULL,
  `target` VARCHAR(20) NOT NULL DEFAULT '_self',
  `parent_id` INT UNSIGNED NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_menu_items_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu_items`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `service_schedules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `day_name` VARCHAR(50) NOT NULL,
  `time_range` VARCHAR(80) NOT NULL,
  `description` VARCHAR(255) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `prayer_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `mobile_number` VARCHAR(50) NULL,
  `request_text` LONGTEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'closed') NOT NULL DEFAULT 'pending',
  `is_private` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `newsletter_subscriptions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NULL,
  `email` VARCHAR(150) NOT NULL,
  `status` ENUM('subscribed', 'unsubscribed') NOT NULL DEFAULT 'subscribed',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_newsletter_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `mobile_number` VARCHAR(50) NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` LONGTEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied') NOT NULL DEFAULT 'new',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `visitor_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NULL,
  `visited_on` DATE NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `activity_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `module` VARCHAR(100) NOT NULL,
  `description` LONGTEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', '["all"]', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Editor', 'editor', '["dashboard","content"]', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone`, `address`, `photo`, `is_active`, `reset_token`, `reset_expires_at`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Grace', 'Administrator', 'admin@gicptalaga.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/Fsh3GZzXpr2m.', '09171234567', 'Talaga, Nueva Ecija', 'assets/images/placeholders/profile.svg', 1, NULL, NULL, NULL, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 2, 'Church', 'Editor', 'editor@gicptalaga.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/Fsh3GZzXpr2m.', '09179876543', 'Talaga, Nueva Ecija', 'assets/images/placeholders/profile.svg', 1, NULL, NULL, NULL, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `church_profile` (`id`, `church_name`, `short_name`, `tagline`, `logo`, `hero_image`, `welcome_message`, `brief_intro`, `history`, `mission`, `vision`, `core_values`, `statement_of_faith`, `address`, `email`, `mobile_number`, `landline`, `google_map_embed`, `facebook_url`, `youtube_url`, `instagram_url`, `prayer_cta_text`, `created_at`, `updated_at`) VALUES
(1, 'Grace in Christ Presbyterian Church - Talaga', 'GICP Talaga', 'Growing together in grace, truth, and mission.', 'assets/images/logo.svg', 'assets/images/hero-pattern.svg', 'Welcome to Grace in Christ Presbyterian Church - Talaga. We are a Christ-centered covenant community committed to worship, discipleship, and gospel witness in Talaga and beyond.', 'GICP Talaga is a Presbyterian congregation committed to biblical preaching, reverent worship, prayerful fellowship, and compassionate ministry in the local community.', 'Grace in Christ Presbyterian Church - Talaga began as a prayerful gathering of families desiring to establish a confessional, gospel-rooted, and mission-minded Presbyterian witness in Talaga. By God''s grace, the church has grown in worship, discipleship, fellowship, and outreach.', 'To glorify God by making disciples of Jesus Christ through worship, biblical teaching, fellowship, mercy ministry, and evangelism.', 'To be a faithful Presbyterian church where every generation is formed by Scripture, strengthened in grace, and sent into the world with the hope of Christ.', 'Biblical authority\nChrist-centered worship\nPrayerful dependence\nCovenant discipleship\nCompassionate mission\nFaithful stewardship', 'We believe in the authority of Holy Scripture, the sovereignty of God in salvation, the lordship of Jesus Christ, the work of the Holy Spirit, and the mission of the church to proclaim the gospel and nurture believers in truth and holiness.', 'Grace in Christ Presbyterian Church - Talaga, Talaga, Nueva Ecija, Philippines', 'info@gicptalaga.org', '09171234567', '(044) 123-4567', '<iframe title="Grace in Christ Presbyterian Church Map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3858.116!2d120.991!3d15.685" width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'https://facebook.com/gicptalaga', 'https://youtube.com/@gicptalaga', 'https://instagram.com/gicptalaga', 'Share your burdens with us. Our prayer team would be honored to pray with you and for you.', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `pastors` (`id`, `name`, `slug`, `position`, `bio`, `photo`, `is_featured`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Rev. Daniel Mendoza', 'rev-daniel-mendoza', 'Senior Pastor', 'Rev. Daniel Mendoza leads the congregation in expository preaching, shepherding, and pastoral care with a deep love for the gospel and the local church.', 'assets/images/placeholders/pastor.svg', 1, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Rev. Samuel Torres', 'rev-samuel-torres', 'Associate Pastor', 'Rev. Samuel Torres oversees discipleship, youth formation, and community outreach ministries.', 'assets/images/placeholders/pastor.svg', 1, 2, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Elder Josiah Ramos', 'elder-josiah-ramos', 'Ruling Elder', 'Elder Josiah Ramos serves in spiritual oversight, prayer, and leadership development.', 'assets/images/placeholders/pastor.svg', 0, 3, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(4, 'Deacon Nathan Cruz', 'deacon-nathan-cruz', 'Church Leader', 'Nathan Cruz coordinates mercy ministry and church operations with humility and diligence.', 'assets/images/placeholders/pastor.svg', 0, 4, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `ministries` (`id`, `name`, `slug`, `description`, `image`, `leader_name`, `meeting_schedule`, `is_featured`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Worship Ministry', 'worship-ministry', 'Leading the congregation in reverent, Christ-centered worship through music, liturgy, and service.', 'assets/images/placeholders/ministry.svg', 'Rev. Daniel Mendoza', 'Sundays, 8:00 AM rehearsal', 1, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Youth Ministry', 'youth-ministry', 'Equipping young people to know Christ, treasure Scripture, and serve the church with joyful faith.', 'assets/images/placeholders/ministry.svg', 'Rev. Samuel Torres', 'Saturdays, 3:00 PM', 1, 2, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Children''s Ministry', 'childrens-ministry', 'Helping children grow in biblical truth through lessons, songs, prayer, and loving care.', 'assets/images/placeholders/ministry.svg', 'Sis. Lydia Santos', 'Sundays, 9:30 AM', 1, 3, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(4, 'Women''s Fellowship', 'womens-fellowship', 'Gathering women for prayer, discipleship, encouragement, and service.', 'assets/images/placeholders/ministry.svg', 'Mrs. Esther Ramos', 'Second Friday, 6:30 PM', 1, 4, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(5, 'Men''s Fellowship', 'mens-fellowship', 'Calling men to faithful leadership in home, church, and community under the lordship of Christ.', 'assets/images/placeholders/ministry.svg', 'Mr. Nathan Cruz', 'First Saturday, 7:00 AM', 1, 5, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(6, 'Outreach Ministry', 'outreach-ministry', 'Mobilizing the church for evangelism, mercy ministry, and mission works in nearby communities.', 'assets/images/placeholders/ministry.svg', 'Rev. Samuel Torres', 'Third Saturday, 8:00 AM', 1, 6, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `sermons` (`id`, `title`, `slug`, `speaker`, `sermon_date`, `excerpt`, `content`, `youtube_url`, `featured_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Christ Our Sure Foundation', 'christ-our-sure-foundation', 'Rev. Daniel Mendoza', '2026-06-01', 'An exposition on the steadfast grace of Christ and the church built upon His promises.', 'In this sermon, the congregation was reminded that Christ remains the unshakable foundation of the church. We are called to trust Him, rest in His grace, and live as a people shaped by His Word.', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'assets/images/placeholders/sermon.svg', 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'A People Devoted to Prayer', 'a-people-devoted-to-prayer', 'Rev. Samuel Torres', '2026-05-25', 'A call for the church to be marked by dependence on God in every season.', 'Prayer is not a program but the posture of a dependent people. This sermon urged the church to cultivate homes, ministries, and worship shaped by confident prayer in Christ.', 'https://www.youtube.com/watch?v=ysz5S6PUM-U', 'assets/images/placeholders/sermon.svg', 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Walking in Gospel Unity', 'walking-in-gospel-unity', 'Rev. Daniel Mendoza', '2026-05-18', 'How the gospel creates peace, humility, and shared mission in the body of Christ.', 'The church is called to preserve the unity of the Spirit in the bond of peace. This message highlighted humility, patience, and truth as marks of Christian fellowship.', NULL, 'assets/images/placeholders/sermon.svg', 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `events` (`id`, `title`, `slug`, `summary`, `description`, `location`, `start_datetime`, `end_datetime`, `image`, `registration_enabled`, `registration_limit`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Family Worship and Fellowship Sunday', 'family-worship-and-fellowship-sunday', 'A church-wide Sunday gathering with lunch and fellowship after worship.', 'Join us for our Lord''s Day worship service followed by a congregational fellowship meal. Families, guests, and friends are warmly invited.', 'GICP Talaga Main Sanctuary', '2026-06-14 09:00:00', '2026-06-14 13:00:00', 'assets/images/placeholders/event.svg', 1, 100, 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Youth Bible Study Retreat', 'youth-bible-study-retreat', 'A weekend retreat focused on Scripture, worship, and fellowship for the youth.', 'Our youth retreat will include Bible teaching, group discussions, prayer gatherings, and team-building activities centered on Christ.', 'Camp Grace, Nueva Ecija', '2026-06-21 08:00:00', '2026-06-22 17:00:00', 'assets/images/placeholders/event.svg', 1, 40, 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Community Outreach and Medical Mission', 'community-outreach-and-medical-mission', 'Serving the neighborhood with practical help, prayer, and gospel conversations.', 'The outreach ministry is organizing a community service day that includes prayer support, care packages, and a medical mission in partnership with volunteers.', 'Talaga Covered Court', '2026-07-05 07:30:00', '2026-07-05 15:00:00', 'assets/images/placeholders/event.svg', 1, 150, 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `event_registrations` (`id`, `event_id`, `full_name`, `email`, `mobile_number`, `notes`, `created_at`) VALUES
(1, 1, 'Maria Lopez', 'maria@example.com', '09170000001', 'Family of four attending fellowship lunch.', '2026-06-08 08:15:00'),
(2, 2, 'Joshua Perez', 'joshua@example.com', '09170000002', 'Needs transportation from Talaga.', '2026-06-08 08:20:00');

INSERT INTO `announcements` (`id`, `title`, `slug`, `summary`, `content`, `image`, `publish_date`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Prayer and Fasting Week', 'prayer-and-fasting-week', 'The church will observe a week of prayer and fasting for missions and renewal.', 'Members are encouraged to join the morning and evening prayer gatherings throughout the week as we seek the Lord together.', 'assets/images/placeholders/news.svg', '2026-06-07', 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Sunday School Volunteers Needed', 'sunday-school-volunteers-needed', 'We are inviting volunteers to support the children''s discipleship classes.', 'If you have a burden for teaching or assisting children during Sunday School, please contact the church office.', 'assets/images/placeholders/news.svg', '2026-06-05', 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Quarterly Congregational Meeting', 'quarterly-congregational-meeting', 'All members are encouraged to attend the upcoming congregational meeting.', 'The session will share ministry updates, stewardship reports, and prayer priorities for the coming quarter.', 'assets/images/placeholders/news.svg', '2026-06-03', 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `daily_verses` (`id`, `verse_text`, `reference`, `publish_date`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'But grow in the grace and knowledge of our Lord and Savior Jesus Christ. To him be glory both now and forever! Amen.', '2 Peter 3:18', '2026-06-08', 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'The steadfast love of the Lord never ceases; his mercies never come to an end.', 'Lamentations 3:22', '2026-06-09', 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `news_updates` (`id`, `title`, `slug`, `category`, `summary`, `content`, `image`, `publish_date`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Mission Team Visits Nearby Barangays', 'mission-team-visits-nearby-barangays', 'Mission Works', 'The outreach team shared the gospel, prayed with families, and distributed care packs in neighboring barangays.', 'Our mission team spent the weekend visiting homes, encouraging believers, and inviting families to join worship and Bible studies.', 'assets/images/placeholders/news.svg', '2026-06-06', 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Women''s Fellowship Encourages Young Mothers', 'womens-fellowship-encourages-young-mothers', 'Church Activities', 'A recent fellowship gathering focused on prayer, testimony, and practical support for young mothers.', 'The ministry shared devotional teaching, testimonies, and gift packs while building deeper fellowship and prayer support.', 'assets/images/placeholders/news.svg', '2026-06-04', 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Youth Outreach Through Music and Testimony', 'youth-outreach-through-music-and-testimony', 'Outreach Programs', 'Young people from the church led songs and shared testimonies during a local outreach event.', 'The youth ministry continues to grow in confidence and joy as they serve the church and witness to their peers.', 'assets/images/placeholders/news.svg', '2026-06-02', 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `galleries` (`id`, `title`, `slug`, `description`, `cover_image`, `album_date`, `category`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Resurrection Sunday Celebration', 'resurrection-sunday-celebration', 'Snapshots from Resurrection Sunday worship, choir presentations, and fellowship.', 'assets/images/placeholders/gallery.svg', '2026-04-20', 'Event Photos', 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Youth Ministry Weekend', 'youth-ministry-weekend', 'Photos from youth gatherings, worship, and Bible study sessions.', 'assets/images/placeholders/gallery.svg', '2026-05-10', 'Ministry Photos', 1, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Community Outreach Highlights', 'community-outreach-highlights', 'A visual recap of outreach and mission efforts around Talaga.', 'assets/images/placeholders/gallery.svg', '2026-05-24', 'Event Photos', 0, 'published', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `gallery_images` (`id`, `gallery_id`, `image_path`, `caption`, `display_order`, `created_at`) VALUES
(1, 1, 'assets/images/placeholders/gallery.svg', 'Congregation gathered for worship.', 1, '2026-06-08 08:00:00'),
(2, 1, 'assets/images/placeholders/gallery.svg', 'Choir leading with joyful praise.', 2, '2026-06-08 08:00:00'),
(3, 2, 'assets/images/placeholders/gallery.svg', 'Youth Bible discussion circle.', 1, '2026-06-08 08:00:00'),
(4, 2, 'assets/images/placeholders/gallery.svg', 'Youth worship and testimony.', 2, '2026-06-08 08:00:00'),
(5, 3, 'assets/images/placeholders/gallery.svg', 'Outreach volunteers preparing relief packs.', 1, '2026-06-08 08:00:00'),
(6, 3, 'assets/images/placeholders/gallery.svg', 'Prayer with community members.', 2, '2026-06-08 08:00:00');

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `label`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Grace in Christ Presbyterian Church - Talaga', 'general', 'Site Title', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'meta_description', 'Grace in Christ Presbyterian Church - Talaga official website and content management system.', 'general', 'Meta Description', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'hero_heading', 'Welcome to GICP Talaga', 'homepage', 'Hero Heading', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(4, 'hero_subheading', 'A Christ-centered Presbyterian church rooted in Scripture, grace, and mission.', 'homepage', 'Hero Subheading', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(5, 'hero_button_text', 'Join Us This Sunday', 'homepage', 'Hero Button Text', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(6, 'hero_button_link', 'contact', 'homepage', 'Hero Button Link', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(7, 'footer_text', 'Grace in Christ Presbyterian Church - Talaga. All rights reserved.', 'general', 'Footer Text', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(8, 'visitor_count', '128', 'analytics', 'Visitor Count', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(9, 'primary_color', '#123d73', 'branding', 'Primary Color', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(10, 'secondary_color', '#c7a756', 'branding', 'Secondary Color', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(11, 'contact_email', 'info@gicptalaga.org', 'contact', 'Contact Email', '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(12, 'contact_phone', '09171234567', 'contact', 'Contact Phone', '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `menu_items` (`id`, `label`, `url`, `icon`, `target`, `parent_id`, `sort_order`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'Home', '', 'bi-house', '_self', NULL, 1, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'About Us', 'about-us', 'bi-info-circle', '_self', NULL, 2, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Pastors', 'pastors', 'bi-people', '_self', NULL, 3, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(4, 'Ministries', 'ministries', 'bi-grid', '_self', NULL, 4, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(5, 'Sermons', 'sermons', 'bi-mic', '_self', NULL, 5, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(6, 'Events', 'events', 'bi-calendar-event', '_self', NULL, 6, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(7, 'News & Updates', 'news-updates', 'bi-newspaper', '_self', NULL, 7, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(8, 'Gallery', 'gallery', 'bi-images', '_self', NULL, 8, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(9, 'Contact', 'contact', 'bi-envelope', '_self', NULL, 9, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `service_schedules` (`id`, `title`, `day_name`, `time_range`, `description`, `display_order`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'Sunday Worship Service', 'Sunday', '9:00 AM - 11:00 AM', 'Corporate worship, prayer, Scripture reading, and expository preaching.', 1, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(2, 'Prayer Meeting', 'Wednesday', '6:30 PM - 7:30 PM', 'Midweek congregational prayer gathering.', 2, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00'),
(3, 'Sunday School', 'Sunday', '8:00 AM - 8:45 AM', 'Bible classes for children, youth, and adults.', 3, 1, '2026-06-08 08:00:00', '2026-06-08 08:00:00');

INSERT INTO `prayer_requests` (`id`, `full_name`, `email`, `mobile_number`, `request_text`, `status`, `is_private`, `created_at`, `updated_at`) VALUES
(1, 'Anonymous Member', 'member@example.com', '09170000003', 'Please pray for healing, wisdom, and peace for our family this week.', 'pending', 1, '2026-06-08 08:30:00', '2026-06-08 08:30:00');

INSERT INTO `newsletter_subscriptions` (`id`, `full_name`, `email`, `status`, `created_at`) VALUES
(1, 'Local Visitor', 'visitor@example.com', 'subscribed', '2026-06-08 08:35:00');

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `mobile_number`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Ana Cruz', 'ana@example.com', '09170000004', 'Sunday Visit Inquiry', 'We would like to visit on Sunday and ask about children''s classes.', 'new', '2026-06-08 08:40:00');

INSERT INTO `visitor_logs` (`id`, `ip_address`, `user_agent`, `visited_on`, `created_at`) VALUES
(1, '127.0.0.1', 'Mozilla/5.0', '2026-06-08', '2026-06-08 08:45:00');

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'seed', 'database', 'Initial CMS sample data imported.', '127.0.0.1', '2026-06-08 08:50:00');

SET FOREIGN_KEY_CHECKS = 1;