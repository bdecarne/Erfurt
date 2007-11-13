<?php

/**
 * RDFSmodel
 *
 * @package RDFSAPI
 * @author S�ren Auer <soeren@auer.cx>
 * @copyright Copyright (c) 2004
 * @version $Id$
 * @access public
 **/
abstract class Erfurt_Rdfs_InstanceSearch_Abstract {
	
	var $model;
	var $store;
	var $searchString;
	var $classes;
	var $properties;
	
	public function __construct(&$model, $searchString, $classes = array(), $properties = array(), $store = null) {
		if ($model instanceof RDFSModel) {
			$this->model     = &$model;
			$this->store     = $model->getStore();
			$this->allModels = false;
		} else {
			$this->store = $store;
			$this->allModels = true;
		}
		
		$this->searchString = $searchString;
		$this->classes      = $classes;
		
		foreach ($this->classes as $class) {
			$cl = $this->model->classF($class);
			$this->classes = array_merge($this->classes, $cl->listSubClassesRecursive());
		}
		
		$this->properties=$properties;
	}
	
	abstract public function listClasses();
	abstract public function listProperties();
	abstract public function search($start = 0, $count = 10, $erg = 0);
	abstract public function searchInstance($instance);
}

?>