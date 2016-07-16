<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 16/7/16
 * Time: 8:33 AM
 */
class View_Compiler extends Compiler
{
	private $partialView;

	public function __construct($partialView = VIEW_TEMPLATE)
	{
		$this->partialView = $partialView;
		parent::__construct();
	}

	public function compile() {
		$template = $this->compileTemplate(file_get_contents(APP_PATH.'/views/'.$this->partialView.'.view'), file_get_contents(APP_PATH.'/views/'.ROUTE_CONTROLLER.'/'.ROUTE_VIEW.'.view'));
		echo $template->saveHTML();
	}

	private function compileTemplate($container, $view) {
		$dom = new domDocument();
		$dom->loadHTML($container);

		$dom->preserveWhiteSpace = false;

		$content = $dom->getElementById(VIEW_CONTAINER_ID);

		//<php> tags in the template
		$evalTags = $dom->getElementsByTagName("php");
		foreach($evalTags as $tag) {
			//var_dump($tag);
			$newVal = eval('return '.$tag->nodeValue);
			$tag->nodeValue = '';

			$newNode = $dom->createElement("span", $newVal);
			$tag->parentNode->replaceChild($newNode, $tag);
		}

		$viewDoc = new domDocument();
		$viewDoc->loadHTML($view);
		//<php> tags in the view
		$evalTags = $viewDoc->getElementsByTagName("php");
		foreach($evalTags as $tag) {
			//var_dump($tag);
			$newVal = eval('return '.$tag->nodeValue);
			$tag->nodeValue = '';

			$newNode = $viewDoc->createElement("span", $newVal);
			$tag->parentNode->replaceChild($newNode, $tag);
		}
		$viewNode = $viewDoc->getElementsByTagName("body")->item(0);
		$node = $dom->importNode($viewNode, true);
		$content->appendChild($node);





//		for($i = 0; $i < $evalTags->length; $i++) {
//			echo $i;
//			$tag = $evalTags->item($i);
//			var_dump($tag);
//			$newVal = eval('return '.$tag->nodeValue);
//			$tag->nodeValue = '';
//
//			$newNode = $dom->createElement("span", $newVal);
//			$tag->parentNode->replaceChild($newNode, $tag);
//		}
		return $dom;
	}
}