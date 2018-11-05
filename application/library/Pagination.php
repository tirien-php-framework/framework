<?php 

Class Pagination{

	private $key;
	private $data;
	private $current_page_data;
	private $current_page = 1;
	private $page_count;
	private $items_per_page = 20;
	private $offset;
	private $count;

	function __construct( $data, $key = null, $items_per_page = null ){

		$this->key = $key;
		$this->data = $data;
		$this->items_per_page = empty($items_per_page) ? $this->items_per_page : $items_per_page;
		$this->count = count($this->data);
		$this->page_count = ceil( $this->count / $this->items_per_page );

		if( 
			empty($this->key) 
			&& !empty($_GET['page']) 
			&& (int)$_GET['page'] <= $this->page_count 
		){
			$this->current_page = (int)$_GET['page'];
		}
		else if( 
			!empty($this->key) 
			&& !empty($_GET['page'][$this->key]) 
			&& (int)$_GET['page'][$this->key] <= $this->page_count 
		){
			$this->current_page = (int)$_GET['page'][$this->key];
		}
		
		$this->offset = ( $this->current_page - 1 ) * $this->items_per_page;
		$this->current_page_data = array_slice( $this->data, $this->offset, $this->items_per_page );

	}

	public function data(){

		return $this->current_page_data;
	
	}

	public function count(){

		return $this->count;
	
	}

	public function links( $base = '', $get = '' ){

		$links = '';

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

		$default_base = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if (strpos($default_base, '?')!==false) {
			$default_base = strstr($default_base, '?', true);
		}

		$base = $base=='' ? $default_base : $base;

		if( $this->page_count < 2 ) return $links;

		for ($i=1; $i <= $this->page_count; $i++) { 
			$current = $i==$this->current_page ? ' class="current"' : '';
			$key = empty($this->key) ? '' : '['.$this->key.']';
			$href = $i==1 ? $base . '?' . $get : $base . '?page'.$key.'='.$i . '&' . $get;
			$links .= '<li'.$current.'><a class="pagination-page-link" href="'.trim($href,'&?').'">'.$i.'</a></li>';
		}

		$links = '<ul class="pagination">'.$links.'</ul>';

		return $links;

	}

}

?>
