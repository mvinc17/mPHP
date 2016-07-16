<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 7:28 PM
 */

$_mphp_included_files = array();
$_mphp_loaded_libs = array();
$_mphp_loaded_controls = array();

function register_include($filePath) {
	global $_mphp_included_files;

	if(!in_array(stripslashes(strtolower($filePath)), $_mphp_included_files)) {
		if(file_exists($filePath)) {
			$_mphp_included_files[] = stripslashes(strtolower($filePath));
			include $filePath;
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

//Load constants
register_include(mPHP_PATH.'/_mphp/constants.php');


//Load Helpers
register_include(mPHP_PATH.'/_mphp/helpers/functions.php');


//Load Libs
$libs = json_decode(file_get_contents(mPHP_PATH.'/_mphp/lib/autoload.json'));

foreach($libs as $lib) {
	global $_mphp_loaded_libs;

	foreach($lib->files as $file) {
		if(!register_include(mPHP_PATH.'/_mphp/lib/' . $file)) {
			throw_error("Failed to load library " . $lib->name);
		}
	}

	$_mphp_loaded_libs[] = $lib;
}


//Load Utilities
foreach(array_diff(scandir(APP_PATH.'/utilities/'), array('..', '.')) as $file) {
	register_include(APP_PATH.'/utilities/'.$file);
}

//Load Compilers
register_include(mPHP_PATH.'/_mphp/compilers/Compiler.php');
register_include(mPHP_PATH.'/_mphp/compilers/JS_Compiler.php');
register_include(mPHP_PATH.'/_mphp/compilers/SASS_Compiler.php');
register_include(mPHP_PATH.'/_mphp/compilers/View_Compiler.php');

register_include(mPHP_PATH.'/_mphp/helpers/mvcFunctions.php');


//Instantiate Asset Manager
register_include(mPHP_PATH.'/_mphp/helpers/assetManager.php');
$AssetManager = new AssetManager();

//Load assets in the asset folder
foreach(array_diff(scandir(APP_PATH.'/assets/js/'), array('..', '.')) as $file) {
	$AssetManager->addJS(APP_PATH.'/assets/js/'.$file);
}

foreach(array_diff(scandir(APP_PATH.'/assets/scss/'), array('..', '.')) as $file) {
	$AssetManager->addSCSS(APP_PATH.'/assets/scss/'.$file);
}

//Load Controls
$controls = json_decode(file_get_contents(APP_PATH.'/controls/controls.json'));
foreach($controls as $control) {
	$_mphp_loaded_controls[] = $control;
	$files = $control->files;
	foreach($files as $file) {
		register_include(APP_PATH.'/controls/'.$file);
	}
}

//Compile the assets before the page is shown

$AssetManager->compile();

//Load router and route to relevant controller
register_include(mPHP_PATH.'/_mphp/router.php');
