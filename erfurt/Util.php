<?php

/**
  * class providing some util methods
  *
  * @author Stefan Berger <berger@intersolut.de>
  * @author Norman Heino <norman@feedface.de>
  * @copyright AKSW Team
  * @version $Id: Util.php 919 2007-04-01 22:48:13Z nheino $
  */
class Erfurt_Util {
	
   /**
	 * Merge two-three ini-files and returns array
	 *
	 * @param string ini files
	 * @return array ini-content
	 */
	public static function mergeIniFiles($iniFiles = array()) {
		$ret = array();
		foreach($iniFiles as $ini) {
			if (!file_exists($ini))
				throw new Erfurt_Exception("ini file: '".$ini."' doesn't exist", 601);
			# parse ini
			$ret = array_merge($ret, parse_ini_file($ini, true));
		}
		return $ret;
	}
	
	/**
	  * Replaces the value for $param in the current URL 
	  * (<code>$_SERVER['QUERY_STRING']</code>) by $value.
	  *
	  * @param $param mixed The param or an array of params whose value(s) should be replaced.
	  * @param mixed $value The new value of $param or false.
	  *
	  * @return string
	  */
	public static function replaceUrlParam($param_replace, $value_replace = false) {		
		// split query string
		$queries = explode('&', $_SERVER['QUERY_STRING']);
		
		// split queries in param and value
		$replaced = false;
		foreach ($queries as $query) {
			$qry = explode('=', $query);
			
			// replace value if given
			if ($value_replace && ($qry[0] === $param_replace || (is_array($param_replace) && in_array($qry[0], $param_replace)))) {
				$url_params[$qry[0]] = $value_replace;
				$replaced = true;
			} else {
				$url_params[$qry[0]] = $qry[1];
			}
		}
		
		// add param if not found and hence not replaced
		if ($value_replace && !$replaced) {
			$url_params[$param_replace] = $value_replace;
		}
		
		// remove double params
		$url_params = array_unique($url_params);
		
		if (isset($_SERVER['REDIRECT_URL'])) {
			$url_head = $_SERVER['REDIRECT_URL'];
		} else {
			$url_head = $_SERVER['PHP_SELF'];
		}
		
		return ($url_head . '?' . http_build_query($url_params));
	}
	
	/**
	  * Returns HTML code containing links that support paging.
	  *
	  * @param $start int the first item.
	  * @param $erg int The number of items on one page.
	  * @param $end int the last item.
	  *
	  * @return string
	  */
	public static function getListHeaderHtml($start, $count, $end) {
		
	}
	
}

?>