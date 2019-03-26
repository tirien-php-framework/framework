<?php

abstract class Query_Builder 
{

	public $tablePrefix = "s";

	public $table = "";

	public function __construct()
	{
		if ($this->table == "") {
			return $this->table = strtolower(str_replace('Model_', '', get_class($this))) . $this->tablePrefix;
		}
	}

	public function get()
	{
		return DB::query( "SELECT * FROM $this->table WHERE status=1");	
	}

	public function getDesc()
	{
		return DB::query( "SELECT * FROM $this->table WHERE status=1 ORDER BY created_at DESC");	
	}

	public function getOrdered()
	{
		return DB::query( "SELECT * FROM $this->table WHERE status=1 ORDER BY order_number");	
	}
	
	public function all()
	{
		return DB::query( "SELECT * FROM $this->table");	
	}

	public function create(array $data)
	{
		return DB::insert($this->table, $data);
	}

	public function find($id)
	{
		return DB::query( "SELECT * FROM $this->table WHERE status=1 and id=?", $id, true);	
	}

	public function where($requirement, $where, $condition = '=')
	{
		return DB::query( "SELECT * FROM $this->table WHERE status=1 and ".$requirement." ".$condition ." ?", $where);	
	}

	public function update($id, array $data)
	{
        return DB::update($this->table, $data, "id=".$id );
	}

	public function delete($id)
	{
		return DB::delete($this->table, "id=".$id );
	}
	
	public function deleteAll()
	{
		return DB::delete($this->table);
	}
}
