<?php




class Controller_Templates {
	private $paths;
	private $taxonomy;
	private $node;

	public function __construct($taxonomy){
		$this->paths = new Section_Front_Paths();
		$this->taxonomy = $taxonomy;

		$this->add_actions();
	}

	private function add_actions(){

	
		add_action('init',						array($this, 'setup_node'));
		add_filter(	'query_vars', 				array(__CLASS__, 'add_query_vars'));
		//add_action(	'template_redirect', 		array($this, 'dont_redirect_canonical'), 0);

		add_action( $this->taxonomy . '_edit_form_fields', array($this, 'category_template_selector'));

	}


	public function setup_node(){
		$this->node = new WP_Node_Controller($this->taxonomy);
		$this->node->create_node($_REQUEST['tag_ID']);
	}


	public function add_query_vars($qvars) {
		//$qvars[] = 'meta_key';
		$qvars[] = 'sf_filter';
		return $qvars;
	}



	public function dont_redirect_canonical($arg){
		remove_filter('template_redirect', 'redirect_canonical');
	}



	public function category_template_selector(){

		if($this->taxonomy == 'category') {

			$this->get_textarea($this->node, 'post_description', 'Posts Description', 'textarea', 'Description for %CAT%/posts');
			$this->get_textarea($this->node, 'guide_description', 'Guides Description', 'textarea', 'Description for %CAT%/guides');
			$this->get_textarea($this->node, 'question_description', 'Questions Description', 'textarea', 'Description for %CAT%/questions');
			$this->get_textarea($this->node, 'video_description', 'Videos Description', 'textarea', 'Description for %CAT%/videos');
		}

		$this->get_dropdown($this->node, $this->taxonomy, ucfirst($this->taxonomy), 'dropdown' );

		if($this->taxonomy == 'category') {

			$this->get_dropdown($this->node, 'post_template', 'Posts Template', 'dropdown');
			$this->get_dropdown($this->node, 'guide_template', 'Guides Tempalte', 'dropdown');
			$this->get_dropdown($this->node, 'question_template', 'Questions Template', 'dropdown');
			$this->get_dropdown($this->node, 'video_template', 'Videos Template', 'dropdown');
		}
	}

	public function get_dropdown($node, $key, $label, $view, $description = ""){
	

		$layout_value = $node->get_node_meta("sf_{$key}");	
		$layout_value = (!empty($layout_value)) ? $layout_value : $node->get_post()->ID;

		$templates = $this->get_template_options();
		
		$name = "{$key}";
		$css_id = "{$key}";
		$label = $label;
		$description = str_replace('%CAT%', $this->taxonomy, $description);

		$default = array("Default", $layout_value);
		
		include ($this->paths->get('views') . "forms/{$view}.php");
	}


	public function get_textarea($node, $key, $label, $view, $description = ""){
	
		$value = $node->get_node_meta($key);
		

		$name = $key;
		$css_id = $key;
		$label = $label;
		$description = str_replace('%CAT%', "/{$node->get_node()->term->taxonomy}/{$node->get_node()->term->slug}", $description);
		
		include ($this->paths->get('views') . "forms/{$view}.php");
	}


	private function get_template_options(){
		if(!class_exists("Model_Template")){
			$this->paths->load('models', 'Model_Template');
		}
		$sections = new WP_Query(array('post_type' => $this->taxonomy));
		return $sections->posts;

	}

}



// class Controller_Templates {
// 	private $paths;
// 	private $taxonomy;
// 	private $node;

// 	public function __construct($taxonomy){
// 		$this->paths = new Section_Front_Paths();
// 		$this->taxonomy = $taxonomy;

// 		$this->add_actions();
// 	}

// 	private function add_actions(){


// 		add_action('init', array($this, 'init'));
// 		add_action( $this->taxonomy . '_edit_form_fields', array($this, 'category_template_selector'));


// 		//add_action($this->taxonomy . '_edit_form_fields', array($this, 'meta_link'));
// 		add_action( 'edited_term', array($this, 'save_section'));
// 		add_action( "edited__$this->taxonomy", array($this, 'add_node_meta'));
// 		add_filter(	'query_vars', 				array(__CLASS__, 'add_query_vars'));
// 		add_action(	'template_redirect', 		array($this, 'dont_redirect_canonical'), 0);

// 	}

// 	public function init(){
// 		$this->node = new WP_Node($_POST['tag_ID'], $_POST['taxonomy'], 'id');
// 		$this->node->register_term_meta();
// 	}

// 	public function add_query_vars($qvars) {
// 		//$qvars[] = 'meta_key';
// 		$qvars[] = 'sf_filter';
// 		return $qvars;
// 	}

// 	public function dont_redirect_canonical($arg){
// 		remove_filter('template_redirect', 'redirect_canonical');
// 	}



// 	public function save_section(){


// 		$this->node->register_term_meta();

// 		$this->node->update_meta_data('sf_category_template', $_POST['category_template']);
// 		$this->node->update_meta_data('sf_post_template', $_POST['post_template']);
// 		$this->node->update_meta_data('sf_question_template', $_POST['question_template']);
// 		$this->node->update_meta_data('sf_guide_template', $_POST['guide_template']);
// 		$this->node->update_meta_data('sf_video_template', $_POST['video_template']);

// 		$this->node->update_meta_data('post_description', $_POST['post_description']);
// 		$this->node->update_meta_data('question_description', $_POST['question_description']);
// 		$this->node->update_meta_data('guide_description', $_POST['guide_description']);
// 		$this->node->update_meta_data('video_description', $_POST['video_description']);

// 		global $wp_rewrite;
// 	   	$wp_rewrite->flush_rules();


// 	}

// 	public function meta_link(){

// 		$label = "Metadata";
// 		//print_pre($node);
// 		include ($this->paths->get('views') . 'meta-edit-link.php');
// 	}




// 	public function get_dropdown($node, $key, $label, $view){
	

// 		$layout_value = $node->get_meta_data("sf_{$key}");	
// 		$layout_value = (!empty($layout_value)) ? $layout_value : $node->post->ID;

// 		$templates = $this->get_template_options();
		
// 		$name = "{$key}";
// 		$css_id = "{$key}";
// 		$label = $label;
// 		$default = array("Default", $layout_value);
		
// 		include ($this->paths->get('views') . "forms/{$view}.php");
// 	}


// 	public function get_textarea($node, $key, $label, $view){
	
// 		$value = $node->get_meta_data($key);	
		
// 		$name = $key;
// 		$css_id = $key;
// 		$label = $label;
		
// 		include ($this->paths->get('views') . "forms/{$view}.php");
// 	}


// 	public function category_template_selector(){


// 		$node = new WP_Node($_GET['tag_ID'], $_REQUEST['taxonomy'], 'id');


// 		if($this->taxonomy == 'category') {

// 			$this->get_textarea($this->node, 'post_description', 'Posts Description', 'textarea');
// 			$this->get_textarea($this->node, 'guide_description', 'Guides Description', 'textarea');
// 			$this->get_textarea($this->node, 'question_description', 'Questions Description', 'textarea');
// 			$this->get_textarea($this->node, 'video_description', 'Videos Description', 'textarea');
// 		}

// 		$this->get_dropdown($this->node, $this->taxonomy, ucfirst($this->taxonomy), 'dropdown' );

// 		if($this->taxonomy == 'category') {

// 			$this->get_dropdown($this->node, 'post_template', 'Posts Template', 'dropdown');
// 			$this->get_dropdown($this->node, 'guide_template', 'Guides Tempalte', 'dropdown');
// 			$this->get_dropdown($this->node, 'question_template', 'Questions Template', 'dropdown');
// 			$this->get_dropdown($this->node, 'video_template', 'Videos Template', 'dropdown');
// 		}
// 	}





	// private function get_template_options(){
	// 	if(!class_exists("Model_Template")){
	// 		$this->paths->load('models', 'Model_Template');
	// 	}
	// 	$sections = new WP_Query(array('post_type' => $this->taxonomy));
	// 	return $sections->posts;

	// }

// 	public function add_node_meta($term_id){

// 		$this->node->add_meta_data($this->taxonomy . '_template', $POST['template']);

// 		global $wp_rewrite;
// 	   	$wp_rewrite->flush_rules();
// 	}

 
// 	public function get_node_template() {
// 		//if(is_tax()){
// 			$term = get_queried_object();
// 			$taxonomy = $term->taxonomy;

// 			global $wp_query;
// 			//print_pre($wp_query);

	
// 			$layout = WidgetPress_Controller_Widgets::display_dropzones($this->node->post->ID);
// 			//if(!empty($layout)){
// 				//exit();
// 			//}
// 		//}
// 	}
// }


