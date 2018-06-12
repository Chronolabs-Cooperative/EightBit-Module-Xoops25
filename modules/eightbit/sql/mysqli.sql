CREATE TABLE `8bit_albums` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `album` varchar(255) NOT NULL DEFAULT '',
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `tracks` int(12) NOT NULL DEFAULT '0',
  `artists` int(12) NOT NULL DEFAULT '0',
  `hits` int(32) NOT NULL DEFAULT '0',
  `bytes` mediumint(22) NOT NULL DEFAULT '0',
  `totalseconds` double(44,14) NOT NULL DEFAULT '0.00000000000000',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`album`) USING BTREE KEY_BLOCK_SIZE=16
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_albums_artists` (
  `id` mediumint(44) NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`albumid`,`artistid`) USING BTREE KEY_BLOCK_SIZE=8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_albums_tracks` (
  `id` mediumint(44) NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(22) NOT NULL DEFAULT '0',
  `trackid` mediumint(128) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`albumid`,`trackid`) USING BTREE KEY_BLOCK_SIZE=8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_alpha` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `type` enum('album', 'artist', 'track') NOT NULL DEFAULT 'album',
  `alpha` varchar(1) NOT NULL DEFAULT '',
  `bravo` varchar(2) NOT NULL DEFAULT '',
  `charley` varchar(3) NOT NULL DEFAULT '',
  `tracks` int(12) NOT NULL DEFAULT '0',
  `artists` int(12) NOT NULL DEFAULT '0',
  `albums` int(12) NOT NULL DEFAULT '0',
  `hits` int(32) NOT NULL DEFAULT '0',
  `bytes` mediumint(22) NOT NULL DEFAULT '0',
  `totalseconds` double(44,14) NOT NULL DEFAULT '0.00000000000000',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`alpha`,`bravo`,`charley`) USING BTREE KEY_BLOCK_SIZE=16
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_alpha_albums` (
  `id` mediumint(44) NOT NULL AUTO_INCREMENT,
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`alphaid`,`artistid`) USING BTREE KEY_BLOCK_SIZE=8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_alpha_artists` (
  `id` mediumint(44) NOT NULL AUTO_INCREMENT,
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`alphaid`,`artistid`) USING BTREE KEY_BLOCK_SIZE=8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_alpha_tracks` (
  `id` mediumint(44) NOT NULL AUTO_INCREMENT,
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `trackid` mediumint(128) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`alphaid`,`trackid`) USING BTREE KEY_BLOCK_SIZE=8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_artists` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `type` enum('alone', 'chaining') NOT NULL DEFAULT 'alone',
  `artist` varchar(255) NOT NULL DEFAULT '',
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `albums` int(12) NOT NULL DEFAULT '0',
  `tracks` int(12) NOT NULL DEFAULT '0',
  `hits` int(32) NOT NULL DEFAULT '0',
  `bytes` mediumint(22) NOT NULL DEFAULT '0',
  `totalseconds` double(44,14) NOT NULL DEFAULT '0.00000000000000',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`artist`) USING BTREE KEY_BLOCK_SIZE=16
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_artists_chaining` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  `childid` mediumint(22) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`artistid`,`childid`,`alphaid`) USING BTREE KEY_BLOCK_SIZE=16
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_hashing` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `type` enum('track','artist','album','repository','8bit') NOT NULL DEFAULT '8bit',
  `repoid` mediumint(22) NOT NULL DEFAULT '0',
  `itemid` mediumint(22) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`hash`,`itemid`,`type`) USING BTREE KEY_BLOCK_SIZE=32
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_repositories` (
  `id` mediumint(22) NOT NULL AUTO_INCREMENT,
  `type` enum('git','svn','webdav') NOT NULL DEFAULT 'svn',
  `url` varchar(255) NOT NULL DEFAULT '',
  `raw` varchar(255) NOT NULL DEFAULT '',
  `json_url` varchar(255) NOT NULL DEFAULT '',
  `json_albums` varchar(255) NOT NULL DEFAULT '',
  `json_artists` varchar(255) NOT NULL DEFAULT '',
  `json_tracks` varchar(255) NOT NULL DEFAULT '',
  `last` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`type`,`last`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `8bit_repositories` VALUES (1,'svn','svn://svn.code.sf.net/p/chronolabs-cooperative/8bit/','https://sourceforge.net/p/chronolabs-cooperative/8bit/HEAD/tree/%s?format=raw','ssh+svn://chronolabscoop@svn.code.sf.net/p/chronolabs-cooperative/8bit/json/','https://sourceforge.net/p/chronolabs-cooperative/8bit/HEAD/tree/json/albums.json?format=raw','https://sourceforge.net/p/chronolabs-cooperative/8bit/HEAD/tree/json/artists.json?format=raw','https://sourceforge.net/p/chronolabs-cooperative/8bit/HEAD/tree/json/tracks.json?format=raw',0);

CREATE TABLE `8bit_tracks` (
  `id` mediumint(128) NOT NULL AUTO_INCREMENT,
  `mode` enum('online', 'offline') NOT NULL DEFAULT 'online',
  `sha1` varchar(44) NOT NULL DEFAULT '',
  `alphaid` mediumint(22) NOT NULL DEFAULT '0',
  `repoid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  `albumid` mediumint(22) NOT NULL DEFAULT '0',
  `year` int(4) unsigned zerofill NOT NULL DEFAULT '2018',
  `title` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `file` varchar(255) NOT NULL,
  `minutes` int(4) NOT NULL DEFAULT '0',
  `seconds` int(2) unsigned zerofill NOT NULL DEFAULT '00',
  `bitrate` double(44,14) NOT NULL DEFAULT '0.00000000000000',
  `playseconds` double(44,14) NOT NULL DEFAULT '0.00000000000000',
  `hits` int(32) NOT NULL DEFAULT '0',
  `bytes` mediumint(22) NOT NULL DEFAULT '0',
  `created` int(12) NOT NULL DEFAULT '0',
  `played` int(12) NOT NULL DEFAULT '0',
  `packed` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`artistid`,`albumid`,`year`,`title`,`path`,`file`,`minutes`,`seconds`,`bitrate`,`playseconds`,`packed`,`created`,`played`,`sha1`) USING BTREE KEY_BLOCK_SIZE=32
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `8bit_tracks_emails` (
  `id` mediumint(128) NOT NULL AUTO_INCREMENT,
  `repoid` mediumint(22) NOT NULL DEFAULT '0',
  `artistid` mediumint(22) NOT NULL DEFAULT '0',
  `albumid` mediumint(22) NOT NULL DEFAULT '0',
  `trackid` mediumint(128) NOT NULL DEFAULT '0',
  `uid` int(12) NOT NULL DEFAULT '0',
  `to_name` varchar(128) NOT NULL DEFAULT '',
  `to_email` varchar(128) NOT NULL DEFAULT '',
  `to_band` varchar(128) NOT NULL DEFAULT '',
  `to_mobile` varchar(128) NOT NULL DEFAULT '',
  `sent` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SEARCH` (`to_email`,`sent`,`uid`,`trackid`,`albumid`,`artistid`) USING BTREE KEY_BLOCK_SIZE=16
) ENGINE=InnoDB DEFAULT CHARSET=utf8;