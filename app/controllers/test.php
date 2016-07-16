<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 8:26 PM
 */
class test
{
	public function index() {
		view();
	}
	public function two($id = null) {

		echo "Hello, World 2!";
		echo "<br/>ID is ".$id;
	}

}