<?php

class Model_Template {
	public $title;
	public $id;

	/**
	 * @param $post [int|object] - The post id or post object.
	 */
	public function __construct($post){
		// $post can be a post object or an id.
		if (!is_object($post) && is_numeric($post)) {

			//if $post is an id, get the post object;
			$post = get_post($post);
		}

		$this->title = $post->post_title;
		$this->id = $post->ID;
	}
}