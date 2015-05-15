<?php
	class Core
	{
		public $view;
		public $ajax = false;
		public $disable_layout = false;
		public $disable_view = false;
		private $layout_name = '';
		private $detect;
		
		public function __construct() {

			global $_config;
			$this->config = $_config;
			
			$this->view = new stdClass();
			$this->detect = new Mobile_Detect();
			
			// PAGE META
			if ( !empty($_config['system']['page_meta_table']) ) {
	
				$page_meta = DB::query("SELECT * FROM page_meta WHERE IFNULL(uri,'') = ?", Path::urlUri(true), true);	
				if (!empty($page_meta)) {
					$this->view->head['title'] = $page_meta['title'];
					$this->view->head['description'] = $page_meta['description'];
					$this->view->head['keywords'] = $page_meta['keywords'];
				}
	
			}
			// END PAGE META

			if(
				!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
			) {
				$this->ajax = true;
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
			
			if (file_exists('application/'.$this->view_path)) {
				include($this->view_path);
				return true;
			}
			else{
				pageNotFound();
			}

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