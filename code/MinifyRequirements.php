<?php
/**
 * 
 * An alternative {@link Requirements} backend for SilverStripe for the provision
 * of a simple file-minification API for both JavaScript and CSS files.
 * 
 * @author Nathan Cox
 * @package silverstripe-minify
 */
class Minify_Requirements_Backend extends Requirements_Backend {

	/**
	 *
	 * @var boolean 
	 */
	private static $rewrite_uris = true;

	/**
	 * 
	 * @param string $filename
	 * @param string $content
	 * @return string
	 */
	protected function minifyFile($filename, $content) {
		// If we have a javascript file and jsmin is enabled, minify the content
		$isJS = stripos($filename, '.js');
		increase_time_limit_to();
		
		if($isJS && $this->combine_js_with_jsmin) {
			$content = JSMin::minify($content);
		} elseif(stripos($filename, '.css')) {
			$minifyCSSConfig = array();

			if(self::$rewrite_uris) {
				$minifyCSSConfig['currentDir'] = Director::baseFolder() . '/' . dirname($filename);
			}

			$content = Minify_CSS::minify($content, $minifyCSSConfig);
		}

		$content .= ($isJS ? ';' : '') . PHP_EOL;
		return $content;
	}

}
