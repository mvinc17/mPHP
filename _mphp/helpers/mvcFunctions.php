<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 16/7/16
 * Time: 8:05 AM
 */


function view(string $viewName = null) {
	$viewCompiler = new View_Compiler();
	$viewCompiler->compile();
//	if($viewName) {
//		if(file_exists(APP_PATH.'/views/'.$viewName.'.view')) {
//
//		} else {
//			throw_error("View not found.");
//		}
//	} else {
//
//	}
}
function control(string $controlName) {
	$control = new $controlName();
	return $control->view();
}