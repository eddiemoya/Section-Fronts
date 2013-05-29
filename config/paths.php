<?php

class Section_Front_Paths{
	private $root;
	private $controllers;
	private $models;
	private $views;

	public function __construct(){
		$this->root = dirname(plugin_dir_path(__FILE__));
		$this->controllers = $this->root . '/controllers/';
		$this->models = $this->root . '/models/';
		$this->views = $this->root . '/views/';
	}

	public function load($base, $file, $ext = 'php'){
		include ($this->{$base} . $file . '.' . $ext);
	}

	public function get($property){
		return $this->{$property};
	}
}
