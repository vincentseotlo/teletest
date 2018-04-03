<?php

namespace App\Http\Controllers\Teleforge;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Teleforge\Queue\Node;
use App\Http\Controllers\Teleforge\Queue\Database;
class TeleForge extends Controller {
	private $DB = null;
	function setup($req){
		$sess = $req->session();
		$this->DB = new Database($sess);
		$this->DB->init();
	}
	function append(Request $req){
		$this->setup($req);
		if($req->has('input')){
			$newid = 1;
			$tailid = $this->DB->getTail();// id
			if($tailid != 'None'){
				$tail = $this->DB->getNode($tailid);
				$newid = (int)$this->DB->generateId();
				$tail->setNext($newid); // update next
				$this->DB->save($tail); // save
			}
			$data = $req->input;
			$node = new Node($data);
			$node->setId("".$newid);
			$node->setNext('None');
			$node->setPrevious($tailid);
			$this->DB->save($node);
			// now point at the ends 
			$this->DB->setTail($newid);
			if($this->DB->getHead() == 'None'){
				$this->DB->setHead($newid);
			}
		}
	}
	function prepend(Request $req){
		$this->setup($req);
		if($req->has('input')){
			$newid = 1;
			$headid = $this->DB->getHead();// id
			if($headid != 'None'){
				$headid = (int) $headid;
				$head = $this->DB->getNode($headid);
				$newid = (int)$this->DB->generateId();
				$head->setPrevious($newid); // update next
				$this->DB->save($head); // save
			}
			$data = $req->input;
			$node = new Node($data);
			$node->setId($newid);
			$node->setPrevious('None');
			$node->setNext($headid);
			$this->DB->save($node);
			// now point at the ends 
			$this->DB->setHead($newid);
			if($this->DB->getTail() == 'None'){
				$this->DB->setTail($newid);
			}
		}
	}
	function eject(Request $req){
		$this->setup($req);
		$headid = $this->DB->getHead();// id
		if($headid == 'None')
			return;
		$head = $this->DB->getNode($headid);
		$nextid = $head->getNext();
		$this->DB->delete($head);
		if($nextid == 'None'){
			$this->DB->setTail('None');
		}
		$this->DB->setHead($nextid);
	}
	function pop(Request $req){
		$this->setup($req);
		$tailid = $this->DB->getTail();// id
		if($tailid == 'None')
			return;
		$tail = $this->DB->getNode($tailid);
		$previd = $tail->getPrevious();
		$this->DB->delete($tail);
		if($previd == 'None'){
			$this->DB->setHead('None');
		}
		$this->DB->setTail($previd);
	}
	function close(Request $req){
		$this->setup($req);
		$this->DB->close();
		return 'closed';
	}
	function show(Request $req){
		$this->setup($req);
		//return $this->DB->all();
		
		$res = [];
		$curr = $this->DB->getHead();
		while($curr != 'None'){
			$node = $this->DB->getNode($curr);
			array_push($res, $node->getData());
			$curr = $node->getNext();
		}
		$srt = $req->input;
		if($srt == 'asc')
			sort($res);
		if($srt == 'desc')
			rsort($res);
		return response()->json($res);
	}
}
