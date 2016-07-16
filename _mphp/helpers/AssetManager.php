<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 16/7/16
 * Time: 4:30 PM
 */
class AssetManager
{
	public $scssFiles;
	public $jsFiles;

	public $cssLink;
	public $jsLink;

	public function __construct()
	{

	}

	public function addSCSS($file)
	{
		$this->scssFiles[] = $file;
	}

	public function addJS($file)
	{
		$this->jsFiles[] = $file;
	}

	public function compile() {
		$this->compileCSS();
		$this->compileJS();
	}

	private function compileCSS() {
		$cssCompiler = new SASS_Compiler();
		$cssCompiler->compile();
	}

	private function compileJS() {
		$jsCompiler = new JS_Compiler();
		foreach($this->jsFiles as $file) {
			$jsCompiler->addFile($file);
		}
		$compiled = $jsCompiler->compile();
		file_put_contents(PUB_PATH.'/assets/js.min.js', $compiled);
	}

}