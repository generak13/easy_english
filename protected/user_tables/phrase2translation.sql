CREATE TABLE IF NOT EXISTS `phrase2translation` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `phrase_id` int(11) NOT NULL,
    `translation_id` int(11) NOT NULL,
    FOREIGN KEY (phrase_id) REFERENCES phrase(id),
    FOREIGN KEY (translation_id) REFERENCES translation(id)
);
