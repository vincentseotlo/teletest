<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeleForge extends Controller {

	function append(Request $req){
		return "input " . $req->input;
	}
	function eject(Request $req){
		return "eject";
	}
	function pop(Request $req){
		return "pops";
	}
	function prepend(Request $req){
		return "prepend " . $req->input;
	}
}
