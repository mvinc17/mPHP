<?php
/**
 * mPHP Application Router
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 8:11 PM
 */

$routes = (array)json_decode(file_get_contents(APP_PATH.'/routes.json'));

$path = request_path();


if(in_array($path, $routes)) {
	$route = $routes[$path];
	register_include(APP_PATH.'/controllers/'.$route->ctrl.'.php');
	$type = $route->ctrl;
	define("ROUTE_CONTROLLER", $route->ctrl);
	$ctrl = new $type();
	if(method_exists($ctrl, $route->view)) {
		define("ROUTE_VIEW", $route->view);
		$ctrl->{$route->view}();
	} else if(method_exists($ctrl, 'index')) {
		define("ROUTE_VIEW", 'index');
		$ctrl->index();
	} else {
		throw_error('No methods found', mPHP_ERROR_404);
	}
} else {
	$parts = explode('/', $path);

	if($path == "") {
		$parts[0] = "root";
	}

	register_include(APP_PATH.'/controllers/'.$parts[0].'.php');
	$type = $parts[0];
	define("ROUTE_CONTROLLER", $type);
	$ctrl = new $type();

	if(method_exists($ctrl, $parts[1])) {
		define("ROUTE_VIEW", $parts[1]);
		if($parts[2] != null) {
			$ctrl->{$parts[1]}($parts[2]);
		} else {
			$ctrl->{$parts[1]}();
		}
	} else if(method_exists($ctrl, 'index')) {
		define("ROUTE_VIEW", 'index');
		$ctrl->index();
	} else {
		throw_error('No methods found', mPHP_ERROR_404);
	}
}



function request_path()
{
	$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
	$script_name = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
	$parts = array_diff_assoc($request_uri, $script_name);
	if (empty($parts))
	{
		return '/';
	}
	$path = implode('/', $parts);
	if (($position = strpos($path, '?')) !== FALSE)
	{
		$path = substr($path, 0, $position);
	}
	return $path;
}