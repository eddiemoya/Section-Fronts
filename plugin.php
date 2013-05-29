<?php
/*
Plugin Name: Section Fronts
Description: Magical things that sound like fun, but might end badly.
Version: 0.1
Author: Eddie Moya
Author URI: http://eddiemoya.com
*/


include (plugin_dir_path(__FILE__) . 'config/paths.php');

$paths = new Section_Front_Paths();
//print_r($paths);
$paths->load('controllers', 'controller_templates');
$category = new Controller_Templates('category');
$skcategory = new Controller_Templates('skcategory');
