<?php

class Controller_Templates {
	private $paths;
	private $taxonomy;

	public function __construct($taxonomy){
		$this->paths = new Section_Front_Paths();
		$this->taxonomy = $taxonomy;
		$this->add_actions();


	}

	private function add_actions(){

		
		add_action( $this->taxonomy . '_edit_form_fields', array($this, 'category_template_selector'));


		//add_action($this->taxonomy . '_edit_form_fields', array($this, 'meta_link'));
		add_action( 'edited_term', array($this, 'save_section'));
		add_action( "edited__$this->taxonomy", array($this, 'add_node_meta'));
		add_filter(	'query_vars', 				array(__CLASS__, 'add_query_vars'));
		add_action(	'template_redirect', 		array($this, 'dont_redirect_canonical'), 0);

	}


	public function add_query_vars($qvars) {
		//$qvars[] = 'meta_key';
		$qvars[] = 'sf_filter';
		return $qvars;
	}

	public function dont_redirect_canonical($arg){
		remove_filter('template_redirect', 'redirect_canonical');
	}



	public function save_section(){

		$node = new WP_Node($_POST['tag_ID'], $_POST['taxonomy'], 'id');

		$node->update_meta_data('sf_category_template', $_POST['category_template']);
		$node->update_meta_data('sf_post_template', $_POST['post_template']);
		$node->update_meta_data('sf_question_template', $_POST['question_template']);
		$node->update_meta_data('sf_guide_template', $_POST['guide_template']);
		$node->update_meta_data('sf_video_template', $_POST['video_template']);

		$node->update_meta_data('post_description', $_POST['post_description']);
		$node->update_meta_data('question_description', $_POST['question_description']);
		$node->update_meta_data('guide_description', $_POST['guide_description']);
		$node->update_meta_data('video_description', $_POST['video_description']);

		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();


	}

	public function meta_link(){


		$label = "Metadata";
		$node = new WP_Node($_GET['tag_ID'], $this->taxonomy, 'id');
		//print_pre($node);
		include ($this->paths->get('views') . 'meta-edit-link.php');
	}




	public function get_dropdown($node, $key, $label, $view){
	
		$layout_value = $node->get_meta_data("sf_{$key}");	
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "{$key}";
		$css_id = "{$key}";
		$label = $label;
		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . "forms/{$view}.php");
	}


	public function get_textarea($node, $key, $label, $view){
	
		$value = $node->get_meta_data($key);	
		
		$name = $key;
		$css_id = $key;
		$label = $label;
		
		include ($this->paths->get('views') . "forms/{$view}.php");
	}


	public function category_template_selector(){


		$node = new WP_Node($_GET['tag_ID'], $this->taxonomy, 'id');


		if($this->taxonomy == 'category') {

			$this->get_textarea($node, 'post_description', 'Posts Description', 'textarea');
			$this->get_textarea($node, 'guide_description', 'Guides Description', 'textarea');
			$this->get_textarea($node, 'question_description', 'Questions Description', 'textarea');
			$this->get_textarea($node, 'video_description', 'Videos Description', 'textarea');
		}

		$this->get_dropdown($node, $this->taxonomy, ucfirst($this->taxonomy), 'dropdown' );

		if($this->taxonomy == 'category') {

			$this->get_dropdown($node, 'post_template', 'Posts Template', 'dropdown');
			$this->get_dropdown($node, 'guide_template', 'Guides Tempalte', 'dropdown');
			$this->get_dropdown($node, 'question_template', 'Questions Template', 'dropdown');
			$this->get_dropdown($node, 'video_template', 'Videos Template', 'dropdown');
		}
	}





	private function get_template_options(){
		if(!class_exists("Model_Template")){
			$this->paths->load('models', 'Model_Template');
		}
		$sections = new WP_Query(array('post_type' => $this->taxonomy));
		return $sections->posts;

	}

	public function add_node_meta($term_id){
		$node = new WP_Node($term_id, $this->taxonomy);
		$node->add_meta_data($this->taxonomy . '_template', $POST['template']);

		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}

 
	public function get_node_template() {
		//if(is_tax()){
			$term = get_queried_object();
			$taxonomy = $term->taxonomy;

			global $wp_query;
			//print_pre($wp_query);

			$node = new WP_Node($term->term_id, $taxonomy );
			$layout = WidgetPress_Controller_Widgets::display_dropzones($node->post->ID);
			//if(!empty($layout)){
				//exit();
			//}
		//}
	}
}


