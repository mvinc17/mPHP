<?php

/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 15/7/16
 * Time: 6:52 PM
 */
class SASS_Compiler extends Compiler
{
	public function __construct()
	{
		parent::__construct();
	}

	public function compile() {
		$this->run("../app/assets/scss/", "../app/assets/compiled/css/");
	}

	private function run($scss_folder, $css_folder, $format_style = "scss_formatter")
	{
		$cssstring = "";

		// scssc will be loaded automatically via Composer
		$scss_compiler = new scssc();
		// set the path where your _mixins are
		$scss_compiler->setImportPaths($scss_folder);
		// set css formatting (normal, nested or minimized), @see http://leafo.net/scssphp/docs/#output_formatting
		$scss_compiler->setFormatter($format_style);
		// get all .scss files from scss folder
		$filelist = glob($scss_folder . "*.scss");
		// step through all .scss files in that folder
		foreach ($filelist as $file_path) {
			// get path elements from that file
			$file_path_elements = pathinfo($file_path);
			// get file's name without extension
			$file_name = $file_path_elements['filename'];
			// get .scss's content, put it into $string_sass
			$string_sass = file_get_contents($scss_folder . $file_name . ".scss");
			// compile this SASS code to CSS
			$string_css = $scss_compiler->compile($string_sass);
			$cssstring .= PHP_EOL.$string_css;
			// write CSS into file with the same filename, but .css extension
			file_put_contents($css_folder . $file_name . ".css", $string_css);
		}

		$minified = $this->minify_css($cssstring);
		file_put_contents(PUB_PATH.'/assets/css.min.css', $minified);
	}

	private function minify_css($input) {
		if(trim($input) === "") return $input;
		// Force white-space(s) in `calc()`
		if(strpos($input, 'calc(') !== false) {
			$input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
				return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
			}, $input);
		}
		return preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
				'#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
				'#\x1A#'
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2',
				' '
			),
			$input);
	}
}