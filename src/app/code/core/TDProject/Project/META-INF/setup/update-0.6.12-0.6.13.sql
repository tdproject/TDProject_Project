ALTER TABLE `task_performance` 
	ADD `allow_overbooking` TINYINT( 1 ) NOT NULL DEFAULT '0',
	ADD `finished` TINYINT( 1 ) NOT NULL DEFAULT '0';
	
INSERT INTO `resource` (`resource_locale`, `key`, `message`) VALUES
    ('de_DE', 'content.task.allowOverbooking', 'Überbuchen erlauben'),
    ('de_DE', 'content.task.finished', 'Abgeschlossen'),
    ('en_US', 'content.task.allowOverbooking', 'Allow overbooking'),
    ('en_US', 'content.task.finished', 'Finished');