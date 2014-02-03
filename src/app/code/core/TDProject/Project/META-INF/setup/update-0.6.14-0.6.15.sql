--
-- add localization for json-errors
-- 
INSERT INTO `resource` (`resource_locale`, `key`, `message`) VALUES
('de_DE', 'message.SystemMessage', 'System-Meldung'),
('en_US', 'message.SystemMessage', 'system-message'),
('de_DE', 'taskDelete.deletingRootTaskNotPossible', 
'Es ist nicht möglich den Root-Task zu löschen.'),
('en_US', 'taskDelete.deletingRootTaskNotPossible', 
'It is not possible to delete the root-task.');


