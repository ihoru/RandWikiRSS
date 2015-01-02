CREATE TABLE `randwikirss` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL DEFAULT '',
  `page_id` bigint(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `link` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`lang`),
  KEY (`page_id`),
  KEY (`time`)
);
