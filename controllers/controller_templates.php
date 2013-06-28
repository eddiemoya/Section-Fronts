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
		add_action( 'edited_skcategory', array($this, 'edited_term'));

		add_action('template_redirect', 		array($this, 'dont_redirect_canonical'), 0);

	}

	public function dont_redirect_canonical($arg){
		remove_filter('template_redirect', 'redirect_canonical');
	}



	public function save_section(){

		$node = new WP_Node($_POST['tag_ID'], $this->taxonomy, 'id');

		$node->update_meta_data('sf_category_template', $_POST['category-template']);
		$node->update_meta_data('sf_posts_template', $_POST['posts-template']);
		$node->update_meta_data('sf_questions_template', $_POST['questions-template']);
		$node->update_meta_data('sf_guides_template', $_POST['guides-template']);
		$node->update_meta_data('sf_videos_template', $_POST['videos-template']);

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

		$templates = $this->get_template_options();
		
		$name = "category-template";
		$css_id = "category-template";
		$label = ucfirst($this->taxonomy) ." Template";
		$default = array("Default", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_posts_template');

		$templates = $this->get_template_options();
		
		$name = "posts-template";
		$css_id = "posts-template";
		$label = "Posts Template";
		$default = array("Inherit from category", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_guides_template');

		$templates = $this->get_template_options();
		
		$name = "guides-template";
		$css_id = "guides-template";
		$label = "Guides Template";
		$default = array("Inherit from category", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');

		$layout_value = $node->get_meta_data('sf_questions_template');

		$templates = $this->get_template_options();
		
		$name = "questions-template";
		$css_id = "questions-template";
		$label = "Questions Template";
		$default = array("Inherit from category", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');
	
		$layout_value = $node->get_meta_data('sf_videos_template');

		$templates = $this->get_template_options();
		
		$name = "videos-template";
		$css_id = "videos-template";
		$label = "Videos Template";
		$default = array("Inherit from category", "0");
		
		include ($this->paths->get('views') . 'forms/dropdown.php');
	}





	private function get_template_options(){
		if(!class_exists("Model_Template")){
			$this->paths->load('models', 'Model_Template');
		}
		$sections = new WP_Query(array('post_type' => $this->taxonomy));
		return $sections->posts;

	}

	public function edited_term($term_id){
		$node = new WP_Node($term_id, $this->taxonomy);
		$node->add_meta_data($this->taxonomy . '_template', $POST['template']);
	}

 
	public function get_node_template() {
		//if(is_tax()){
			$term = get_queried_object();
			$taxonomy = $term->taxonomy;

			global $wp_query;
			print_pre($wp_query);

			$node = new WP_Node($term->term_id, $taxonomy );
			print_pre($term);
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

