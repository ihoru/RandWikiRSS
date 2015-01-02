CREATE TABLE IF NOT EXISTS `randwikirss` (
  `id` integer PRIMARY KEY ASC AUTOINCREMENT,
  `page_id` integer NOT NULL default '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `link` text NOT NULL,
  `text` text NOT NULL,
  `time` integer NOT NULL default '0'
);
