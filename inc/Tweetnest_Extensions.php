<?
class Extensions {
	
	private static $hooks = array();
	private static $allowed_hooks = array(
		'tweet', 'before_tweet'
	);
	
	// This should be a class variable, but PHP
	// currently does not allow anonymous functions
	// to be stored in class variables.
	private static function defaults() {
		return array(
			"before_tweet" => function($data) {
				return "";
			}
		);
	}
	
	public static function load_all_extensions() {
		if($h = opendir(FULL_PATH.'/extensions/enabled')) {
			while (false !== ($file = readdir($h))) {
				if($file == '.' || $file == '..'){ continue; }
				if(!is_file(FULL_PATH."/extensions/enabled/$file")){ continue; }
				
				$file_info = explode('.', $file);
				$ext = array_pop($file_info);
				if($ext == 'php') {
					include(FULL_PATH."/extensions/enabled/$file");
				}
			}
		}
	}
	
	public static function execute_hook($hook, $data = "") {
		$defaults = self::defaults();
		if(self::hook_registered($hook)) {
			/* Execute all hooks for this type */
			foreach(self::$hooks[$hook] as $func) {
				$return = $func($data);
				if($return !== null) {
					$data = $return;
				}
			}
		} elseif(array_key_exists($hook, $defaults)) {
			$data = $defaults[$hook]();
		}
		
		return $data;
	}
	
	public static function register_hook(Extension $ext) {
		if(in_array($ext->hook, self::$allowed_hooks)) {
			self::$hooks[$ext->hook][] = $ext->func;
		}
	}
	
	private static function hook_registered($hook) {
		if(array_key_exists($hook, self::$hooks)) {
			return true;
		}
	}
}