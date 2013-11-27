<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

		public function __construct(){
			parent::__construct();
			$this->load->model('aq/aq_user_bak_model','aubm');
		}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$page = $this->input->get('page');
		if(!$page){
			$page = 1;
		}
		$pagesize = $this->input->get('pagesize');
		if(!$pagesize){
			$pagesize = 20;
		}
		$res = $this->aubm->get_aq_teacher_list(0,0,$page,$pagesize);
		if($res){
				foreach ($res as $k=>$v) {				
						if(isset($v['aq_fields']) && $v['aq_fields']){
								$aq_fields = $this->aubm->get_aq_field_by_ids($v['aq_fields']);
								$tmp = '';
								if(is_array($aq_fields)){
										foreach($aq_fields as $key=>$val){
											$tmp.= $val['name'].',';
										}
										$v['aq_fields'] = rtrim($tmp,',');
								}
						}

						$v['subject'] = trans_subject_to_text($v['subject']);
						$v['grade_type'] = trans_grade_type_to_text($v['grade_type']);
						
						$res[$k] = $v;
				}
		}

		//分页
		$this->load->library('pagination');
		$config['base_url'] = Constant::ADMIN_URL."?"; //别忘记问号
		$config['total_rows'] = $this->aubm->count_table_aq_teacher();
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = "page";
		$config['per_page'] =$pagesize; 
		$config['cur_tag_open'] = "<a style='text-decoration:none'><font color='FF00CC'>";  
		$config['cur_tag_close'] = '</font></a>';  
		$config['first_link'] = '首页';  
		$config['last_link'] = '尾页'; 
		$config['num_links'] = 6;

		$this->pagination->initialize($config); 
		$pageinfo = ($this->pagination->create_links());


		$this->load->view('teacher_list.php',array('res'=>$res,'pageinfo'=>$pageinfo));
	}

	//导入excel的view页面
	function import_page(){
		$this->load->view('import_page.php');
	}

	//import excel into aq_user_bak
	function import_excel(){
			if ($_FILES["up"]["error"] > 0)
		  {
		  		echo "Error: " . $_FILES["up"]["error"] . "<br>";die;
		  }
			$filePath = $_FILES['up']['tmp_name'];

			require_once ('excel_ext/PHPExcel.php'); // PHPExcel.php && PHPExcel文件夹都与该文件放一起
			/**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
			$PHPReader = new PHPExcel_Reader_Excel2007 ();
			if (! $PHPReader->canRead ( $filePath )) {
					$PHPReader = new PHPExcel_Reader_Excel5 ();
					if (! $PHPReader->canRead ( $filePath )) {
						echo 'no Excel';
						return;
					}
			}
			$PHPExcel = $PHPReader->load ( $filePath );
			/**读取excel文件中的第一个工作表*/
			$currentSheet = $PHPExcel->getSheet ( 0 );
			/**取得最大的列号*/
			$allColumn = $currentSheet->getHighestColumn ();
			/**取得一共有多少行*/
			$allRow = $currentSheet->getHighestRow ();
			$standardSecond = 73800;// (20*60+30)min * 60s
			$param = array();
			/**从第二行开始输出，因为excel表中第一行为列名*/
			for($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
					/**从第A列开始输出*/
					for($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
						$nowCol = ord($currentColumn) - 65;
						$param[$currentRow][$nowCol]=$currentSheet->getCellByColumnAndRow($nowCol,$currentRow)->getValue();// 即为内容
					}
			}
			//转换到 $data
			$data = array();
			$i = 0;
			if(is_array($param)){
					foreach($param as $k=>$v){
							if(is_array($v) && isset($v[1]) && $v[1]){ //v[1] is email in excel file
									$data[$i]['name'] = $v[0];
									$data[$i]['email'] = $v[1];
									$data[$i]['subject'] = trans_subject($v[2]);
									$data[$i]['grade_type'] = trans_grade_type($v[3]);
									$data[$i]['school'] = $v[4];
									$data[$i]['intro'] = $v[5];
									$data[$i]['password']='idf3EU$cd80412fd5d034ea3d6f24b296671d4fce847991'; // 默认密码为123123
							}
							$i++;
					}
			}
				// print_r ( $data);
			if($data){
					$res = $this->aubm->add($data);
					echo '导入总数:'.$res['total'].'<br>';
					echo '存在user表中的帐号数:'.$res['ava'].'<br>';
					echo '插入aq_teacher成功:'.$res['succ'].'<br>';
					echo '插入aq_teacher失败:'.$res['err'].'<br>'.'<br>'.'<br>';
					echo '具体可参考/log下的文件';
			}
	}

	//
	function edit_page(){
		$teacher_id = $this->input->get('teacher_id');
		$res = $this->aubm->get_aq_teacher_list(true,$teacher_id);
		$subject_types = $this->aubm->get_all_subject_type();
		$points = $this->aubm->get_aq_field($res[0]['subject'],$res[0]['grade_type']);
		$my_points = explode(',',$res[0]['aq_fields']);
		// var_dump($points,$my_points);
		$this->load->view('edit_teacher2.html',array('res'=>$res[0],'subject_types'=>$subject_types,'points'=>$points,'my_points'=>$my_points));
	}

	//edit single account in aq_teacher
	function edit(){
			$name = $_REQUEST['name'];
			$param['name'] = addslashes(urldecode($name));
			$param['id'] = $this->input->post('id');
			$param['aq_teacher'] = $this->input->post('aq_teacher');

			$param['school'] = addslashes($this->input->post('school'));
			$param['subject'] = $this->input->post('subject');
			$param['grade_type'] = $this->input->post('grade_type');
			$param['intro'] = addslashes($this->input->post('intro'));
			$param['aq_fields'] = trim($this->input->post('aq_fields'),',');
			foreach ($param as $k=>$v) {
					$v = trim($v);
					$param[$k] = trim($v);
			}

			$res = $this->aubm->edit_one($param);
			if($res){
					echo 1;die;// succ
			}
			echo 0;die; // failed
	}

	function ttt(){
		var_dump($this->aubm->get_aq_field_by_ids('2,21,34'));die;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */