<?

class Extension {
	private static $name;
	private static $desc;
	
	public static function name($name) {
		self::$desc = "";
		self::$name = $name;
	}
	
	public static function desc($desc) {
		self::$desc = $desc;
	}
	
	public static function action($hook, $func) {		
		$ext = new Extension(self::$name, self::$desc, $hook, $func);
		$ext->register();
	}
	
	private function __construct($name, $desc, $hook, $func) {
		$this->name = $name;
		$this->desc = $desc;
		$this->hook = $hook;
		$this->func = $func;
	}
	
	private function register() {
		Extensions::register_hook($this);
		
		// harmless debug output
		$info = "
		<!--
		=== Loaded Extension ===
		Name: {$this->name}
		Description: {$this->desc}
		Hook: {$this->hook}
		=== End Loaded Extension ===
		-->
		";
		
		echo ltrim(str_replace("\t", "", $info));
	}
}