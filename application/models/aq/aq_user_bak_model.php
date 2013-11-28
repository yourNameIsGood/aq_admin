<?php
class Aq_User_Bak_Model extends CI_Model{
    private $_table ;
    function __construct(){
        parent::__construct();
        $this->_table = 'aq_user_bak';
    }


    function get_aq_teacher_list($is_one=false,$teacher_id=0,$page=1,$pagesize=20){
      $sql = "select user.name, a.* from aq_teacher a left join user on user.id = a.id where 1=1 ";
      if($is_one){
            if(!$teacher_id){
                return null;
            }
            $sql .= " and a.id=$teacher_id";
      }else{
          $start = ($page-1)*$pagesize;
          $sql .= " limit $start,$pagesize";
      }
      // echo $sql;die;
      $res = $this->db->query($sql)->result_array();
      if($res){
        return $res;
      }
      return null;
    }


    function count_table_aq_teacher(){
        $sql = "select count(1) as num from aq_teacher ";
        return $this->db->query($sql)->row(0)->num;
    }


    function add($param){
      $total = $available_num = $succ_num = $err_num = 0;
      $this->create_table();// 没有就创建表
        if(is_array($param)){
            $total = count($param);
            foreach($param as $k=>$v){// 好戏开始
                if(!$this->_check_available($v['email'])){ //检测email是否可用
                  //写文件
                  $available_num ++;
                  $result = $this->exist_user_into_aq_teacher($v['email'],$v['subject'],$v['grade_type'],$v['school'],$v['intro']);
                  if($result){
                    $content = $v['name'].'的email:'.$v['email'].'重复了, 写进aq_teacher成功';
                    $succ_num ++;
                  }else{
                    $content = $v['name'].'的email:'.$v['email'].'重复了, 写进aq_teacher失败';
                    $err_num ++;
                  }
                  mylog('err',$content); // 封装了写日志的方法，在log_util_helper中
                }else{
                    $insert_bak_sql = "insert into $this->_table (name,verified,email,email_verified,password,user_type,register_subject,register_time,register_origin) values(?,?,?,?,?,?,?,?,?)";
                    $tmp = array();
                    $tmp = array($v['name'],1,$v['email'],1,$v['password'],3,$v['subject'],date('Y-m-d H:i:s',time()),2);
                    $this->db->query($insert_bak_sql,$tmp);
                    $id = 0;
                    $id = $this->db->insert_id();
                    if($id){
                        $content = '插入bak成功,id='.$id.':'.$v['name'].','.$v['email'];
                        mylog('succ_bak',$content); //插入bak成功日志
                        $insert_user_sql = "insert into user (name,verified,email,email_verified,password,user_type,register_subject,register_time,register_origin) values(?,?,?,?,?,?,?,?,?)";
                        $this->db->query($insert_user_sql,$tmp);
                        $id = 0;
                        $id = $this->db->insert_id();
                        if($id){
                            $content = '插入user成功,id='.$id.':'.$v['name'].','.$v['email'];
                            mylog('succ_user',$content);//插入user成功日志
                            $insert_aq_teacher = "insert into aq_teacher (id,aq_teacher,school,subject,grade_type,intro) values(?,?,?,?,?,?) ";
                            $arr = array($id,1,$v['school'],$v['subject'],$v['grade_type'],$v['intro']);
                            if($this->db->query($insert_aq_teacher,$arr)){ 
                                $content = '插入aq_teacher成功,id='.$id;
                                mylog('succ_teacher',$content);// 插入老师成功日志
                                $succ_num ++;
                            }else{ 
                                $content = "插入aq_teacher失败，id为".$id;
                                mylog('err',$content);// 插入老师失败日志
                                $err_num ++;
                            }
                        }else{
                            $content = '插入user失败:'.$v['name'].','.$v['email'];
                            mylog('err',$content);//插入user失败日志
                        }
                    }else{
                          $content = '插入bak失败:'.$v['name'].','.$v['email'];
                          mylog('err',$content);//插入bak失败日志
                    }
                    
                }
            }
            return array('succ'=>$succ_num,'err'=>$err_num,'ava'=>$available_num,'total'=>$total);
        }else{
            return false;
        }
    }

    function edit_one($param){
        $sql="update aq_teacher set aq_teacher={$param['aq_teacher']},aq_fields='{$param['aq_fields']}',subject={$param['subject']},school='{$param['school']}',grade_type={$param['grade_type']},intro='{$param['intro']}' where id={$param['id']}";
        $res = $this->db->query($sql);
        if($res && isset($param['name']) && $param['name']){
            $sql = "update user set name='{$param['name']}' where id={$param['id']}";
            $res = $this->db->query($sql);
        }
        return $res;
    }

    // return true: 可用   ;  false 已经被使用，so 不能用
     function _check_available($e){
        $sql = "select id from user where email='$e' ";
        $res = $this->db->query($sql)->row(0);
        // var_dump($res);die;
        if($res){
          return false;
        }
        return true;
    }

    //将已经存在user表中的用户，在aq_teacher中添加相应的记录
    function exist_user_into_aq_teacher($email,$subject,$grade_type,$school,$intro){
        $sql = "select id,register_subject,user_type from user where email='$email'";
        $res = $this->db->query($sql)->row(0);
        if($res->user_type==3){
            $count_sql = "select count(id) as cc from aq_teacher where id = ".$res->id;
            $num = $this->db->query($count_sql)->row(0)->cc;
            if($num){
                return false;
            }
            // $res->register_subject=1;
            $insert_sql = "insert into aq_teacher (id,aq_teacher,subject,grade_type,school,intro) values({$res->id},1,$subject,$grade_type,'$school','$intro') ";
            return $this->db->query($insert_sql);
        }
        return false;
        // var_dump($res);die;
    }

    //从subject_type表中获取所有的科目 及其 id
    function get_all_subject_type(){
        $sql= "select * from subject_type ";
        $res = $this->db->query($sql)->result_array();
        return $res;
    }

    function get_aq_field($subject,$grade_type){
        $sql = "select * from aq_point where aq_subject_id=$subject and grade_type=$grade_type ";
        $res = $this->db->query($sql)->result_array();
        return $res;   
    }
    function get_aq_field_by_ids($point_ids){
        $sql = "select * from aq_point where id in ($point_ids)";
        $res = $this->db->query($sql)->result_array();
        return $res;   
    }

    //获取user表中的某个字段的值
    function get_user_info($uid){
      $sql = "select * from user where id = $uid";
      $res = $this->db->query($sql)->row(0);
      return $res;
    }

    function create_table(){
        $sql = "CREATE TABLE IF NOT EXISTS `aq_user_bak` (
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
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `email` (`email`),
                  UNIQUE KEY `phone` (`phone`),
                  UNIQUE KEY `student_id` (`student_id`),
                  KEY `email_phone` (`email`,`phone`)
                ) ENGINE=InnoDB AUTO_INCREMENT=812800458 DEFAULT CHARSET=utf8";
        $this->db->query($sql);
    }
}