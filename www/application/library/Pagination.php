<?php 

Class Pagination{

	private $key;
	private $data;
	private $current_page_data;
	private $current_page = 1;
	private $page_count;
	private $items_per_page = 10;
	private $offset;

	function Pagination( $data, $key = null, $items_per_page = null ){

		$this->key = $key;
		$this->data = $data;
		$this->items_per_page = empty($items_per_page) ? $this->items_per_page : $items_per_page;
		$this->page_count = ceil( count($this->data) / $this->items_per_page );

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

		return count($this->current_page_data);
	
	}

	public function links( $base = '', $get = '' ){

		$links = '';

		if( $this->page_count < 2 ) return $links;

		for ($i=1; $i <= $this->page_count; $i++) { 
			$current = $i==$this->current_page ? ' current' : '';
			$key = empty($this->key) ? '' : '['.$this->key.']';
			$links .= '<li><a class="pagination-page-link'.$current.'" href="'.$base.'?page'.$key.'='.$i.'&'.$get.'">'.$i.'</a></li>';
		}

		$links = '<ul class="pagination">'.$links.'</ul>';

		return $links;

	}

}

?>