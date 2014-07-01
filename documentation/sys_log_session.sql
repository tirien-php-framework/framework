SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sys_log_session`
-- ----------------------------
DROP TABLE IF EXISTS `sys_log_session`;
CREATE TABLE `sys_log_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `http_cookie` varchar(255) DEFAULT NULL,
  `http_host` varchar(255) DEFAULT NULL,
  `http_user_agent` varchar(255) DEFAULT NULL,
  `query_string` varchar(255) DEFAULT NULL,
  `redirect_status` varchar(255) DEFAULT NULL,
  `redirect_url` varchar(255) DEFAULT NULL,
  `remote_addr` varchar(255) DEFAULT NULL,
  `remote_port` varchar(255) DEFAULT NULL,
  `request_method` varchar(255) DEFAULT NULL,
  `request_uri` varchar(255) DEFAULT NULL,
  `script_filename` varchar(255) DEFAULT NULL,
  `server_addr` varchar(255) DEFAULT NULL,
  `server_port` varchar(255) DEFAULT NULL,
  `server_protocol` varchar(255) DEFAULT NULL,
  `server_software` varchar(255) DEFAULT NULL,
  `request_time` varchar(255) DEFAULT NULL,
  `argc` varchar(255) DEFAULT NULL,
  `dti` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;