CREATE TABLE `task` (
	`task_id` int(10) NOT NULL, 
	`task_id_fk` int(10) NULL,   
	`task_type_id_fk` int(10) NOT NULL,  
	`left_node` int(10) NULL, 
	`right_node` int(10) NULL,
	`order_number` int(10) NULL,
	`level` int(10) NULL,
	`name` varchar(255) NOT NULL, 
	`description` varchar(255) NOT NULL, 
	`billable` tinyint(1) NOT NULL default 0
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `task_lock` (
  `lockId` varchar(32) NOT NULL,
  `lockTable` varchar(32) NOT NULL,
  `lockStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ENGINE=InnoDB;