<?
error_reporting(E_ALL ^ E_NOTICE);
mb_language("uni");
mb_internal_encoding("UTF-8");

define("TWEET_NEST", "0.8"); // Version number

class Tweetnest {

	public static $config;
	public static $user;
	
	public static function run() {
		self::check_for_requirements();
		
		self::load_config();
		self::init();
		self::set_runtime_data();
		
		self::$user = User::load_active_user();
		
		Extensions::load_all_extensions();
		
		// Run router to determine page to load
		Router::run();
	}
	
	public static function load_css() {
		self::load_config();
		self::init();
		self::set_runtime_data();
		
		self::$user = User::load_active_user();
	}
	
	private static function check_for_requirements() {
		// Check for cURL
		if(!extension_loaded("curl")){
		    $prefix = (PHP_SHLIB_SUFFIX === "dll") ? "php_" : "";
		    if(!@dl($prefix . "curl." . PHP_SHLIB_SUFFIX)){
		        trigger_error("Unable to load the PHP cURL extension.", E_USER_ERROR);
		        die();
		    }
		}
		
		// Check for json_encode/decode
		if(!function_exists('json_encode') || !function_exists('json_decode')) {
			trigger_error("PHP >= 5.2.0 or PECL JSON >= 1.2.0 is required.", E_USER_ERROR);
			die();
		}
	}
	
	private static function load_config() {
		include dirname(__FILE__).'/config.php';
		self::$config = $config;
		
		if(isset(self::$config['twitter_screenname'])) {
			header('Location: setup.php'); exit;
		}
	}
	
	private static function init() {
		spl_autoload_register(array('Tweetnest', 'autoload'));
	}
	
	private static function set_runtime_data() {
		/* Define absolute path to application */
		define('PATH', self::$config['path']);
		$fPath = explode("/", rtrim(__FILE__, "/"));
		array_pop($fPath); array_pop($fPath);
		$fPath = implode($fPath, "/");
		define("FULL_PATH", $fPath);
		define('FULL_INC_PATH', $fPath.'/inc');
		
		/* Set and define timezone settings */
		date_default_timezone_set(self::$config['timezone']);
		define("DTP", self::$config['db']['table_prefix']);
		
		/* Set database time offset */
		$db = DB::connection();
		$result = $db->query("SELECT TIME_FORMAT(NOW() - UTC_TIMESTAMP(), '%H%i') AS `diff`");
		$dbtR = $db->fetch($result);
		$dbOffset = date("Z") - ($dbtR['diff'] * 36); if(!is_numeric($dbOffset)){ $dbOffset = 0; }
		
		// Explicit positivity/negativity
		$dbOffset = $dbOffset >= 0 ? "+" . $dbOffset : $dbOffset; 
		define("DB_OFFSET", $dbOffset);
		
		define("PST_GZIP", (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip") > 0));
		define("PST_GZIP_S", (PST_GZIP ? ".gz" : ""));
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
		} elseif(file_exists(FULL_INC_PATH."/lib/$class.php")) {
			include FULL_INC_PATH."/lib/$class.php";
		} elseif(preg_match("/([A-Za-z0-9]+)_Controller/", $class)) {
			if(file_exists(FULL_INC_PATH."/controllers/$class.php")) {
				include FULL_INC_PATH."/controllers/$class.php";
			}
		}
	}
	
}