CREATE TABLE IF NOT EXISTS `cat` (
  `id` int(11) NOT NULL auto_increment,
  `radio` varchar(250) NOT NULL,
  `frequency` int(11) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;