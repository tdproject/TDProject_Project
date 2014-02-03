ALTER TABLE `task_user` 
	ADD `task_name` VARCHAR(255) NOT NULL AFTER `user_id_fk`,
	ADD `project_name` VARCHAR(255) NOT NULL AFTER `task_name`;
	
UPDATE 
	`task` t1, 
	`task_user` t2 
SET 
	t2.`task_name` = t1.`name` 
WHERE
	t2.`task_id_fk` = t1.`task_id`;
	
UPDATE 
	`task` t1,
   	`task` t2, 
   	`task_user` t3, 
   	`project_task` t4, 
   	`project` t5 
SET 
   	t3.`project_name` = t5.`name` 
WHERE 
   	t1.`task_id` = t3.`task_id_fk` AND
   	t2.`task_id` = t1.`task_id_fk` AND 
   	t4.`task_id_fk` = t2.`task_id` AND 
   	t5.`project_id` = t4.`project_id_fk`;