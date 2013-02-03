<?php
	class Core
	{
		public $ajax = false;
		public $disable_layout = false;
		public $disable_view = false;
		public function __construct() {
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
			$this->layout_name = $layout_name;
		}
		
		public function run() {
			if($this->ajax) $this->disableLayout();
			
			if($this->disable_layout){
				$this->viewContent();
			}
			else {
				include('./application/layouts/'.$this->layout_name.'.php');
			}
		}

		public function viewContent() {
		
			if($this->disable_view){
				return false;
			}

			$this->view_path = empty($this->view_path) ? './application/views/'.$this->controller_name.'/'.$this->action_name.'.php' : $this->view_path;
			
			include($this->view_path);
			return true;
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