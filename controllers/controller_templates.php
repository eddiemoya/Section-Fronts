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

		$node = new WP_Node($_POST['tag_ID'], $this->taxonomy, 'id');

		$node->update_meta_data('sf_category_template', $_POST['category-template']);
		$node->update_meta_data('sf_post_template', $_POST['post-template']);
		$node->update_meta_data('sf_question_template', $_POST['question-template']);
		$node->update_meta_data('sf_guide_template', $_POST['guide-template']);
		$node->update_meta_data('sf_video_template', $_POST['video-template']);

		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();


	}

	public function meta_link(){


		$label = "Metadata";
		$node = new WP_Node($_GET['tag_ID'], $this->taxonomy, 'id');
		//print_pre($node);
		include ($this->paths->get('views') . 'meta-edit-link.php');
	}




	public function category_template_selector(){


		$node = new WP_Node($_GET['tag_ID'], $this->taxonomy, 'id');

		$layout_value = $node->get_meta_data('sf_category_template');
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "category-template";
		$css_id = "category-template";
		$label = ucfirst($this->taxonomy) ." Template";
		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_post_template');
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "post-template";
		$css_id = "post-template";
		$label = "Posts Template";
		$default = array("Default", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_guide_template');
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "guide-template";
		$css_id = "guide-template";
		$label = "Guide Template";
		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_question_template');
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "question-template";
		$css_id = "question-template";
		$label = "Questions Template";
		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . 'forms/dropdown.php');
	
		$layout_value = $node->get_meta_data('sf_video_template');
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

		$templates = $this->get_template_options();
		
		$name = "video-template";
		$css_id = "video-template";
		$label = "Videos Template";
		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . 'forms/dropdown.php');
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
// function get_taxonomy_template() {
// 	$term = get_queried_object();
// 	$taxonomy = $term->taxonomy;

// 	$templates = array();

// 	$templates[] = "taxonomy-$taxonomy-{$term->slug}.php";
// 	$templates[] = "taxonomy-$taxonomy.php";
// 	$templates[] = 'taxonomy.php';

// 	return get_query_template( 'taxonomy', $templates );
// }


// function get_category_template() {
// 	$category = get_queried_object();

// 	$templates = array();

// 	$templates[] = "category-{$category->slug}.php";
// 	$templates[] = "category-{$category->term_id}.php";
// 	$templates[] = 'category.php';

// 	return get_query_template( 'category', $templates );
// }

