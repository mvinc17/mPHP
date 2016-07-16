<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 6:50 PM
 */
class JS_Compiler extends Compiler
{
	public function __construct()
	{
		parent::__construct();
	}

	public function compile() {
		$js = "";

		foreach($this->files as $file) {
			$js .= PHP_EOL.file_get_contents($file);
		}

		return $this->compiler($js);
	}

	private function compiler($input)
	{
		if(trim($input) === "") return $input;

		return $input;
	}
}