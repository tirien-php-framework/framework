<?php
	class Core
	{
		public $view;
		public $ajax = false;
		public $mobile = false;
		public $apple = false;
		public $iphone = false;
		public $ipad = false;
		public $disable_layout = false;
		public $disable_view = false;
		private $layout_name = '';
		
		public function __construct() {

			$this->view = new stdClass();
			
			$this->view->head = array(
				'title' => '',
				'description' => '',
				'og_title' => '',
				'og_description' => '',
				'og_image' => ''
				);

			if(
				!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
			) {
				$this->ajax = true;
			}

			if( strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') ) {
				$this->apple = true;
			}
			
			if( strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') ) {
				$this->iphone = true;
			}

			if( strstr($_SERVER['HTTP_USER_AGENT'],'iPad') ) {
				$this->ipad = true;
			}

			if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|iphone|itouch|ipod|symbian|htc_|htc-|palmos|opera mini|iemobile|windows ce|nokia|fennec|kindle|mot |mot-|samsung|sonyericsson|^sie-|nintendo|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]) ) {
				$this->mobile = true;
			}

		}

		public function init() {
		}

		public function setVars($rq_controller, $rq_action, $layout_name) {
			$this->controller_name = $rq_controller;
			$this->action_name = $rq_action;
			$this->layout_name = !empty( $this->layout_name ) ? $this->layout_name : $layout_name;
		}
		
		public function run() {
			if($this->ajax) $this->disableLayout();
			
			if($this->disable_layout){
				$this->viewContent();
			}
			else {
				include('layouts/'.$this->layout_name.'.php');
			}
		}

		public function viewContent() {
		
			if($this->disable_view){
				return false;
			}

			$this->view_path = 'views/' . ( empty($this->view_path) ? $this->controller_name.'/'.$this->action_name.'.php' : $this->view_path );
			
			include($this->view_path);
			return true;
		}

		public function setView($view_path){
			$this->view_path = $view_path;
		}
		
		public function disableView() {
			$this->disable_view = true;
		}

		public function setLayout($layout_name){
			$this->layout_name = $layout_name;
		}
		
		public function disableLayout() {
			$this->disable_layout = true;
		}

	}
?>