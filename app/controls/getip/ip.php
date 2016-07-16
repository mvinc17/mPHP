<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 16/7/16
 * Time: 4:12 PM
 */

class GetIP {
	public function __construct()
	{

	}

	public function view() {
		return $_SERVER['REMOTE_ADDR'];
	}
}