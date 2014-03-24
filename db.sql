create database `newyearstweet`;

CREATE TABLE `newyearstweet` (
  `id` bigint(20) NOT NULL,
  `oauth_token` char(255) DEFAULT NULL,
  `oauth_token_secret` char(255) DEFAULT NULL,
  `tweet` char(255) DEFAULT NULL,
  `timezone` char(255) DEFAULT NULL,
  `sent` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;