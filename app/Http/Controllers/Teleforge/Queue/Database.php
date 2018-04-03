<?php
namespace  App\Http\Controllers\Teleforge\Queue;
class Database{
        private $DB;
        function __construct($sess){
                $this->DB = $sess;
        }
        public function put($key, $value){
                $this->DB->put($key, $value);
        }
        public function forget($key){
		$this->DB->forget($key);
	}
	public function generateId(){
		$curr = $this->DB->get('serial');
		$this->put("serial", $curr += 1);
		return $curr;
	}
	public function getTail(){
		return $this->DB->get('tail');
	}
	public function getHead(){
		return $this->DB->get('head');
	}
	public function setHead($head){
		$this->DB->put('head', "". $head);
	}
	public function setTail($tail){
		$this->DB->put('tail', "". $tail);
	}
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
	*  @param session 
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
	*  @param session 
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
	public function close(){
		$this->DB->flush();
		return "reset done";
	}
	public function all(){
		return $this->DB->all();
	}
}
