CREATE TABLE `project_user` (
	`project_user_id` INT NOT NULL AUTO_INCREMENT ,
	`project_id_fk` INT NOT NULL ,
	`user_id_fk` INT NOT NULL ,
	PRIMARY KEY (`project_user_id`)
) ENGINE = ndbcluster;

ALTER TABLE `project_user` ADD INDEX `project_user_idx_01` (`project_id_fk`);
ALTER TABLE `project_user` ADD INDEX `project_user_idx_02` (`user_id_fk`);

INSERT INTO
    `project_user` (`project_id_fk`, `user_id_fk`) 
SELECT
    t4.`project_id_fk`,
    t3.`user_id_fk`
FROM 
    `task` t1,
    `task` t2, 
    `task_user` t3, 
    `project_task` t4
WHERE 
    t1.`task_id` = t3.`task_id_fk` AND
    t2.`task_id` = t1.`task_id_fk` AND 
    t4.`task_id_fk` = t2.`task_id`
GROUP BY t3.`user_id_fk`, t4.`project_id_fk`;

