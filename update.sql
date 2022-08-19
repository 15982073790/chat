ALTER TABLE `wolive_wechat_platform`
ADD `wx_id` varchar(60) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '公众号原始id' AFTER `business_id`,
CHANGE `app_id` `app_id` varchar(255) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '公众号appid' AFTER `wx_id`,
CHANGE `app_secret` `app_secret` varchar(255) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '公众号appsecret' AFTER `app_id`;
ALTER TABLE `wolive_wechat_platform`
ADD `wx_token` varchar(120) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '公众号token' AFTER `app_secret`,
ADD `wx_aeskey` varchar(120) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '消息加解密密钥(EncodingAESKey)' AFTER `wx_token`;
ALTER TABLE `wolive_question`
ADD `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1显示 0不显示',
COMMENT='常见问题表';
ALTER TABLE `wolive_group`
CHANGE `business_id` `business_id` int(11) unsigned NOT NULL DEFAULT '0' AFTER `groupname`,
ADD `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序';

ALTER TABLE `wolive_visiter`
ADD `extends` text NOT NULL DEFAULT '' COMMENT '浏览器扩展' AFTER `comment`;


-- 限制登录用户名唯一
ALTER TABLE `wolive_service`
ADD UNIQUE `user_name` (`user_name`),
DROP INDEX `user_name`;

-- 系统版本 DIY5.0.2前如果要升级都需要执行
ALTER TABLE `wolive_chats`
ADD `unstr` varchar(64) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '前端唯一字符串用于撤销使用',
COMMENT='消息表';
ALTER TABLE `wolive_chats`
ADD INDEX `visiter_id` (`visiter_id`),
ADD INDEX `service_id` (`service_id`),
ADD INDEX `business_id` (`business_id`),
ADD INDEX `unstr` (`unstr`);
-- LK_DIY5.0.8新增附件上传统计表
CREATE TABLE `wolive_attachment_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件id',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `url` varchar(600) NOT NULL DEFAULT '',
  `filemd5` varchar(64) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `inputtime` (`inputtime`) USING BTREE,
  KEY `fileext` (`fileext`) USING BTREE,
  KEY `uid` (`service_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件归档表';

ALTER TABLE `wolive_weixin`
    CHANGE `service_id` `business_id` int(11) NOT NULL COMMENT '商户ID' AFTER `wid`;
ALTER TABLE `wolive_weixin`
    ADD `subscribe` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否关注微信0未关注1已关注';
ALTER TABLE `wolive_weixin`
    ADD `subscribe_time` int(11) NOT NULL DEFAULT '0' COMMENT '关注时间';
ALTER TABLE `wolive_weixin`
    ADD INDEX `business_id` (`business_id`),
DROP INDEX `service_id`;
ALTER TABLE `wolive_wechat_platform`
    ADD `isscribe` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否开启引导关注1开启0关闭' AFTER `customer_tpl`;
-- LK_DIY5.0.9新增
ALTER TABLE `wolive_weixin`
    ADD `app_id` varchar(64) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '公众号appid' AFTER `business_id`;
ALTER TABLE `wolive_weixin`
    ADD INDEX `app_id` (`app_id`);

-- LK_DIY5.1.5新增
ALTER TABLE `wolive_visiter`
    ADD COLUMN `istop` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '1置顶展示0未置顶' AFTER `state`;
ALTER TABLE `wolive_vgroup`
    ADD COLUMN `bgcolor` CHAR(7) NOT NULL DEFAULT '#707070' AFTER `create_time`;

-- LK_DIY5.1.7新增
ALTER TABLE `wolive_reply`
    CHANGE `word` `word` text COLLATE 'utf8_general_ci' NOT NULL AFTER `id`;
ALTER TABLE `wolive_chats`
    CHANGE `unstr` `unstr` varchar(60) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '前端唯一字符串用于撤销使用' AFTER `direction`;
-- LK_DIY5.1.8新增
ALTER TABLE `wolive_reply`
    CHANGE COLUMN `service_id` `service_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `word`,
    ADD COLUMN `sort` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `service_id`;
ALTER TABLE `wolive_chats`
    ADD COLUMN `avatar` VARCHAR(1024) NOT NULL DEFAULT '' COMMENT '发送者头像' AFTER `direction`;
ALTER TABLE `wolive_weixin`
    ADD COLUMN `service_id` INT(11) NOT NULL COMMENT '客服ID' AFTER `business_id`;
-- LK_DIY5.1.9新增
ALTER TABLE `wolive_wechat_platform`
    ADD COLUMN `onesubtpl_id` VARCHAR(120) NOT NULL DEFAULT '' COMMENT '订阅消息ID' AFTER `customer_tpl`,
	ADD COLUMN `onesubtpl_title` VARCHAR(120) NOT NULL DEFAULT '' COMMENT '订阅消息标题' AFTER `onesubtpl_id`,
	ADD COLUMN `onesubtpl_desc` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '订阅消息内容' AFTER `onesubtpl_title`;
ALTER TABLE `wolive_visiter`
    ADD COLUMN `openid` VARCHAR(128) NOT NULL DEFAULT '' AFTER `name`;

-- LK_DIY6.0.1大改动 此次升级必须在config/database.php  // 填写数据库表前缀 'prefix'         => 'wolive_',
CREATE TABLE `wolive_login_log` (
`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`uid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆者ID',
`name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '登陆者账号',
`ip` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '登陆者IP',
`area` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '地址',
`login_side` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未知登陆方式1电脑登录2手机登录方式',
`source` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1管理员2客服人员',
`createtime` INT(10) NULL DEFAULT '0' COMMENT '登录时间',
PRIMARY KEY (`id`),
INDEX `source` (`source`)
)COMMENT='登录日志表' COLLATE='utf8_general_ci' ENGINE=MyISAM;

-- LK_DIY6.0.2
ALTER TABLE `wolive_business`
    ADD COLUMN `domain_entry` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '入口域名' AFTER `voice_address`,
    ADD COLUMN `domain_landing` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '落地域名' AFTER `domain_entry`;
CREATE TABLE `wolive_report` (
 `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 `type` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '投诉类型',
 `content` VARCHAR(1200) NOT NULL DEFAULT '' COMMENT '投诉内容',
 `contact` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '联系方式',
 `ip` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '投诉者IP',
 `business_id` INT(10) NOT NULL DEFAULT '0' COMMENT '商家ID',
 `createtime` INT(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
 PRIMARY KEY (`id`)
)COMMENT='访客投诉表' COLLATE='utf8_general_ci' ENGINE=MyISAM;

-- LK_DIY6.0.3
ALTER TABLE `wolive_queue`
    CHANGE COLUMN `visiter_id` `visiter_id` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '访客id' AFTER `qid`,
    CHANGE COLUMN `service_id` `service_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '客服id' AFTER `visiter_id`,
    CHANGE COLUMN `groupid` `groupid` INT(11) UNSIGNED NULL DEFAULT '0' COMMENT '客服分类id' AFTER `service_id`,
    CHANGE COLUMN `business_id` `business_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `groupid`,
    ADD COLUMN `lastpost` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后收发信息时间' AFTER `remind_comment`;