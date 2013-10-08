<?php

class Model_Template_Test extends WP_UnitTestCase {
	public $post;
	/**
	 * 
	 */
	public function setUP()
	{
		parent::setUp();

		$post_args = array(
		  'post_title'    => 'My post',
		  'post_content'  => 'This is my post.',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_category' => array(8,39)
		);

		$this->post = get_post(wp_insert_post( $post_args ));
	
		$this->assertNotInstanceOf('WP_Error', $this->post);
	}

	/**
	 * 
	 */
	public function tearDown()
	{
		parent::tearDown();
		wp_delete_post($this->post->ID, true);
	}


	public function testInstanceOf()
	{
		$model = new Model_Template($this->post);

		$this->assertInstanceOf('Model_Template', $model);

		$this->assertObjectHasAttribute('title', $model);
		$this->assertObjectHasAttribute('id', $model);

		$this->assertAttributeInternalType('string', 'title', $model);
		$this->assertAttributeInternalType('integer', 'id', $model);

		$this->assertAttributeEquals($this->post->ID, 'id', $model);
		$this->assertAttributeEquals($this->post->post_title, 'title', $model);
	}
}