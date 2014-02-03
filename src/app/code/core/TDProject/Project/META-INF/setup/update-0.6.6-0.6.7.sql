--
-- Table structure for table `task_performance`
--

CREATE TABLE IF NOT EXISTS `task_performance` (
  `task_performance_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id_fk` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `minimum` int(11) NOT NULL,
  `normal` int(11) NOT NULL,
  `average` int(11) NOT NULL,
  `maximum` int(11) NOT NULL,
  PRIMARY KEY (`task_performance_id`),
  UNIQUE KEY `task_id_fk` (`task_id_fk`)
) ENGINE=ndbcluster  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;