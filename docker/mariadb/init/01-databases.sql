# noinspection SqlNoDataSourceInspectionForFile

# Create databases
CREATE DATABASE IF NOT EXISTS `ssl_search` DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_520_ci;
CREATE DATABASE IF NOT EXISTS `ssl_search_test` DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_520_ci;
GRANT ALL ON `ssl_search_test`.* TO 'root'@'%';
FLUSH PRIVILEGES;
