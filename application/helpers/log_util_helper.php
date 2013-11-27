<?php 

if (!function_exists('mylog')) {
    function mylog($prefix='err',$content) {
        $destPath = 'log/'; // 存放路径
        if(!is_writable($destPath)){
            if(!is_dir($destPath)){ // 存放路径不存在则创建
                @mkdir($destPath);
            }
            @chmod($destPath, 0755);
            if(!is_writable($destPath)){
                show_error('目标地址不可写导致无法保存');
            }
        }
        if(!$prefix){
          $prefix = 'err';
        }
        file_put_contents($destPath.$prefix.'_log.txt', date('Y-m-d H:i:s',time()).$content."\r\n", FILE_APPEND | LOCK_EX);
    }   
}

//科目 对应出 subject_type_id
    function trans_subject($data){
        $data = trim($data);
        switch($data){
          case '语文':return 1;
          case '数学':return 2;
          case '英语':return 3;
          case '物理':return 4;
          case '化学':return 5;
          case '生物':return 6;
          case '政治':return 7;
          case '历史':return 8;
          case '地理':return 9;
          case '科学':return 10;
          default: return 1;
        }
    }
    function trans_subject_to_text($data){
        $data = intval($data);
        switch($data){
          case 1:return '语文';
          case 2:return '数学';
          case 3:return '英语';
          case 4:return '物理';
          case 5:return '化学';
          case 6:return '生物';
          case 7:return '政治';
          case 8:return '历史';
          case 9:return '地理';
          case 10:return '科学';
          default: return '无';
        }
    }

    //转换
    function trans_grade_type($grade_type){
      $grade_type = trim($grade_type);
      switch($grade_type){
        case '小学':return 1;
        case '初中':return 2;
        case '高中':return 3;
        default: return 1;
      }
    }

    function trans_grade_type_to_text($grade_type){
      $grade_type = intval($grade_type);
      switch($grade_type){
        case 1:return '小学';
        case 2:return '初中';
        case 3:return '高中';
        default: return '未填';
      }
    }