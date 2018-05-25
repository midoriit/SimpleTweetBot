CREATE TABLE `tweets_table` (
  `id` bigint(20) unsigned NOT NULL,    -- ユニークなID
  `tweet` mediumtext NOT NULL,          -- ツイート本文
  `count` int(10) NOT NULL DEFAULT '0', -- ツイートした回数
  `last` date DEFAULT NULL              -- 最後にツイートした日付
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
