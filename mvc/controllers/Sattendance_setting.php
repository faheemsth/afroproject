<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sattendance_Setting extends Admin_Controller
{
	/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("teacher_m");
		$this->load->model("classes_m");
		$this->load->model("user_m");
		$this->load->model("usertype_m");
		$this->load->model("section_m");
		$this->load->model("setting_m");
		$this->load->model('studentgroup_m');
		$this->load->model('subject_m');
		$this->load->model('schoolyear_m');
		$this->load->model('mailandsmstemplate_m');
		$this->load->model('mailandsmstemplatetag_m');
		$this->load->model('markpercentage_m');
		$this->load->model('mark_m');
		$this->load->model('grade_m');
		$this->load->model('exam_m');
		$this->load->model('studentrelation_m');
		$this->load->model('leaveapplication_m');
		$this->load->model('Setting_m');

		$this->load->library("email");
		$this->load->library('clickatell');
		$this->load->library('twilio');
		$this->load->library('bulk');
		$this->load->library('msg91');

		$this->data['setting'] = $this->setting_m->get_setting();

		if ($this->data['setting']->attendance == "subject") {
			$this->load->model("subjectattendance_m");
		}
		$language = $this->session->userdata('lang');
		$this->lang->load('sattendance', $language);
	}


	public function index()
	{
		if (permissionChecker('sattendance_setting')) {
			if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {

				$this->schoolyear_m->get_obj_schoolyear();
				$this->data['headerassets'] = array(
					'css' => array(
						'assets/select2/css/select2.css',
						'assets/select2/css/select2-bootstrap.css',
						'assets/datepicker/datepicker.css'
					),
					'js' => array(
						'assets/select2/select2.js',
						'assets/datepicker/datepicker.js'
					)
				);

				if (!empty($_POST)) {
					foreach ($_POST as $key => $value) {
						$field_settings = $this->setting_m->get_setting_where($key);
						if (isset($field_settings->fieldoption)) {
							$this->setting_m->update_setting($key, $value);
						} else {
							$attendance_setting = [
								'fieldoption' => $key,
								'value' => $value
							];
							$this->setting_m->insert_setting($attendance_setting);
						}
					}
				}

				$this->data["subview"] = "sattendance/settings";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
}
