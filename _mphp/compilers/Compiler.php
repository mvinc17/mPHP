<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 6:50 PM
 */
class Compiler
{
	protected $files = array();

	public function __construct()
	{
	}

	public function addFile($filePath) {
		if(!in_array($filePath, $this->files)) {
			$this->files[] = $filePath;
		}
	}


}