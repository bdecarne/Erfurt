<?

/**
  * Erfurt plug-in manager
  *
  * @package Plugin
  * @subpackage Widget
  * @author Norman Heino <norman@feedface.de>
  * @version $Id$
  */
class Erfurt_Plugin_Manager {
	
	/**
	  * @var An array of plug-ins found and loaded.
	  */
	protected $_activePlugins;
	
	/**
	  * @var An array of plug-in dirs to be scanned.
	  */
	protected $_pluginDirs;
	
	/**
	  * Constructs an erfurt plug-in manager object.
	  *
	  * @param array|string $pluginDirs One ore more directories to be
	  * scanned for plug-ins.
	  */
	public function __contruct($pluginDirs = array()) {
		$this->_activePlugins = array();
		
		if (is_array($pluginDirs)) {
			$this->_pluginDirs = $pluginDirs();
		} elseif (is_string($pluginDirs)) {
			$this->_pluginDirs[] = $pluginDirs;
		}
	}
	
	/**
	  * Add a plug-in directory to be searched.
	  *
	  * @param string $path The path to a plug-in directory. 
	  */
	public function addPluginDir($path) {
		if (file_exists($path)) {
			$this->_pluginDirs[] = $path;
			$this->_scanPluginDir($path);
		}
	}
	
	/**
	  * Returns an array of plug-in class names found in the plug-in directories.
	  *
	  * @return array
	  */
	public function getActivePlugins($type = null) {
		// if (empty($this->_activePlugins)) {
		// 	$this->_scanPluginDirs();
		// }
		// if ($type && $type === 'widget') {
		// 	// TODO: return widgets only
		// 	return array();
		// }
		return $this->_activePlugins;
	}
	
	/**
	  * Returns true iff the given plug-in class name has been found and
	  * can be instantiated.
	  *
	  * @param string $className The name of a plug-in class.
	  */
	public function isPluginActive($className) {
		// if (empty($this->_activePlugins)) {
		// 	$this->_scanPluginDirs();
		// }
		return in_array($className, $this->_activePlugins);
	}
	
	/**
	  * Scans $directory for valid Erfurt plug-ins and stores their class names
	  * in the $_activePlugins member.
	  * A plug-in is considered valid if the following conditions hold.
	  * <ul>
	  *   <li>Either a php file or a subfolder of one of the plug-in dirs.</li>
	  *   <li>In case of a subfolder, this folder contains a php file of exactly the same name.</li>
	  *   <li>The php file declares a class of the same name (without the .php extension).</li>
	  *   <li>The class (or another class of the same name) has not already been loaded</li>
	  * </ul>
	  *
	  * @param string $directory The path to a directory to be scanned.
	  */
	private function _scanPluginDir($directory) {
		$contents = scandir($directory);
		
		// scan dir contens
		foreach ($contents as $fileName) {
			$searchPath = $directory . $fileName;
			$pathInfo = pathinfo($searchPath);
			
			if (is_dir($searchPath)) {
				// if it's a directory, scan its contents for a file of the same name
				// but with a .php extension
				if (file_exists($searchPath . DIRECTORY_SEPARATOR . $fileName . '.php')) {
					if (!class_exists($fileName)) {
						include_once $searchPath . DIRECTORY_SEPARATOR . $fileName . '.php';
						$className = $fileName;
					} else {
						throw new Erfurt_Exception('Class ' . $fileName . ' already exists!');
					}
				}
			// scan for file-only plug-ins
			} elseif ($pathInfo['extension'] === 'php') {
				if (!class_exists($fileName)) {
					include_once $searchPath;
					$className = substr($fileName, 0, strlen($fileName) - 4);
				} else {
					throw new Erfurt_Exception('Class ' . $fileName . ' already exists!');
				}
			}
			
			// add class found
			if ($className && class_exists($className)) {
				$this->_activePlugins[$className] = $className;
				unset($className);
			}
		}
	}
}

?>
