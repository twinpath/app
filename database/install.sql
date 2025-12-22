-- Database Export for Direct Import (e.g., cPanel/phpMyAdmin)
-- Includes Schema and Initial Data (Roles & Admin User)

-- Table structure for `roles`
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', NOW(), NOW()),
(2, 'customer', 'Customer', NOW(), NOW());

-- Table structure for `users`
CREATE TABLE `users` (
  `id` varchar(32) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `phone` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city_state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `x_link` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `google_token` text DEFAULT NULL,
  `google_refresh_token` text DEFAULT NULL,
  `github_id` varchar(255) DEFAULT NULL,
  `github_token` text DEFAULT NULL,
  `github_refresh_token` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin User (Password: password)
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
('ADMIN000000000000000000000000001', 'Admin', 'User', 'admin@dyzulk.com', '$2y$12$R.SjA7Gk/9l7HlA.zC6iGOJbA5HkXfLrTYDR.SjA7Gk/9l7HlA.zC6iG', 1, 'active', NOW(), NOW());

-- Table structure for `api_keys`
CREATE TABLE `api_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(64) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_keys_key_unique` (`key`),
  KEY `api_keys_user_id_foreign` (`user_id`),
  CONSTRAINT `api_keys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `ca_certificates`
CREATE TABLE `ca_certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `ca_type` varchar(255) NOT NULL,
  `cert_content` text NOT NULL,
  `key_content` text NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ca_certificates_uuid_unique` (`uuid`),
  UNIQUE KEY `ca_certificates_ca_type_unique` (`ca_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `certificates`
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `san` text DEFAULT NULL,
  `key_bits` int(11) NOT NULL DEFAULT 2048,
  `serial_number` varchar(255) NOT NULL,
  `cert_content` text NOT NULL,
  `key_content` text NOT NULL,
  `csr_content` text DEFAULT NULL,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_to` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_uuid_unique` (`uuid`),
  UNIQUE KEY `certificates_serial_number_unique` (`serial_number`),
  KEY `certificates_user_id_foreign` (`user_id`),
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `cache`, `jobs`, `sessions`, etc.
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(32) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES 
(1,'0000_00_00_000000_create_roles_table',1),
(2,'0001_01_01_000000_create_users_table',1),
(3,'0001_01_01_000001_create_cache_table',1),
(4,'0001_01_01_000002_create_jobs_table',1),
(5,'2025_12_21_051706_create_ca_certificates_table',1),
(6,'2025_12_21_051735_create_certificates_table',1),
(7,'2025_12_21_161950_create_login_histories_table',1),
(8,'2025_12_22_012656_add_status_to_users_table',1),
(9,'2025_12_22_025212_create_api_keys_table',1),
(10,'2025_12_22_030724_add_is_active_to_api_keys_table',1);
