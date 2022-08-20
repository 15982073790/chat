SET NAMES utf8mb4;
SET
FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wolive_admin
-- ----------------------------
DROP TABLE IF EXISTS `wolive_admin`;
CREATE TABLE `wolive_admin`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `username`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `addtime`       int(11) NOT NULL DEFAULT 0,
    `is_delete`     smallint(6) NOT NULL DEFAULT 0,
    `app_max_count` int(11) NOT NULL DEFAULT 0,
    `permission`    longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    `remark`        varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `expire_time`   int(11) NOT NULL DEFAULT 0 COMMENT '账户有效期至，0表示永久',
    `mobile`        varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_admin_token
-- ----------------------------
DROP TABLE IF EXISTS `wolive_admin_token`;
CREATE TABLE `wolive_admin_token`
(
    `token`      varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Token',
    `user_id`    int(11) UNSIGNED NOT NULL DEFAULT 0,
    `createtime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
    `expiretime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '过期时间',
    PRIMARY KEY (`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Token表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_business
-- ----------------------------
DROP TABLE IF EXISTS `wolive_business`;
CREATE TABLE `wolive_business`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `business_name`     varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商家标识符',
    `logo`              varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `copyright`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '底部版权信息',
    `admin_id`          int(11) NOT NULL DEFAULT 0,
    `video_state`       enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'close' COMMENT '是否开启视频',
    `voice_state`       enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open' COMMENT '是否开启提示音',
    `audio_state`       enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'close' COMMENT '是否开启音频',
    `template_state`    enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'close' COMMENT '是否开启模板消息',
    `distribution_rule` enum('auto','claim') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto' COMMENT 'claim:认领，auto:自动分配',
    `voice_address`     varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '/upload/voice/default.mp3' COMMENT '提示音文件地址',
    `domain_entry`      VARCHAR(200)                                            NOT NULL DEFAULT '' COMMENT '入口域名',
    `domain_landing`    VARCHAR(200)                                            NOT NULL DEFAULT '' COMMENT '落地域名',
    `remark`            varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `expire_time`       int(11) NOT NULL DEFAULT 0,
    `max_count`         int(11) NOT NULL DEFAULT 0,
    `push_url`          varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '推送url',
    `state`             enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open' COMMENT '\' open \': 打开该商户 ，‘close’：禁止该商户',
    `is_recycle`        tinyint(2) NOT NULL DEFAULT 0,
    `is_delete`         tinyint(2) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX               `bussiness`(`business_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact COMMENT '商家表';

-- ----------------------------
-- Table structure for wolive_chats
-- ----------------------------
DROP TABLE IF EXISTS `wolive_chats`;
CREATE TABLE `wolive_chats`
(
    `cid`         int(11) NOT NULL AUTO_INCREMENT,
    `visiter_id`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '访客id',
    `service_id`  int(11) NOT NULL COMMENT '客服id',
    `business_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家id',
    `content`     mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '内容',
    `timestamp`   int(11) NOT NULL,
    `state`       enum('readed','unread') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'unread' COMMENT 'unread 未读；readed 已读',
    `direction`   enum('to_visiter','to_service') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `unstr`       varchar(60) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '前端唯一字符串用于撤销使用',
    `avatar`      VARCHAR(1024)                                           NOT NULL DEFAULT '' COMMENT '发送者头像',
    PRIMARY KEY (`cid`) USING BTREE,
    INDEX         `visiter_id` (`visiter_id`),
    INDEX         `service_id` (`service_id`),
    INDEX         `business_id` (`business_id`),
    INDEX         `unstr` (`unstr`)
) AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic COMMENT '消息表';

-- ----------------------------
-- Table structure for wolive_comment
-- ----------------------------
DROP TABLE IF EXISTS `wolive_comment`;
CREATE TABLE `wolive_comment`
(
    `id`           int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id`  int(11) NOT NULL DEFAULT 0,
    `service_id`   int(11) NOT NULL DEFAULT 0,
    `group_id`     int(11) NOT NULL DEFAULT 0,
    `visiter_id`   varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `visiter_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `word_comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文字评价',
    `add_time`     timestamp                                               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_comment_detail
-- ----------------------------
DROP TABLE IF EXISTS `wolive_comment_detail`;
CREATE TABLE `wolive_comment_detail`
(
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `comment_id` int(11) UNSIGNED NOT NULL,
    `title`      varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `score`      tinyint(4) NOT NULL DEFAULT 1 COMMENT '分数',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_comment_setting
-- ----------------------------
DROP TABLE IF EXISTS `wolive_comment_setting`;
CREATE TABLE `wolive_comment_setting`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `title`       varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '评价说明',
    `comments`    text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '评价条目',
    `word_switch` enum('close','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'close',
    `word_title`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
    `add_time`    timestamp                                               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_group
-- ----------------------------
DROP TABLE IF EXISTS `wolive_group`;
CREATE TABLE `wolive_group`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `groupname`   varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `business_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `sort`        int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_message
-- ----------------------------
DROP TABLE IF EXISTS `wolive_message`;
CREATE TABLE `wolive_message`
(
    `mid`         int(11) NOT NULL AUTO_INCREMENT,
    `content`     text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '留言内容',
    `name`        varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '留言人姓名',
    `moblie`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '留言人电话',
    `email`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '留言人邮箱',
    `business_id` int(11) NULL DEFAULT 0,
    `timestamp`   timestamp                                               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`mid`) USING BTREE,
    INDEX         `timestamp`(`timestamp`) USING BTREE,
    INDEX         `web`(`business_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_option
-- ----------------------------
DROP TABLE IF EXISTS `wolive_option`;
CREATE TABLE `wolive_option`
(
    `id`          bigint(20) NOT NULL AUTO_INCREMENT,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `group`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `title`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `value`       longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX         `business_id`(`business_id`) USING BTREE,
    INDEX         `group`(`group`) USING BTREE,
    INDEX         `name`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_question
-- ----------------------------
DROP TABLE IF EXISTS `wolive_question`;
CREATE TABLE `wolive_question`
(
    `qid`         int(11) NOT NULL AUTO_INCREMENT,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `question`    longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `keyword`     varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
    `sort`        int(11) NOT NULL DEFAULT 0,
    `answer`      longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `answer_read` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `status`      tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1显示 0不显示',
    PRIMARY KEY (`qid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '常见问题表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_queue
-- ----------------------------
DROP TABLE IF EXISTS `wolive_queue`;
CREATE TABLE `wolive_queue`
(
    `qid`            int(11) NOT NULL AUTO_INCREMENT,
    `visiter_id`     VARCHAR(200) NOT NULL DEFAULT '' COMMENT '访客id',
    `service_id`     int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '客服id',
    `groupid`        int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '客服分类id',
    `business_id`    int(11) UNSIGNED NOT NULL DEFAULT '0' DEFAULT 0,
    `state`          enum('normal','complete','in_black_list') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'normal' COMMENT 'normal：正常接入,‘complete’:已经解决，‘in_black_list’:黑名单',
    `timestamp`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `remind_tpl`     tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否已发送模板消息',
    `remind_comment` tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否已推送评价',
    `lastpost`       INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后收发信息时间',
    PRIMARY KEY (`qid`) USING BTREE,
    INDEX            `se`(`service_id`) USING BTREE,
    INDEX            `vi`(`visiter_id`) USING BTREE,
    INDEX            `business`(`business_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会话表(排队表)' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_reply
-- ----------------------------
DROP TABLE IF EXISTS `wolive_reply`;
CREATE TABLE `wolive_reply`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `word`       text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快捷用语',
    `service_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `tag`        varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `sort`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
-- ----------------------------
-- Table structure for wolive_rest_setting
-- ----------------------------
DROP TABLE IF EXISTS `wolive_rest_setting`;
CREATE TABLE `wolive_rest_setting`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `state`       enum('open','close') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
    `start_time`  time NULL DEFAULT NULL,
    `end_time`    time NULL DEFAULT NULL,
    `week`        varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
    `reply`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `name_state`  enum('open','close') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
    `tel_state`   enum('open','close') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
    `add_time`    timestamp                                               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_sentence
-- ----------------------------
DROP TABLE IF EXISTS `wolive_sentence`;
CREATE TABLE `wolive_sentence`
(
    `sid`        int(11) NOT NULL AUTO_INCREMENT,
    `content`    text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '内容',
    `service_id` int(11) NOT NULL COMMENT '所属客服id',
    `state`      enum('using','unuse') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'unuse' COMMENT 'unuse: 未使用 ，using：使用中',
    PRIMARY KEY (`sid`) USING BTREE,
    INDEX        `se`(`service_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_service
-- ----------------------------
DROP TABLE IF EXISTS `wolive_service`;
CREATE TABLE `wolive_service`
(
    `service_id`    int(11) NOT NULL AUTO_INCREMENT,
    `user_name`     varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '用户名',
    `nick_name`     varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '昵称',
    `password`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '密码',
    `groupid`       varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '客服分类id',
    `phone`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '手机',
    `open_id`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
    `email`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '邮箱',
    `business_id`   int(11) NOT NULL DEFAULT 0,
    `avatar`        varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '/assets/images/admin/avatar-admin2.png' COMMENT '头像',
    `level`         enum('super_manager','manager','service') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'service' COMMENT 'super_manager: 超级管理员，manager：商家管理员 ，service：普通客服',
    `parent_id`     int(11) NOT NULL DEFAULT 0 COMMENT '所属商家管理员id',
    `offline_first` tinyint(2) NOT NULL DEFAULT 0,
    `state`         enum('online','offline') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'offline' COMMENT 'online：在线，offline：离线',
    PRIMARY KEY (`service_id`) USING BTREE,
    UNIQUE INDEX `user_name`(`user_name`) USING BTREE,
    INDEX           `pid`(`parent_id`) USING BTREE,
    INDEX           `web`(`business_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台客服表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_storage
-- ----------------------------
DROP TABLE IF EXISTS `wolive_storage`;
CREATE TABLE `wolive_storage`
(
    `id`       int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `admin_id` int(11) NOT NULL,
    `type`     tinyint(1) NOT NULL COMMENT '存储类型：1=本地，2=阿里云，3=腾讯云，4=七牛',
    `config`   text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `status`   tinyint(1) NOT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_tablist
-- ----------------------------
DROP TABLE IF EXISTS `wolive_tablist`;
CREATE TABLE `wolive_tablist`
(
    `tid`          int(11) NOT NULL AUTO_INCREMENT,
    `title`        varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'tab的名称',
    `content_read` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    `content`      text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `business_id`  int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_vgroup
-- ----------------------------
DROP TABLE IF EXISTS `wolive_vgroup`;
CREATE TABLE `wolive_vgroup`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `service_id`  int(11) NOT NULL DEFAULT 0,
    `group_name`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `create_time` timestamp                                               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status`      tinyint(4) NOT NULL DEFAULT 1,
    `bgcolor`     CHAR(7)                                                 NOT NULL DEFAULT '#707070',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_visiter
-- ----------------------------
DROP TABLE IF EXISTS `wolive_visiter`;
CREATE TABLE `wolive_visiter`
(
    `vid`          int(11) NOT NULL AUTO_INCREMENT,
    `visiter_id`   varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '访客id',
    `visiter_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '访客名称',
    `channel`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '用户游客频道',
    `avatar`       varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像',
    `name`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '' COMMENT '用户自己填写的姓名',
    `openid`       VARCHAR(128)                                             NOT NULL DEFAULT '',
    `tel`          varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci   NOT NULL DEFAULT '' COMMENT '用户自己填写的电话',
    `login_times`  int(11) NOT NULL DEFAULT 1 COMMENT '登录次数',
    `connect`      text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '联系方式',
    `comment`      text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
    `extends`      text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '浏览器扩展',
    `ip`           varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '访客ip',
    `from_url`     varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL COMMENT '访客浏览地址',
    `msg_time`     timestamp NULL DEFAULT NULL,
    `timestamp`    timestamp                                                NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '访问时间',
    `business_id`  int(11) NOT NULL DEFAULT 0,
    `state`        enum('online','offline') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'offline' COMMENT 'offline：离线，online：在线',
    `istop`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1置顶展示0未置顶',
    PRIMARY KEY (`vid`) USING BTREE,
    UNIQUE INDEX `id`(`visiter_id`, `business_id`) USING BTREE,
    INDEX          `visiter`(`visiter_id`) USING BTREE,
    INDEX          `time`(`timestamp`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_visiter_vgroup
-- ----------------------------
DROP TABLE IF EXISTS `wolive_visiter_vgroup`;
CREATE TABLE `wolive_visiter_vgroup`
(
    `vid`         int(11) NOT NULL,
    `business_id` int(11) NOT NULL DEFAULT 0,
    `service_id`  int(11) NOT NULL DEFAULT 0,
    `group_id`    int(11) NOT NULL DEFAULT 0,
    `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`vid`, `group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wolive_wechat_platform
-- ----------------------------
DROP TABLE IF EXISTS `wolive_wechat_platform`;
CREATE TABLE `wolive_wechat_platform`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `business_id`     int(11) NOT NULL DEFAULT 0 COMMENT '客服系统id',
    `wx_id`           varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '' COMMENT '公众号原始id',
    `app_id`          varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公众号appid',
    `app_secret`      varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公众号appsecret',
    `wx_token`        varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公众号token',
    `wx_aeskey`       varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '消息加解密密钥(EncodingAESKey)',
    `visitor_tpl`     varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新访客模板消息',
    `msg_tpl`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '新消息提示模板消息',
    `customer_tpl`    varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '访客模板消息',
    `onesubtpl_id`    VARCHAR(120)                                            NOT NULL DEFAULT '' COMMENT '订阅消息ID',
    `onesubtpl_title` VARCHAR(120)                                            NOT NULL DEFAULT '' COMMENT '订阅消息标题',
    `onesubtpl_desc`  VARCHAR(300)                                            NOT NULL DEFAULT '' COMMENT '订阅消息内容',
    `isscribe`        tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否开启引导关注1开启0关闭',
    `desc`            varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '公共号说明、备注',
    `addtime`         int(11) NOT NULL DEFAULT 0,
    `is_delete`       smallint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `business_id`(`business_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信公众号' ROW_FORMAT = Compact;


DROP TABLE IF EXISTS `wolive_attachment_data`;
CREATE TABLE `wolive_attachment_data`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件id',
    `service_id` int(10) unsigned NOT NULL DEFAULT '0',
    `admin_id`   int(10) unsigned NOT NULL DEFAULT '0',
    `filename`   varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
    `fileext`    varchar(20)  NOT NULL COMMENT '文件扩展名',
    `filesize`   int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
    `url`        varchar(600) NOT NULL DEFAULT '',
    `filemd5`    varchar(64)  NOT NULL DEFAULT '',
    `inputtime`  int(10) unsigned NOT NULL COMMENT '入库时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `inputtime` (`inputtime`) USING BTREE,
    KEY          `fileext` (`fileext`) USING BTREE,
    KEY          `uid` (`service_id`) USING BTREE
) DEFAULT CHARSET=utf8 COMMENT='附件归档表';

DROP TABLE IF EXISTS `wolive_weixin`;
CREATE TABLE `wolive_weixin`
(
    `wid`            int(11) NOT NULL AUTO_INCREMENT,
    `business_id`    int(11) NOT NULL COMMENT '商户ID',
    `service_id`     INT(11) NOT NULL COMMENT '客服ID',
    `app_id`         varchar(64)  NOT NULL DEFAULT '' COMMENT '公众号appid',
    `open_id`        varchar(255) NOT NULL COMMENT '微信用户id',
    `subscribe`      tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否关注微信0未关注1已关注',
    `subscribe_time` int(11) NOT NULL DEFAULT '0' COMMENT '关注时间',
    PRIMARY KEY (`wid`),
    KEY              `business_id` (`business_id`),
    KEY              `app_id` (`app_id`)
)DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `wolive_login_log`;
CREATE TABLE `wolive_login_log`
(
    `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid`        INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆者ID',
    `name`       VARCHAR(50) NOT NULL DEFAULT '' COMMENT '登陆者账号',
    `ip`         VARCHAR(15) NOT NULL DEFAULT '' COMMENT '登陆者IP',
    `area`       VARCHAR(60) NOT NULL DEFAULT '' COMMENT '地址',
    `login_side` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未知登陆方式1电脑登录2手机登录方式',
    `source`     TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1管理员2客服人员',
    `createtime` INT(10) NULL DEFAULT '0' COMMENT '登录时间',
    PRIMARY KEY (`id`),
    INDEX        `source` (`source`)
)COMMENT='登录日志表' COLLATE='utf8_general_ci';

CREATE TABLE `wolive_report`
(
    `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type`        TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '投诉类型',
    `content`     VARCHAR(1200) NOT NULL DEFAULT '' COMMENT '投诉内容',
    `contact`     VARCHAR(60)   NOT NULL DEFAULT '' COMMENT '联系方式',
    `ip`          VARCHAR(15)   NOT NULL DEFAULT '' COMMENT '投诉者IP',
    `business_id` INT(10) NOT NULL DEFAULT '0' COMMENT '商家ID',
    `createtime`  INT(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
    PRIMARY KEY (`id`)
)COMMENT='访客投诉表' COLLATE='utf8_general_ci';

SET
FOREIGN_KEY_CHECKS = 1;