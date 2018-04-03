<?php
namespace App\Http\Controllers\Teleforge\Queue;
class Node{
	/**
	*  @var int 
	*/
	private $id = null;
	/**
	*  @var mixed
	*/
	private $data  = null;
	/**
	*  @var null | Node the next node
	*/
	private $next = null;
	/**
	* @var null | Node the previous node
	*/
	private $previous  =  null;
	/**
	*  Node constructor.
	*  @param  null$data
	*/
	public function __construct ($data){
		$this->data = $data;
	}
	/**
	* @return id 
	*/
	public function getId(){
		return $this->id;
	}
	/**
	* @return mixed
	*/
	public function getData(){
		return $this->data;
	}
	/**
	*  @return null | Node
	*/
	public function getNext(){
		return $this->next;
	}
	/**
	* @param int 
	*/
	public function setId($id){
		$this->id = $id;
	}
	/**
	* @param null | Node $next
	*/
	public function setNext($next){
		$this->next = $next;
	}
	/**
	* @return null | Node
	*/
	public function getPrevious(){
		return $this->previous;
	}
	/**
	*  @param null | Node $previous
	*/
	public function setPrevious($previous){
		$this->previous = $previous;
	}
}
