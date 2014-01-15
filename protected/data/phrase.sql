CREATE IF NOT EXISTS TABLE `phrase`
(
`id` int(11) NOT NULL AUTO_INCREMENT,
`global_phrase_id` int(11) DEFAULT NULL,
`content_id` int(11) DEFAULT NULL,
`context` varchar(255) DEFAULT NULL,
`text` varchar(255)
);