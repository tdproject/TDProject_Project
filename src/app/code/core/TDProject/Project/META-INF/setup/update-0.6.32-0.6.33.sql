ALTER TABLE `task_user` 
	ADD `to_book` INT NOT NULL AFTER `until`,
	ADD `to_account` INT NOT NULL AFTER `to_book`,
	ADD `costs` INT NOT NULL AFTER `to_account`;
	
UPDATE `task_user`
    SET `to_book` = `until` - `from`;
    
UPDATE `task_user`
    SET `to_account` = `until` - `from`;
    
UPDATE 
	`task_user` a,
	`user` b
SET 
    a.`costs` = b.`rate` * (a.`to_book` / 3600)
WHERE
	b.`user_id` = a.`user_id_fk`;