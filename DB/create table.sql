CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `verified` tinyint(1) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone_verified` tinyint(1) DEFAULT NULL,
  `phone_mask` varchar(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `user_type` tinyint(1) DEFAULT NULL,
  `register_subject` int(11) DEFAULT NULL,
  `register_time` datetime DEFAULT NULL,
  `register_ip` bigint(20) DEFAULT NULL,
  `register_origin` tinyint(1) DEFAULT NULL,
  `is_lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `email_phone` (`email`,`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=812800910 DEFAULT CHARSET=utf8;


 
CREATE TABLE `aq_teacher` (
  `id` int(11) NOT NULL,
  `aq_teacher` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否允许答疑，1：允许，0：不允许',
  `aq_online` tinyint(4) NOT NULL DEFAULT '0' COMMENT '老师是否在线，1：在线，2：不在线',
  `aq_account_balance` int(10) unsigned NOT NULL COMMENT '学点余额',
  `aq_fields` varchar(255) NOT NULL,
  `aq_credit_once` int(10) unsigned NOT NULL COMMENT '每次回答时的学点',
  `aq_avg_sec` int(10) unsigned NOT NULL,
  `aq_avg_evaluate` int(10) unsigned NOT NULL COMMENT '页面上显示的1  在这里是 100',
  `aq_answer_count` int(10) unsigned NOT NULL COMMENT '总的答题数',
  `aq_comment_count` int(10) NOT NULL,
  `aq_revoke_times` int(10) NOT NULL COMMENT '被撤销答题 机会 的次数',
  `avatar_url` varchar(200) NOT NULL COMMENT '头像地址',
  `school` varchar(200) NOT NULL COMMENT '答疑老师的学校和tizi不用打通',
  `subject` int(10) NOT NULL COMMENT '老师在答疑里的科目',
  `weight` int(10) NOT NULL COMMENT '排名权重',
  `grade_type` tinyint(4) NOT NULL COMMENT '== aq_grade.grade_type ; 1.小学 2.初中 3.高中',
  `intro` varchar(1000) NOT NULL COMMENT '简介',
  PRIMARY KEY (`id`),
  KEY `aq_online` (`aq_online`),
  KEY `grade_type` (`grade_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

