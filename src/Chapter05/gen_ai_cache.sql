CREATE TABLE `gen_ai_cache` (
  `cache_key` char(32) NOT NULL,
  `request_text` text NOT NULL,
  PRIMARY KEY (`cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
