ALTER TABLE `task` ADD CONSTRAINT task_pk PRIMARY KEY (`task_id`);

ALTER TABLE `task_lock` ADD CONSTRAINT task_lock_pk PRIMARY KEY (`lockId`); 