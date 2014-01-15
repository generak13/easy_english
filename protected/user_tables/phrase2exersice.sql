CREATE TABLE IF NOT EXISTS `phrase2exersice` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `phrase_id` int(11) NOT NULL,
    `exercise_id` int(11) NOT NULL,
    FOREIGN KEY (phrase_id) REFERENCES phrase(id)
);
