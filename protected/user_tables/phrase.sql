CREATE TABLE IF NOT EXISTS `phrase` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `global_phrase_id` int(11) NOT NULL,
    `content_id` int(11) DEFAULT NULL
);
