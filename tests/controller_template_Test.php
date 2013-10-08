<?php

class controller_template_Test extends WP_UnitTestCase {
	public $term;

	public function setUP()
	{
		parent::setUp();

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
}