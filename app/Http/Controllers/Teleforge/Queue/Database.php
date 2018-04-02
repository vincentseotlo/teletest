<?php
namespace Teleforge\Queue;
class Database{
        private $DB;
        function __construct($sess){
                $this->DB = sess;
        }
        public function put($key, $value){
                $this->DB->put($key, $value);
        }
        public function forget($key){
		$this->DB->forget($key);
	}
	public function getNode($id){
		
	}
	/**
	*  @param session 
	*/
	public function save($node){
		$this->DB->put("" . $node->id . ".id", $node->getId());
		$this->DB->put("" . $node->id . ".data", $node->getData());

		if($this->prev == null){
			$this->DB->put("" . $node->getId() . ".prev", null);
		}
		else{
			$this->DB->put("" . $node->getId() . ".prev", $this->prev->getId());
		}

		if($this->next == null){
			$this->DB->put("" . $node->getId() . ".next", null);
		}
		else{
			$this->DB->put("" . $node->getId() . ".next", $this->next->getId());
		}
	}
	/**
	*  @param session 
	* delete this, and update prev and next 
	*/
	public function delete($node){
		if($node->previous != null){
			$prev = $this->getNode($node->prev_id);
			$prev.setNext($node->next);
			$this->save($prev);
		}
		if($node->next != null){
			$next = $this->getNode($node->next_id);
			$next.setPrevious($node->previous);
			$this->save($next);
		}
		$DB->forget("". $node->id . ".id");
		$DB->forget("". $node->id . ".data");
		$DB->forget("". $node->id . ".next");
		$DB->forget("". $node->id . ".prev");
	}
}
