<?php

namespace App\Http\Controllers\Teleforge;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Teleforge\Queue\Node;
use App\Http\Controllers\Teleforge\Queue\Database;
class TeleForge extends Controller {
	private $DB = null;
	/*
	* basic setup. get session and init queue 
	*/
	function setup($req){ 
		$sess = $req->session();
		$this->DB = new Database($sess);
		$this->DB->init();
	}
	/*
	* append:- simply find the tail and update it's next with the id of the new node.
	* add new node. and update tail (and head) 
	*/
	function append(Request $req){
		$this->setup($req);
		if($req->has('input')){
			$newid = 1;
			$tailid = $this->DB->getTail();// id
			if($tailid != 'None'){ // we update the next id as newid
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
	/*
	* prepend:- simply find the tail and update it's next with the id of the new node.
	* add new node. and update tail (and head) 
	*/
	function prepend(Request $req){
		$this->setup($req);
		if($req->has('input')){
			$newid = 1;
			$headid = $this->DB->getHead();// id
			if($headid != 'None'){ // update head's previous as this newid
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
	/*
	* get the tail node and issue delete.
	* update tail(and head) accordingly
	*/
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
	/*
	* get the head node and issue delete.
	* update tail(and head) accordingly
	*/
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
	/*
	* reset/empty session queue
	*/
	function close(Request $req){
		$this->setup($req);
		$this->DB->close();
	}
	/*
	* show: return the queue
		1. as is
		2. sorted asc(ending)
		3. sorted desc(ending)
	*/
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
		$srt = $req->sort;
		if($srt == 'asc')
			sort($res);
		if($srt == 'desc')
			rsort($res);
		return response()->json($res);// no view() necessary 
	}
}
