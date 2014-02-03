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
    
UPDATE 
    `user` t1, 
    `task_user` t2 
SET 
    t2.`username` = t1.`username` 
WHERE
    t2.`user_id_fk` = t1.`user_id`;
    
DELETE FROM `resource` WHERE `key` = 'page.content.tabs.userperformance';
DELETE FROM `resource` WHERE `key` = 'page.content.project.logging.loggingGrid.header.row.projectName';
DELETE FROM `resource` WHERE `key` = 'page.content.project.logging.loggingGrid.footer.row.projectName';
DELETE FROM `resource` WHERE `key` = 'page.content.project.logging.loggingGrid.footer.row.username';
DELETE FROM `resource` WHERE `key` = 'page.content.project.logging.loggingGrid.header.row.username';
DELETE FROM `resource` WHERE `message` = 'tab.label.user.performance';
DELETE FROM `resource` WHERE `message` = 'logging.overview.grid.label.username';
DELETE FROM `resource` WHERE `message` = 'logging.overview.grid.label.projectName';
    
INSERT INTO `resource` (`resource_locale`, `key`, `message`) VALUES
    ('de_DE', 'page.content.project.logging.loggingGrid.footer.row.username', 'Benutzer'),
    ('en_US', 'page.content.project.logging.loggingGrid.footer.row.username', 'Username'),
    ('de_DE', 'page.content.project.logging.loggingGrid.header.row.username', 'Benutzer'),
    ('en_US', 'page.content.project.logging.loggingGrid.header.row.username', 'Username'),
    ('de_DE', 'page.content.project.logging.loggingGrid.footer.row.projectName', 'Projekt'),
    ('en_US', 'page.content.project.logging.loggingGrid.footer.row.projectName', 'Project'),
    ('de_DE', 'page.content.project.logging.loggingGrid.header.row.projectName', 'Projekt'),
    ('en_US', 'page.content.project.logging.loggingGrid.header.row.projectName', 'Project'),
    ('de_DE', 'page.content.tabs.userperformance', 'Benutzer Performance'),
    ('en_US', 'page.content.tabs.userperformance', 'User Performance');