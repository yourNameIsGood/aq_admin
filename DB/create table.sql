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
  `aq_teacher` tinyint(4) NOT NULL DEFAULT '0' COMMENT '�Ƿ�������ɣ�1������0��������',
  `aq_online` tinyint(4) NOT NULL DEFAULT '0' COMMENT '��ʦ�Ƿ����ߣ�1�����ߣ�2��������',
  `aq_account_balance` int(10) unsigned NOT NULL COMMENT 'ѧ�����',
  `aq_fields` varchar(255) NOT NULL,
  `aq_credit_once` int(10) unsigned NOT NULL COMMENT 'ÿ�λش�ʱ��ѧ��',
  `aq_avg_sec` int(10) unsigned NOT NULL,
  `aq_avg_evaluate` int(10) unsigned NOT NULL COMMENT 'ҳ������ʾ��1  �������� 100',
  `aq_answer_count` int(10) unsigned NOT NULL COMMENT '�ܵĴ�����',
  `aq_comment_count` int(10) NOT NULL,
  `aq_revoke_times` int(10) NOT NULL COMMENT '���������� ���� �Ĵ���',
  `avatar_url` varchar(200) NOT NULL COMMENT 'ͷ���ַ',
  `school` varchar(200) NOT NULL COMMENT '������ʦ��ѧУ��tizi���ô�ͨ',
  `subject` int(10) NOT NULL COMMENT '��ʦ�ڴ�����Ŀ�Ŀ',
  `weight` int(10) NOT NULL COMMENT '����Ȩ��',
  `grade_type` tinyint(4) NOT NULL COMMENT '== aq_grade.grade_type ; 1.Сѧ 2.���� 3.����',
  `intro` varchar(1000) NOT NULL COMMENT '���',
  PRIMARY KEY (`id`),
  KEY `aq_online` (`aq_online`),
  KEY `grade_type` (`grade_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

