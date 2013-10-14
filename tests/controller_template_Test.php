<?php

class controller_template_Test extends WP_UnitTestCase {
	public $term;

	public function setUP()
	{
		parent::setUp();
		wp_delete_post(1, true);
		$term = wp_insert_term('test-category', 'category');
		$this->term = get_term_by('slug', 'test-category', 'category');
		$_REQUEST['tag_ID'] = $this->term->term_id;
	}

	public function tearDown()
	{
		parent::tearDown();
		wp_delete_term($this->term->term_id, $this->term->taxonomy);
	}

	public function testControllerTemplate()
	{
		$temp_cont = new Controller_Templates($this->term->taxonomy);
		$this->assertAttributeInstanceOf('Section_Front_Paths', 'paths', $temp_cont);
		$this->assertAttributeInternalType('string', 'taxonomy', $temp_cont);
		$this->assertAttributeEquals($this->term->taxonomy, 'taxonomy', $temp_cont);
		$this->assertAttributeEmpty('node', $temp_cont);
	}


	public function testControllerTemplate_setupNode()
	{
		$temp_cont = new Controller_Templates($this->term->taxonomy);
		$temp_cont->setup_node();
		$this->assertAttributeInstanceOf('WP_Node_Factory', 'node', $temp_cont);
	}

	/**
	 * This tests that we are able to create single posts will NULL post type and a colon for a title.
	 *
	 * @group wpnode
	 * @group wpnode_class
	 * @group bugfixes
	 * @group colonbug
	 *
	 */
	public function test_colon_bug(){
		
		if(isset($_REQUEST['tag_ID'])) 
		{
			unset($_REQUEST['tag_ID']);
		}

		$section = new Controller_Templates($this->term->taxonomy);
		$section->setup_node();

		$colon_post = get_page_by_title(': ', 'OBJECT', 'post');
		$query = new WP_Query('post_type=post');

		$this->assertAttributeEquals(0, 'post_count', $query, 'Test for Colon Bug failed. Wrong number of posts exist.');
		$this->assertAttributeEmpty('posts', $query, 'Test for Colon Bug failed. Wrong number of posts exist.');
		$this->assertNull($colon_post, 'Test for the Colon Bug failed. Found a post with a colon as the title');

		$query = new WP_Query('post_type=post');

		$this->assertAttributeEquals(0, 'post_count', $query, 'The control test for Colon Bug failed. Unable to delete all "colon" posts.');
	}



}