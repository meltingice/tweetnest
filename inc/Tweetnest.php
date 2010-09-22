<?
error_reporting(E_ALL ^ E_NOTICE);
mb_language("uni");
mb_internal_encoding("UTF-8");

define("TWEET_NEST", "0.8"); // Version number

class Tweetnest {

	public static $config;
	
	public static function run() {
		Tweetnest::load_config();
		Tweetnest::init();
	}
	
	private static function load_config() {
		require dirname(__FILE__).'/config.php';
		self::$config = $config;
	
		/* Define absolute path to application */
		$fPath = explode("/", rtrim(__FILE__, "/"));
		array_pop($fPath); array_pop($fPath);
		$fPath = implode($fPath, "/");
		define("FULL_PATH", $fPath);
		define('FULL_INC_PATH', $fPath.'/inc');
		
		/* Set and define timezone settings */
		date_default_timezone_set($config['timezone']);
		define("DTP", $config['db']['table_prefix']);
	}
	
	private static function init() {
		spl_autoload_register(array('Tweetnest', 'autoload'));
	}
	
	public static function autoload($class) {		
		if(file_exists(FULL_INC_PATH."/$class.php")) {
			include FULL_INC_PATH."/$class.php";
		} elseif(file_exists(FULL_INC_PATH.'/'.strtolower($class).'.php')) {
			include FULL_INC_PATH.'/'.strtolower($class).'.php';
		} elseif(file_exists(FULL_INC_PATH.'/class.'.strtolower($class).'.php')) {
			include FULL_INC_PATH.'/class.'.strtolower($class).'.php';
		} elseif(file_exists(FULL_INC_PATH."/Tweetnest_$class.php")) {
			include FULL_INC_PATH."/Tweetnest_$class.php";
		}
	}
	
}