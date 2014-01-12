CREATE IF NOT EXISTS TABLE `translation`
(
`id` int(11) NOT NULL AUTO_INCREMENT,
`global_transltion_id` int(11) DEFAULT NULL,
`text` varchar(255)
);