--
-- Table structure for table `project_calculation_export`
--

CREATE TABLE IF NOT EXISTS `project_calculation_export` (
  `project_calculation_export_id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id_fk` int(10) NOT NULL,
  `user_id_fk` int(10) NOT NULL,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`project_calculation_export_id`),
  KEY `project_calculation_export_idx_01` (`project_id_fk`),
  KEY `project_calculation_export_idx_02` (`user_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5;

INSERT INTO `resource` (`resource_locale`, `key`, `message`) VALUES
('de_DE', 'page.content.toolbar.export', 'Export'),
('en_US', 'page.content.toolbar.export', 'Export'),
('de_DE', 'page.content.tabs.settings.settings.mediaDirectory', 'Verzeichnis für Medien'),
('en_US', 'page.content.tabs.settings.settings.mediaDirectory', 'Media Directory');