ALTER TABLE `estimation` CHANGE `complexity` `complexity` VARCHAR(25) NOT NULL;
ALTER TABLE `estimation` CHANGE `unit` `unit` VARCHAR(25) NOT NULL;

ALTER TABLE 
	`tdproject`.`estimation` 
ADD 
	UNIQUE `estimation_uidx_01` (`user_id_fk` , `task_id_fk`);