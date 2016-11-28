/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : project

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-10-08 11:17:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL COMMENT '日期',
  `js` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '江苏',
  `bj` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '北京',
  `gd` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '广东',
  `ah` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '安徽',
  `sc` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '四川',
  `zj` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '浙江',
  `hb1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '湖北',
  `jl` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '吉林',
  `hn` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '河南',
  `sd` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '山东',
  `hb2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '河北',
  `sh` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '上海',
  `jx` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '江西',
  `ln` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '辽宁',
  `sx` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '陕西',
  `fj` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '福建',
  `hlj` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT ''COMMENT '黑龙江',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='地域排名';

-- ----------------------------
-- Records of area
-- ----------------------------

-- ----------------------------
-- Table structure for baidu_visit
-- ----------------------------
DROP TABLE IF EXISTS `baidu_visit`;
CREATE TABLE `baidu_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '端类型',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '吧名',
  `uv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '独立访客数',
  `pv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '页面浏览量',
  `login` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '登录用户数',
  `tb_uv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧独立访问量',
  `tb_pv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧网页浏览量',
  `home_uv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧首页独立访问量',
  `home_pv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧首页网页访问量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='百度访问端';

-- ----------------------------
-- Records of baidu_visit
-- ----------------------------

-- ----------------------------
-- Table structure for behaviour
-- ----------------------------
DROP TABLE IF EXISTS `behaviour`;
CREATE TABLE `behaviour` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id主键',
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `register` smallint(8) NOT NULL DEFAULT '0' COMMENT '注册会员数',
  `active` smallint(8) NOT NULL DEFAULT '0' COMMENT '活跃会员数',
  `uv` smallint(8) NOT NULL DEFAULT '0' COMMENT '独立会员数',
  `home_in` smallint(8) NOT NULL DEFAULT '0' COMMENT '首页浏览量',
  `home_out` char(5) COLLATE utf8_bin NOT NULL COMMENT '首页跳出率',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='行为和流量';

-- ----------------------------
-- Records of behaviour
-- ----------------------------

-- ----------------------------
-- Table structure for behaviour_info
-- ----------------------------
DROP TABLE IF EXISTS `behaviour_info`;
CREATE TABLE `behaviour_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `send_active` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发主题者活跃',
  `reply_active` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '仅回复者活跃',
  `see_active` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '围观者活跃',
  `visit` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '访问人数',
  `active_degree` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '活跃度',
  `send` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发主题者人均页面浏览量',
  `reply` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '仅回复者人均页面浏览量',
  `see` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '围观者人均页面浏览量',
  `send_one` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '人均发主题者',
  `reply_one` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '人均仅回复者',
  `see_one` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '人均围观者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户行为分析';

-- ----------------------------
-- Records of behaviour_info
-- ----------------------------

-- ----------------------------
-- Table structure for click
-- ----------------------------
DROP TABLE IF EXISTS `click`;
CREATE TABLE `click` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id主键',
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `url` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'url',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_url` (`date`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='行为和流量';

-- ----------------------------
-- Records of click
-- ----------------------------

-- ----------------------------
-- Table structure for collect
-- ----------------------------
DROP TABLE IF EXISTS `collect`;
CREATE TABLE `collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '时间',
  `active` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '活跃用户',
  `visit` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '客户端访问数',
  `theme` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '主题数',
  `pc_theme` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '客户端主题数',
  `reply` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '回复数',
  `pc_reply` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '客户端回复数',
  `signin` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '签到数',
  `pc_signin` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '客户端签到数',
  `signin_ratio` varchar(11) COLLATE utf8_bin NOT NULL COMMENT '签到数',
  `new_member` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '新增会员数',
  `total_member` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '总会员数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='吧内数据总汇';

-- ----------------------------
-- Records of collect
-- ----------------------------

-- ----------------------------
-- Table structure for data
-- ----------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL,
  `uv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `login_num` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '登录用户数',
  `num` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发帖数',
  `pv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of data
-- ----------------------------

-- ----------------------------
-- Table structure for excel_import_time
-- ----------------------------
DROP TABLE IF EXISTS `excel_import_time`;
CREATE TABLE `excel_import_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baidu` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `collect` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `conventional` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tieba` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pool` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of excel_import_time
-- ----------------------------

-- ----------------------------
-- Table structure for feature
-- ----------------------------
DROP TABLE IF EXISTS `feature`;
CREATE TABLE `feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `login` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '登录用户比例',
  `nologin` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '未登录用户比例',
  `man` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '男性比例',
  `woman` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '女性比例',
  `age18` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '18以下比例',
  `age24` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '18-24比例',
  `age34` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '25-34比例',
  `age44` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '35-44比例',
  `age` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '45-比例',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户特征';

-- ----------------------------
-- Records of feature
-- ----------------------------

-- ----------------------------
-- Table structure for ffrom
-- ----------------------------
DROP TABLE IF EXISTS `ffrom`;
CREATE TABLE `ffrom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `tb_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧独立访客',
  `tb_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧人均页面浏览量',
  `tb_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '贴吧占比',
  `az_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '安卓独立访客',
  `az_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '安卓人均页面浏览量',
  `az_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '安卓占比',
  `ios_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ios独立访客',
  `ios_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ios人均页面浏览量',
  `ios_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ios占比',
  `zd_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `zd_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `zd_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qt_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '其他',
  `qt_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qt_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ps_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ps_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ps_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ald_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ald_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ald_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `wbps_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `wbps_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `wbps_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab1_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab1_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab1_3` varchar(11) COLLATE utf8_bin NOT NULL,
  `tab2_1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab2_2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tab2_3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='访问来源';

-- ----------------------------
-- Records of ffrom
-- ----------------------------

-- ----------------------------
-- Table structure for iin
-- ----------------------------
DROP TABLE IF EXISTS `iin`;
CREATE TABLE `iin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `num` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name1` varchar(50) COLLATE utf8_bin NOT NULL,
  `num1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name2` varchar(50) COLLATE utf8_bin NOT NULL,
  `num2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name3` varchar(50) COLLATE utf8_bin NOT NULL,
  `num3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name4` varchar(50) COLLATE utf8_bin NOT NULL,
  `num4` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户流入';

-- ----------------------------
-- Records of iin
-- ----------------------------

-- ----------------------------
-- Table structure for interest
-- ----------------------------
DROP TABLE IF EXISTS `interest`;
CREATE TABLE `interest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL COMMENT '日期',
  `music` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '影视音乐',
  `book` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '书籍阅读',
  `game` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '游戏',
  `beauty` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '个护美容',
  `sports` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '体育健身',
  `education` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '教育培训',
  `numeral` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '家电数码',
  `news` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '资讯',
  `health` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '医疗健康',
  `software` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '软件应用',
  `internet` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网络购物',
  `pet` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '花鸟萌宠',
  `eat` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '餐饮美食',
  `play` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '休闲爱好',
  `play1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '休闲娱乐',
  `car` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '汽车',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='兴趣排名';

-- ----------------------------
-- Records of interest
-- ----------------------------

-- ----------------------------
-- Table structure for oout
-- ----------------------------
DROP TABLE IF EXISTS `oout`;
CREATE TABLE `oout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `num` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name1` varchar(50) COLLATE utf8_bin NOT NULL,
  `num1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name2` varchar(50) COLLATE utf8_bin NOT NULL,
  `num2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name3` varchar(50) COLLATE utf8_bin NOT NULL,
  `num3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name4` varchar(50) COLLATE utf8_bin NOT NULL,
  `num4` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户流入';

-- ----------------------------
-- Records of oout
-- ----------------------------

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL COMMENT '日期',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `pname` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '商品名称',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `unit` int(11) NOT NULL DEFAULT '0' COMMENT '商品单价',
  `cost` int(11) NOT NULL DEFAULT '0' COMMENT '成本价',
  `buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '订单总数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单详情';

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for step
-- ----------------------------
DROP TABLE IF EXISTS `step`;
CREATE TABLE `step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `n1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '1',
  `n2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '2-5',
  `n6` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '6-20',
  `n21` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '20-50',
  `n50` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '50+',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户步长';

-- ----------------------------
-- Records of step
-- ----------------------------

-- ----------------------------
-- Table structure for tb_activity
-- ----------------------------
DROP TABLE IF EXISTS `tb_activity`;
CREATE TABLE `tb_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '活动名称',
  `zt_id` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '主题id',
  `type` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '端类型',
  `pv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `uv` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `login` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '登录用户数',
  `reply_n` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发回复贴数',
  `reply_n2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发回复贴人数',
  `send_n` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发楼中楼数',
  `send_n2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '发楼中楼人数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='贴吧活动';

-- ----------------------------
-- Records of tb_activity
-- ----------------------------

-- ----------------------------
-- Table structure for ttime
-- ----------------------------
DROP TABLE IF EXISTS `ttime`;
CREATE TABLE `ttime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '日期',
  `t0` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t1` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t2` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t3` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t4` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t5` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t6` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t7` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t8` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t9` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t10` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t11` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t12` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t13` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t14` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t15` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t16` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t17` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t18` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t19` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t20` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t21` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t22` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  `t23` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='进吧时段';

-- ----------------------------
-- Records of ttime
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `flag` tinyint(3) unsigned NOT NULL COMMENT '0未改 1已改密码',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `dept` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '所属部门',
  `rules` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '权限控制',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'root', 'db18caecff41bab2cae53baa7f2fc115', '1', '1473652006', '1473662836', '超级管理员', '666');

-- ----------------------------
-- Table structure for visit
-- ----------------------------
DROP TABLE IF EXISTS `visit`;
CREATE TABLE `visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` char(10) COLLATE utf8_bin NOT NULL COMMENT '日期',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '平均会话时长',
  `url` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '页面链接',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_url` (`date`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='页面访问时长';

-- ----------------------------
-- Records of visit
-- ----------------------------
DROP TABLE IF EXISTS `synthesize`;
DROP TABLE IF EXISTS `testScene`;
DROP TABLE IF EXISTS `cost`;
DROP TABLE IF EXISTS `trading`;
DROP TABLE IF EXISTS `ALA`;
DROP TABLE IF EXISTS `SGCDistribution`;
DROP TABLE IF EXISTS `ASGGame`;
DROP TABLE IF EXISTS `expired`;
DROP TABLE IF EXISTS `operation`;
CREATE TABLE `synthesize` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `testScene` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `cost` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `trading` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `ALA` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `SGCDistribution` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `ASGGame` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `expired` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
CREATE TABLE `operation` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;



ALTER TABLE `interest`
ADD COLUMN `qzcy`  varchar(11) NOT NULL DEFAULT '' COMMENT '求职创业' AFTER `car`;


