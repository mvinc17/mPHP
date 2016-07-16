<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 6:50 PM
 */
class CSS_Compiler extends Compiler
{
	private $scssCompiler;

	public function __construct()
	{
		$this->scssCompiler = new scssc();
	}
	
	public function compile() {
		
	}
}