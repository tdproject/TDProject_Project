ALTER TABLE `task_user` 
	ADD `project_id_fk` INT NOT NULL AFTER `user_id_fk`;
	
UPDATE 
	`task` t1,
   	`task` t2, 
   	`task_user` t3, 
   	`project_task` t4, 
   	`project` t5 
SET 
   	t3.`project_id_fk` = t5.`project_id` 
WHERE 
   	t1.`task_id` = t3.`task_id_fk` AND
   	t2.`task_id` = t1.`task_id_fk` AND 
   	t4.`task_id_fk` = t2.`task_id` AND 
   	t5.`project_id` = t4.`project_id_fk`;
   	
ALTER TABLE `task_user` 
    ADD `username` VARCHAR(255) NOT NULL AFTER `project_name`;
    
UPDATE 
    `user` t1, 
    `task_user` t2 
SET 
    t2.`username` = t1.`username` 
WHERE
    t2.`user_id_fk` = t1.`user_id`;