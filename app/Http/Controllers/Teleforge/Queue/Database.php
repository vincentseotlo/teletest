<?php
namespace  App\Http\Controllers\Teleforge\Queue;
class Database{
        private $DB;
        function __construct($sess){
                $this->DB = $sess;
        }
	/*
	* exposes the session put
	*/
        public function put($key, $value){
                $this->DB->put($key, $value);
        }
	/*
	* exposes the session forget
	*/
        public function forget($key){
		$this->DB->forget($key);
	}
	/*
	* each new node get a unique id/pointer
	*/
	public function generateId(){
		$curr = $this->DB->get('serial');
		$this->put("serial", $curr += 1);
		return $curr;
	}
	/*
	* the id of the tail node 
	*/
	public function getTail(){
		return $this->DB->get('tail');
	}
	/*
	* the id of the head node
	*/
	public function getHead(){
		return $this->DB->get('head');
	}
	/*
	* where to prepend
	*/
	public function setHead($head){
		$this->DB->put('head', "". $head);
	}
	/*
	* where to append next
	*/
	public function setTail($tail){
		$this->DB->put('tail', "". $tail);
	}
	/*
	* retrive the node, given id
	*/
	public function getNode($id){
		$data = $this->DB->get("node" . $id . ".data"); 
		$nextid = $this->DB->get("node" . $id . ".next"); 
		$previd = $this->DB->get("node" . $id . ".prev");
		$curr = new Node($data); 
		$curr->setId($id);
		$curr->setNext($nextid);
		$curr->setPrevious($previd);
		/*echo '<br>' . " ". $curr->getId();
		echo '<br>' . " ". $curr->getPrevious();
		echo '<br>' . " ". $curr->getNext();
		echo "<br>===============";*/
		return $curr;
	}
	/**
	*  @param Node. 
	*  save this node to session
	*/
	public function save($node){ // or simply store the whole as a json??
		$this->DB->put("node" . $node->getId() . ".id", $node->getId());
		$this->DB->put("node" . $node->getId() . ".data", $node->getData());
		/*echo '<br>' . " ". $node->getId();
		echo '<br>' . " ". $node->getPrevious();
		echo '<br>' . " ". $node->getNext();
		echo "<br>--------------";*/
		if($node->getPrevious() == 'None'){
			$this->DB->put("node" . $node->getId() . ".prev", 'None');
		}
		else{
			$this->DB->put("node" . $node->getId() . ".prev", $node->getPrevious());
		}

		if($node->getNext() == 'None'){
			$this->DB->put("node" . $node->getId() . ".next", 'None');
		}
		else{
			$this->DB->put("node" . $node->getId() . ".next", $node->getNext());
		}
	}
	/**
	*  @param Node 
	* delete this, and update prev and next 
	*/
	public function delete($node){
		if($node->getPrevious() != 'None'){
			$prev = $this->getNode($node->getPrevious());
			$prev->setNext($node->getNext());
			$this->save($prev);
		}
		if($node->getNext() != 'None'){
			$next = $this->getNode($node->getNext());
			$next->setPrevious($node->getPrevious());
			$this->save($next);
		}
		$this->DB->forget("node" . $node->getId() . ".id");
		$this->DB->forget("node" . $node->getId() . ".data");
		$this->DB->forget("node" . $node->getId() . ".next");
		$this->DB->forget("node" . $node->getId() . ".prev");
		$this->DB->forget("node" . $node->getId());
	}
	/*
	* init queue if it's not there
	*/
	public function init(){
		if(! $this->DB->exists('serial')){
			$this->put('serial', 1);
		}
		if(! $this->DB->exists('head')){
			$this->put('head', 'None');
		}
		if(! $this->DB->exists('tail')){
			$this->put('tail', 'None');
		}
	}
	/*
	* empty/reset queue
	*/
	public function close(){
		$this->DB->flush();
		//return "reset done";
	}
	public function all(){
		return $this->DB->all();
	}
}
