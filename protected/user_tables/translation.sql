CREATE TABLE IF NOT EXISTS `translation` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `global_translation_id` NOT NULL,
    `context` DEFAULT NULL
)
