<?php
	class Core
	{
		public $ajax = false;
		public $disable_layout = false;
		public $disable_content = false;
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
			$this->content_name = $rq_action;
			$this->layout_name = $layout_name;
		}
		
		public function setLayout($layout_name){
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
		
			if($this->disable_content){
				return false;
			}

			$this->content_path = empty($this->content_path) ? './application/contents/'.$this->controller_name.'/'.$this->content_name.'.php' : $this->content_path;
			
			include($this->content_path);
			return true;
		}

		public function disableLayout() {
			$this->disable_layout = true;
		}

		public function disableContent() {
			$this->disable_content = true;
		}

	}
?>