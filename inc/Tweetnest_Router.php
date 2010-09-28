<?

class Router {
	public static $PAGE = 'index';
	public static $PARAMS = array();
	private static $routes = array(
		'index' => '',
		'sort' => '/^sort\/?$',
		'favorites' => '/^favorites\/?$/',
		'search' => '/^search\/?$/',
		'month' => '/^([0-9]+)\/([0-9]+)\/?$/',
		'day' => '/^([0-9]+)\/([0-9]+)\/([0-9]+)\/?$/'
	);
	
	private static $active_controller;
	
	public static function run() {
		self::determine_page();
		self::determine_params();
		
		$controller = ucfirst(self::$PAGE).'_Controller';
		self::$active_controller = new $controller();
		self::$active_controller->load();
	}
	
	private static function determine_page() {
		if(!isset($_GET['tn_uri'])) {
			return;
		} else {
			$uri = $_GET['tn_uri'];
			if($uri == "" || $uri == "/") { return; } // index page
			
			foreach(self::$routes as $page => $regex) {
				if(preg_match($regex, $uri)) {
					self::$PAGE = $page;
					return;
				}
			}
		}
	}
	
	private static function determine_params() {
		if(self::$PAGE == 'index') return;
		
		preg_match_all(self::$routes[self::$PAGE], $_GET['tn_uri'], $matches);
		self::$PARAMS = $matches[1];
	}
	
	public static function is_index() {
		return (self::$PAGE == 'index');
	}
	
}