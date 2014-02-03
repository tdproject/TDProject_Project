ALTER TABLE `task_user` 
    ADD `issue` VARCHAR(45) NULL;

ALTER TABLE  `project_calculation_export`
	ADD `created_at` INT(10) NOT NULL;