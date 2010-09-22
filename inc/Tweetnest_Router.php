<?

class Router {
	public static $PAGE = 'index';
	private static $routes = array(
		'sort' => '^\/sort\/?$',
		'favorites' => '^\/favorites\/?$',
		'search' => '^\/search\/?$'
		'month' => '^\/([0-9]+)\/([0-9]+)\/?$',
		'day' => '^\/([0-9]+)\/([0-9]+)\/([0-9]+)\/?$'
	);
	
	public static function run() {
		self::determine_page();
		
		
	}
	
	private static function determine_page() {
		if(!isset($_GET['tn_uri'])) {
			return;
		} else {
			$uri = $_GET['tn_uri'];
			foreach(self::$routes as $page => $regex) {
				if(preg_match($regex, $uri)) {
					self::$PAGE = $page;
					return;
				}
			}
		}
	}
	
}