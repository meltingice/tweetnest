<?

abstract class Controller {
	public abstract function load();
	
	public function render($file, $data) {
		extract($data);
		
		ob_start();
		include(FULL_INC_PATH."/views/$file.php");
		$rendered = ob_get_contents();
		ob_end_clean();
		
		return $rendered;
	}
}