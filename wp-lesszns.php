<?php
/*
Plugin Name: LessZns
Plugin URI: http://platanocafe.ca
Description: Music Lesson System for Wordpress. Quizzes, Levels, Songs, Chords, Tabs, Etc 
Version: 0.0.5
Author: Adrian Toro
Author URI: http://platanocafe.ca
*/

/* Add Custom Image Sizes
===================================================*/
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'lesszns-list-thumb', 145, 145, true ); 
	add_image_size( 'lesszns-featured', 900, 400, true ); 
}

if ( ! isset( $content_width ) ) $content_width = 900;

function lesszns_name_scripts() {
	wp_enqueue_style( 'lesszns-style', plugin_dir_url( __FILE__ ).'css/wp-lesszns.css' );
}

add_action( 'wp_enqueue_scripts', 'lesszns_name_scripts' );

/* Add Student Role
===================================================*/
//remove_role('subscriber');
remove_role('editor');
remove_role('author');
remove_role('contributor');
add_role('teacher', 'Teacher');



/* Define Basic Templates
===================================================*/
function lessznz_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    //A Specific Custom Post Type
    if ( is_post_type_archive( 'lesson' ) ) {
    	$templatefilename = 'archive-lesson.php';
    }elseif (array_key_exists("post_type",$wp->query_vars) && $wp->query_vars["post_type"] == 'lesson') {
        $templatefilename = 'single-lesson.php';
    }else{
    	$templatefilename = '';
    }
	
	if ( !empty($templatefilename) ) {
	 	if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
			$return_template = TEMPLATEPATH . '/' . $templatefilename;
		} else {
			$return_template = $plugindir . '/templates/' . $templatefilename;
		}
		do_theme_redirect($return_template);
	}
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

add_action("template_redirect", 'lessznz_redirect');


/* Register Lessons Post Type
===================================================*/
function lesszns_custom_register_lessons() {
  $labels = array(
    'name' => 'Lessons',
    'singular_name' => 'Lesson',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Lesson',
    'edit_item' => 'Edit Lesson',
    'new_item' => 'New Lesson',
    'all_items' => 'All Lessons',
    'view_item' => 'View Lesson',
    'search_items' => 'Search Lessons',
    'not_found' =>  'No lessons found',
    'not_found_in_trash' => 'No lessons found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Lessons'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( "with_front" => false, 'slug' => __('lesson','lesszns') ),
    'capability_type' => 'post',
	'map_meta_cap' => true,
    'has_archive' => true, 
    'hierarchical' => true,
    'menu_position' => null,
    'menu_icon' => plugin_dir_url( __FILE__ ).'img/lesszns_ico.png', 
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 

  register_post_type( 'lesson', $args );
}
add_action( 'init', 'lesszns_custom_register_lessons' );

//Register Lesson taxonomy: Category
add_action( 'init', 'lesszns_custom_lesson_category' );
function lesszns_custom_lesson_category() 
{
   register_taxonomy(
      'lesson-type',
      'lesson',
      array(
        'label' => __( 'Type','lesszns' ),
        'rewrite' => array( "with_front" => false, 'slug' => 'type' ),
        'show_in_nav_menus' => true,
		'labels' => '',
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
   register_taxonomy(
      'lesson-level',
      'lesson',
      array(
        'label' => __( 'Level','lesszns' ),
		'rewrite' => array( "with_front" => false, 'slug' => 'level' ),
        'show_in_nav_menus' => true,
		'labels' => '',
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
}

/* Register Song Post Type
===================================================*/
function lesszns_custom_register_songs() {
  $labels = array(
    'name' => __('Songs','lesszns'),
    'singular_name' => __('Song','lesszns'),
    'add_new' => __('Add New','lesszns'),
    'add_new_item' => __('Add New Song','lesszns'),
    'edit_item' => __('Edit Song','lesszns'),
    'new_item' => __('New Song','lesszns'),
    'all_items' => __('All Songs','lesszns'),
    'view_item' => __('View Song','lesszns'),
    'search_items' => __('Search Songs','lesszns'),
    'not_found' =>  __('No Songs found','lesszns'),
    'not_found_in_trash' => __('No Songs found in Trash','lesszns'), 
    'menu_name' => __('Songs','lesszns')
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( "with_front" => false, 'slug' => __('song','lesszns') ),
    'capability_type' => 'post',
	'map_meta_cap' => true,
    'has_archive' => true, 
    'hierarchical' => true,
    'menu_position' => null,
    'menu_icon' => plugin_dir_url( __FILE__ ).'img/lesszns_ico.png', 
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 

  register_post_type( 'song', $args );
}
add_action( 'init', 'lesszns_custom_register_songs' );

//Register Songs taxonomy: Category
add_action( 'init', 'lesszns_custom_song_category' );
function lesszns_custom_song_category() 
{
   register_taxonomy(
      'song-type',
      'song',
      array(
        'label' => __( 'Type','lesszns' ),
        'rewrite' => array( "with_front" => false, 'slug' => 'song-type' ),
        'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
   register_taxonomy(
      'song-level',
      'song',
      array(
        'label' => __( 'Level','lesszns' ),
		'rewrite' => array( "with_front" => false, 'slug' => 'song-level' ),
        'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
}

/* Register E-Book Post Type
===================================================*/
function lesszns_custom_register_ebooks() {
  $labels = array(
    'name' => __('Ebooks','lesszns'),
    'singular_name' => __('Ebook','lesszns'),
    'add_new' => __('Add New','lesszns'),
    'add_new_item' => __('Add New Ebook','lesszns'),
    'edit_item' => __('Edit Ebook','lesszns'),
    'new_item' => __('New Ebook','lesszns'),
    'all_items' => __('All Ebooks','lesszns'),
    'view_item' => __('View Ebook','lesszns'),
    'search_items' => __('Search Ebooks','lesszns'),
    'not_found' =>  __('No Ebooks found','lesszns'),
    'not_found_in_trash' => __('No Ebooks found in Trash','lesszns'), 
    'menu_name' => __('Ebooks','lesszns')
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( "with_front" => false, 'slug' => __('ebook','lesszns') ),
    'capability_type' => 'post',
	'map_meta_cap' => true,
    'has_archive' => true, 
    'hierarchical' => true,
    'menu_position' => null,
    'menu_icon' => plugin_dir_url( __FILE__ ).'img/lesszns_ico.png', 
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 

  register_post_type( 'ebook', $args );
}
add_action( 'init', 'lesszns_custom_register_ebooks' );

//Register Ebooks taxonomy: Category
add_action( 'init', 'lesszns_custom_ebook_category' );
function lesszns_custom_ebook_category() 
{
   register_taxonomy(
      'ebook-type',
      'ebook',
      array(
        'label' => __( 'Type','lesszns' ),
        'rewrite' => array( "with_front" => false, 'slug' => 'ebook-type' ),
        'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
   register_taxonomy(
      'ebook-level',
      'ebook',
      array(
        'label' => __( 'Level','lesszns' ),
		'rewrite' => array( "with_front" => false, 'slug' => 'ebook-level' ),
        'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
}

/* Register Chord Post Type
===================================================*/
function lesszns_custom_register_chords() {
  $labels = array(
    'name' => 'Chords',
    'singular_name' => 'Chord',
    'add_new' => 'Add New ',
    'add_new_item' => 'Add New Chord',
    'edit_item' => 'Edit Chord',
    'new_item' => 'New Chord',
    'all_items' => 'All Chords',
    'view_item' => 'View Chord',
    'search_items' => 'Search Chords',
    'not_found' =>  'No chordss found',
    'not_found_in_trash' => 'No chordss found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Chords'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => '' ),
    'capability_type' => 'post',
	'map_meta_cap' => true,
    'has_archive' => true, 
    'hierarchical' => true,
    'menu_position' => null,
    'menu_icon' => plugin_dir_url( __FILE__ ).'img/lesszns_ico.png', 
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 

  register_post_type( 'chord', $args );
}
add_action( 'init', 'lesszns_custom_register_chords' );

//Register Chord taxonomy: Type
add_action( 'init', 'lesszns_custom_chord_type' );
function lesszns_custom_chord_type() 
{
   register_taxonomy(
      'chord_type',
      'chord',
      array(
        'label' => __( 'Type','lesszns' ),
        'rewrite' => array( 'slug' => 'chord/type' ),
        'show_in_nav_menus' => false,
		'labels' => '',
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
   register_taxonomy(
      'chord_inversion',
      'chord',
      array(
        'label' => __( 'Inversion','lesszns' ),
		'rewrite' => array( 'slug' => 'chord/inversion' ),
        'show_in_nav_menus' => false,
		'labels' => '',
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
}



/* Register Rhythm Post Type
===================================================*/
function lesszns_custom_register_rhythms() {
  $labels = array(
    'name' => 'Rhythms',
    'singular_name' => 'Rhythm',
    'add_new' => 'Add New ',
    'add_new_item' => 'Add New Rhythm',
    'edit_item' => 'Edit Rhythm',
    'new_item' => 'New Rhythm',
    'all_items' => 'All Rhythms',
    'view_item' => 'View Rhythm',
    'search_items' => 'Search Rhythms',
    'not_found' =>  'No rhythmss found',
    'not_found_in_trash' => 'No rhythmss found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Rhythms'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => '' ),
    'capability_type' => 'post',
	'map_meta_cap' => true,
    'has_archive' => true, 
    'hierarchical' => true,
    'menu_position' => null,
    'menu_icon' => plugin_dir_url( __FILE__ ).'img/lesszns_ico.png', 
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 

  register_post_type( 'rhythm', $args );
}
add_action( 'init', 'lesszns_custom_register_rhythms' );

//Register Rhythm taxonomy: Type
add_action( 'init', 'lesszns_custom_rhythm_type' );
function lesszns_custom_rhythm_type() 
{
   register_taxonomy(
      'rhythm_level',
      'rhythm',
      array(
        'label' => __( 'Level','lesszns' ),
        'rewrite' => array( 'slug' => 'rhythm/level' ),
        'show_in_nav_menus' => false,
		'labels' => '',
		'show_ui' => true,
		'show_admin_column' => true,
		'capabilities' => array (
			'manage_terms' => 'administrator',
			'edit_terms' => 'administrator',
			'delete_terms' => 'administrator',
			'assign_terms' => 'administrator', 'editor', 'author', 'contributor'
		 ),
		'query_var' => true,
        'hierarchical' => true
      )
   );
}

/* ADD META BOXES ON LESSON and SONG POST TYPE */
function lesszns_add_lesson_boxes() {
	add_meta_box( 'lesszns_video', __( 'Video URL for this lesson (Full Youtube URL) ', 'lesszns' ), 'lesszns_video_meta_box', 'lesson', 'advanced', 'high' );
	add_meta_box( 'lesszns_examples', __( 'Video IDs for Examples (Only Youtube ID) ', 'lesszns' ), 'lesszns_examples_meta_box', 'lesson', 'advanced', 'high' );

}
add_action( 'add_meta_boxes', 'lesszns_add_lesson_boxes' );

function lesszns_video_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_lesson_meta_boxes', 'lesszns_lesson_meta_boxes_nonce' );

  $video = get_post_meta( $post->ID, '_lesszns_video', true);	
  ?>
 	  	<?php _e('Video','lesszns');?>: <input type="text" id="lesszns_video" name="lesszns_video" value="<?php echo esc_attr( $video )?>" placeholder="Add Video URL (Full Youtube URL)" size="60" /><br/>
  <?php	
}
function lesszns_examples_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_lesson_meta_boxes', 'lesszns_lesson_meta_boxes_nonce' );

  $examples = get_post_meta( $post->ID, '_lesszns_examples', true);	
  ?>
 	  	<?php _e('Examples','lesszns');?>: <input type="text" id="lesszns_examples" name="lesszns_examples" value="<?php echo esc_attr( $examples )?>" placeholder="Add Examples (Youtube IDs, separated by comma)" size="60" /><br/>
  <?php	
}

function lesszns_save_lesson_metaboxes( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_lesson_meta_boxes_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_lesson_meta_boxes_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_lesson_meta_boxes' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'lesson' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  
  $video = $_POST['lesszns_video'];
  $examples = $_POST['lesszns_examples'];
      
  update_post_meta( $post_id, '_lesszns_video', $video );
  update_post_meta( $post_id, '_lesszns_examples', $examples );
}
add_action( 'save_post', 'lesszns_save_lesson_metaboxes' );

// Chords:
function lesszns_add_chords_box() {
	add_meta_box( 'lesszns_chords', __( 'Chords in this lesson ', 'lesszns' ), 'lesszns_chords_meta_box', 'lesson', 'advanced', 'high' );
	add_meta_box( 'lesszns_chords', __( 'Chords in this song ', 'lesszns' ), 'lesszns_chords_meta_box', 'song', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'lesszns_add_chords_box' );

function lesszns_chords_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_chords_meta_box', 'lesszns_chords_meta_box_nonce' );

  $chords = get_post_meta( $post->ID, '_lesszns_chords', true);	
  ?>
 	  	<?php _e('Chords','lesszns');?>: <input type="text" id="lesszns_chords" name="lesszns_chords" value="<?php echo esc_attr( $chords )?>" placeholder="Add chords ID" size="60" /><br/>
  <?php	
}

function lesszns_save_chords_postdata( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_chords_meta_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_chords_meta_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_chords_meta_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'lesson' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  
  $chords = $_POST['lesszns_chords'];
      
  update_post_meta( $post_id, '_lesszns_chords', $chords );
}
add_action( 'save_post', 'lesszns_save_chords_postdata' );


function lesszns_add_rhythms_box() {
	add_meta_box( 'lesszns_rhythms', __( 'Rhythms in this song ', 'lesszns' ), 'lesszns_rhythms_meta_box', 'song', 'advanced', 'high' );
	add_meta_box( 'lesszns_rhythms', __( 'Rhythms in this lesson ', 'lesszns' ), 'lesszns_rhythms_meta_box', 'lesson', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'lesszns_add_rhythms_box' );

function lesszns_rhythms_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_rhythms_meta_box', 'lesszns_rhythms_meta_box_nonce' );

  $rhythms = get_post_meta( $post->ID, '_lesszns_rhythms', true);	
  ?>
 	  	<?php _e('Rhythms','lesszns');?>: <input type="text" id="lesszns_rhythms" name="lesszns_rhythms" value="<?php echo esc_attr( $rhythms )?>" placeholder="Add rhythms ID" size="60" /><br/>
  <?php	
}

function lesszns_save_rhythms_postdata( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_rhythms_meta_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_rhythms_meta_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_rhythms_meta_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'lesson' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  
  $rhythms = $_POST['lesszns_rhythms'];
      
  update_post_meta( $post_id, '_lesszns_rhythms', $rhythms );
}
add_action( 'save_post', 'lesszns_save_rhythms_postdata' );



/* ADD META BOXES ON SONG POST TYPE FOR ALL SONG PARTS AND FORMATS */
function lesszns_add_song_info_box() {
	add_meta_box( 'lesszns_song_info', __( 'Song Parts ', 'lesszns' ), 'lesszns_song_info_meta_box', 'song', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'lesszns_add_song_info_box' );

function lesszns_song_info_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_song_info_meta_box', 'lesszns_song_info_meta_box_nonce' );

  $lesszns_song_resources = get_post_meta( $post->ID, '_lesszns_song_resources', true);
  $buy_tag = get_post_meta( $post->ID, '_buy_tag', true);
  $buy_price = get_post_meta( $post->ID, '_buy_price', true);

  ?>
  		<div style="padding:16px; margin:4px; background:#E8E8E8; color:#333; border-radius:4px;">
  			<h3><?php _e('Let\'s rock this song!','lesszns');?></h3>
  			<p><?php _e('We\'re going to build a great song together.  Let\'s start by inserting your Video and Audio links and your images, PDF documents and everything related to your song.','lesszns');?></p>
  			<p><?php _e('We will sell this song to the visitors so also include here your BUY link and info about the song','lesszns');?></p>
  			<p><?php _e('If you have any question, you can contact us at lessznsdigital@gmail.com','lesszns');?></p>
  			<p><?php _e('Have fun!','lesszns');?></p>
 	  	</div>
 	  	<hr/>
 	  	<h2><?php _e('About this Song','lesszns'); ?></h2>
 	  	<?php _e('Buy Price','lesszns');?>: <input type="text" id="buy_price" name="buy_price" value="<?php echo esc_attr( $buy_price )?>" placeholder="Add price (number only)" size="60" /><br/>
 	  	<?php _e('Buy button tag','lesszns');?>: <input type="text" id="buy_tag" name="buy_tag" value="<?php echo esc_attr( $buy_tag )?>" placeholder="Add shorttag to buy the song" size="60" /><br/>
 	  	<hr/>
 	  	<h2><?php _e('Full Song','lesszns'); ?></h2>
 	  	<?php _e('Song Video','lesszns');?>: <input type="text" id="lesszns_song_resources[full_video]" name="lesszns_song_resources[full_video]" value="<?php echo esc_attr( $lesszns_song_resources['full_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Song Chords Code','lesszns');?>: <input type="text" id="lesszns_song_resources[chords_pdf]" name="lesszns_song_resources[chords_pdf]" value="<?php echo esc_attr( $lesszns_song_resources['chords_pdf'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Song Tabs','lesszns');?>: <input type="text" id="lesszns_song_resources[tabs_pdf]" name="lesszns_song_resources[tabs_pdf]" value="<?php echo esc_attr( $lesszns_song_resources['tabs_pdf'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<p><?php _e('Play the full song with no explanation and post the Youtube link here. This will be shown to the public.','lesszns'); ?></p>
  		<hr/>
 	  	<h2><?php _e('Intro Part','lesszns'); ?></h2>
  		<?php _e('Video URL','lesszns');?>: <input type="text" id="lesszns_song_resources[intro_video]" name="lesszns_song_resources[intro_video]" value="<?php echo esc_attr( $lesszns_song_resources['intro_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Normal Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[intro_audio_normal]" name="lesszns_song_resources[intro_audio_normal]" value="<?php echo esc_attr( $lesszns_song_resources['intro_audio_normal'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Slow Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[intro_audio_slow]" name="lesszns_song_resources[intro_audio_slow]" value="<?php echo esc_attr( $lesszns_song_resources['intro_audio_slow'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Loop','lesszns');?>: <input type="text" id="lesszns_song_resources[intro_audio_loop]" name="lesszns_song_resources[intro_audio_loop]" value="<?php echo esc_attr( $lesszns_song_resources['intro_audio_loop'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio No Cuatro','lesszns');?>: <input type="text" id="lesszns_song_resources[intro_audio_noinstrument]" name="lesszns_song_resources[intro_audio_noinstrument]" value="<?php echo esc_attr( $lesszns_song_resources['intro_audio_noinstrument'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Intro Tabs','lesszns');?>: <input type="text" id="lesszns_song_resources['intro_tabs_pdf]" name="lesszns_song_resources[intro_tabs_pdf]" value="<?php echo esc_attr( $lesszns_song_resources['intro_tabs_pdf'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php wp_editor( $lesszns_song_resources['intro_explanation'],"intro_explanation", array( 'textarea_name' => 'lesszns_song_resources[intro_explanation]' ) ); ?>
  		<p><?php _e('Explain how to do the Song Intro with full detail.  Also attach four audio files.  1: Intro at normal speed. 2: Intro at slow speed. 3: Intro loop. 4: Intro without cuatro','lesszns'); ?></p>
  		<hr/>
 	  	<h2><?php _e('Verse Part','lesszns'); ?></h2>
  		<?php _e('Verse Video','lesszns');?>: <input type="text" id="lesszns_song_resources[verse_video]" name="lesszns_song_resources[verse_video]" value="<?php echo esc_attr( $lesszns_song_resources['verse_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Normal Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[verse_audio_normal]" name="lesszns_song_resources[verse_audio_normal]" value="<?php echo esc_attr( $lesszns_song_resources['verse_audio_normal'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Slow Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[verse_audio_slow]" name="lesszns_song_resources[verse_audio_slow]" value="<?php echo esc_attr( $lesszns_song_resources['verse_audio_slow'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Loop','lesszns');?>: <input type="text" id="lesszns_song_resources[verse_audio_loop]" name="lesszns_song_resources[verse_audio_loop]" value="<?php echo esc_attr( $lesszns_song_resources['verse_audio_loop'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio No Cuatro','lesszns');?>: <input type="text" id="lesszns_song_resources[verse_audio_noinstrument]" name="lesszns_song_resources[verse_audio_noinstrument]" value="<?php echo esc_attr( $lesszns_song_resources['noinstrument'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php wp_editor( $lesszns_song_resources['verse_explanation'],"verse_explanation", array( 'textarea_name' => 'lesszns_song_resources[verse_explanation]' ) ); ?>
  		<p><?php _e('Explain how to do the Song Verse with full detail.  Also attach four audio files.  1: Verse at normal speed. 2: Verse at slow speed. 3: Intro loop. 4: Verse without cuatro','lesszns'); ?></p>
  		<hr/>
 	  	<h2><?php _e('Chorus Part','lesszns'); ?></h2> 
  		<?php _e('Chorus Video','lesszns');?>: <input type="text" id="lesszns_song_resources[chorus_video]" name="lesszns_song_resources[chorus_video]" value="<?php echo esc_attr( $lesszns_song_resources['chorus_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Normal Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[chorus_audio_normal]" name="lesszns_song_resources[chorus_audio_normal]" value="<?php echo esc_attr( $lesszns_song_resources['chorus_audio_normal'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Slow Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[chorus_audio_slow]" name="lesszns_song_resources[chorus_audio_slow]" value="<?php echo esc_attr( $lesszns_song_resources['chorus_audio_slow'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Loop','lesszns');?>: <input type="text" id="lesszns_song_resources[chorus_audio_loop]" name="lesszns_song_resources[chorus_audio_loop]" value="<?php echo esc_attr( $lesszns_song_resources['chorus_audio_loop'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio No Cuatro','lesszns');?>: <input type="text" id="lesszns_song_resources[chorus_audio_noinstrument]" name="lesszns_song_resources[chorus_audio_noinstrument]" value="<?php echo esc_attr( $lesszns_song_resources['chorus_audio_noinstrument'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php wp_editor( $lesszns_song_resources['chorus_explanation'],"chorus_explanation", array( 'textarea_name' => 'lesszns_song_resources[chorus_explanation]' ) ); ?>
  		<p><?php _e('Explain how to do the Song Chorus with full detail.  Also attach four audio files.  1: Verse at normal speed. 2: Verse at slow speed. 3: Intro loop. 4: Verse without cuatro','lesszns'); ?></p>
  		<hr/>
 	  	<h2><?php _e('Solo Part','lesszns'); ?></h2>
  		<?php _e('Final Video','lesszns');?>: <input type="text" id="lesszns_song_resources[solo_video]" name="lesszns_song_resources[solo_video]" value="<?php echo esc_attr( $lesszns_song_resources['solo_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Normal Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[solo_audio_normal]" name="lesszns_song_resources[solo_audio_normal]" value="<?php echo esc_attr( $lesszns_song_resources['solo_audio_normal'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Slow Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[solo_audio_slow]" name="lesszns_song_resources[solo_audio_slow]" value="<?php echo esc_attr( $lesszns_song_resources['solo_audio_slow'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Loop','lesszns');?>: <input type="text" id="lesszns_song_resources[solo_audio_loop]" name="lesszns_song_resources[solo_audio_loop]" value="<?php echo esc_attr( $lesszns_song_resources['solo_audio_loop'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio No Cuatro','lesszns');?>: <input type="text" id="lesszns_song_resources[solo_audio_noinstrument]" name="lesszns_song_resources[solo_audio_noinstrument]" value="<?php echo esc_attr( $lesszns_song_resources['solo_audio_noinstrument'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php wp_editor( $lesszns_song_resources['solo_explanation'],"solo_explanation", array( 'textarea_name' => 'lesszns_song_resources[solo_explanation]' ) ); ?>
  		<p><?php _e('Explain how to do the Song Solo with full detail.  Also attach four audio files.  1: Verse at normal speed. 2: Verse at slow speed. 3: Intro loop. 4: Verse without cuatro','lesszns'); ?></p>
  		<hr/>
 	  	<h2><?php _e('Final Part','lesszns'); ?></h2>
  		<?php _e('Final Video','lesszns');?>: <input type="text" id="lesszns_song_resources[final_video]" name="lesszns_song_resources[final_video]" value="<?php echo esc_attr( $lesszns_song_resources['final_video'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Normal Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[final_audio_normal]" name="lesszns_song_resources[final_audio_normal]" value="<?php echo esc_attr( $lesszns_song_resources['final_audio_normal'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Slow Speed','lesszns');?>: <input type="text" id="lesszns_song_resources[final_audio_slow]" name="lesszns_song_resources[final_audio_slow]" value="<?php echo esc_attr( $lesszns_song_resources['final_audio_slow'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio Loop','lesszns');?>: <input type="text" id="lesszns_song_resources[final_audio_loop]" name="lesszns_song_resources[final_audio_loop]" value="<?php echo esc_attr( $lesszns_song_resources['final_audio_loop'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php _e('Audio No Cuatro','lesszns');?>: <input type="text" id="lesszns_song_resources[final_audio_noinstrument]" name="lesszns_song_resources[final_audio_noinstrument]" value="<?php echo esc_attr( $lesszns_song_resources['final_audio_noinstrument'] )?>" placeholder="Add full URL" size="60" /><br/>
  		<?php wp_editor( $lesszns_song_resources['final_explanation'],"final_explanation", array( 'textarea_name' => 'lesszns_song_resources[final_explanation]' ) ); ?>
  		<p><?php _e('Explain how to do the Song Final with full detail.  Also attach four audio files.  1: Verse at normal speed. 2: Verse at slow speed. 3: Intro loop. 4: Verse without cuatro','lesszns'); ?></p>
  		
  <?php	
}

function lesszns_save_song_info_postdata( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_song_info_meta_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_song_info_meta_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_song_info_meta_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'song' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.


    $song_resources = $_POST['lesszns_song_resources'];
	update_post_meta( $post_id, '_lesszns_song_resources', $song_resources );
	
	$buy_tag = $_POST['buy_tag'];
	$buy_price = $_POST['buy_price'];
	update_post_meta( $post_id, '_buy_tag', $buy_tag );
	update_post_meta( $post_id, '_buy_price', $buy_price );	
}
add_action( 'save_post', 'lesszns_save_song_info_postdata' );




/* SET DEFAULT META IMAGE */
function fix_jp_og_bugs ($og_tags)
{
    $og_tags['twitter:site'] = '@lesszns';

    
    $og_tags['og:image'] = plugin_dir_url( __FILE__ ).'img/lesszns_logo.png';

    return $og_tags;
}

add_filter ('jetpack_open_graph_tags', 'fix_jp_og_bugs', 11);

function jeherve_custom_image( $media, $post_id, $args ) {
    if ( $media ) {
        return $media;
    } else {
        $permalink = get_permalink( $post_id );
        $url = apply_filters( 'jetpack_photon_url', plugin_dir_url( __FILE__ ).'img/lesszns_logo.png' );
     
        return array( array(
            'type'  => 'image',
            'from'  => 'custom_fallback',
            'src'   => esc_url( $url ),
            'href'  => $permalink,
        ) );
    }
}
add_filter( 'jetpack_images_get_images', 'jeherve_custom_image', 10, 3 );


/* CREATE META BOXES TO ADD ON CHORDS AND RHYTHMS POST TYPES */

function lesszns_chord_info_box() {
	add_meta_box( 'lesszns_chord_info', __( 'Chord Info', 'lesszns' ), 'lesszns_chord_info_meta_box', 'chord', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'lesszns_chord_info_box' );

function lesszns_chord_info_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_chord_info_meta_box', 'lesszns_chord_info_meta_box_nonce' );

  $chord_info = get_post_meta( $post->ID, '_lesszns_chord_info', true);	
  ?>
 	  	<?php _e('Chord Code: ','lesszns');?>: <input type="text" id="lesszns_chord_info[]" name="lesszns_chord_info[]" value="<?php echo esc_attr( $chord_info[0] )?>" placeholder="Add chords code" /><br/>
 	  	<?php _e('Chord Notation: ','lesszns');?>: <input type="text" id="lesszns_chord_info[]" name="lesszns_chord_info[]" value="<?php echo esc_attr( $chord_info[1] )?>" placeholder="Add chords notation" /><br/>
 	  	<?php _e('Chord Notes: ','lesszns');?>: <input type="text" id="lesszns_chord_info[]" name="lesszns_chord_info[]" value="<?php echo esc_attr( $chord_info[2] )?>" placeholder="Add chords notes" /><br/>
  <?php	
}

function lesszns_save_chord_info_postdata( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_chord_info_meta_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_chord_info_meta_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_chord_info_meta_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'chord' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  
  $chords = $_POST['lesszns_chord_info'];
//  print_r($chords);
//  die;
      
  update_post_meta( $post_id, '_lesszns_chord_info', $chords );
}
add_action( 'save_post', 'lesszns_save_chord_info_postdata' );



// Rhythms into Lessons:
function lesszns_rhythm_info_box() {
	add_meta_box( 'lesszns_rhythm_info', __( 'Rhythm Info', 'lesszns' ), 'lesszns_rhythm_info_meta_box', 'rhythm', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'lesszns_rhythm_info_box' );

function lesszns_rhythm_info_meta_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'lesszns_rhythm_info_meta_box', 'lesszns_rhythm_info_meta_box_nonce' );

  $rhythm_info = get_post_meta( $post->ID, '_lesszns_rhythm_info', true);	
  ?>
 	  	<?php _e('Rhythm Code: ','lesszns');?>: <input type="text" id="lesszns_rhythm_info[]" name="lesszns_rhythm_info[]" value="<?php echo esc_attr( $rhythm_info[0] )?>" placeholder="Add rhythms code" /><br/>
 	  	<?php _e('Rhythm Notation: ','lesszns');?>: <input type="text" id="lesszns_rhythm_info[]" name="lesszns_rhythm_info[]" value="<?php echo esc_attr( $rhythm_info[1] )?>" placeholder="Add rhythms notation" /><br/>
 	  	<?php _e('Rhythm Notes: ','lesszns');?>: <input type="text" id="lesszns_rhythm_info[]" name="lesszns_rhythm_info[]" value="<?php echo esc_attr( $rhythm_info[2] )?>" placeholder="Add rhythms notes" /><br/>
  <?php	
}

function lesszns_save_rhythm_info_postdata( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['lesszns_rhythm_info_meta_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['lesszns_rhythm_info_meta_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'lesszns_rhythm_info_meta_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'rhythm' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id; 
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  
  $rhythms = $_POST['lesszns_rhythm_info'];
//  print_r($rhythms);
//  die;
      
  update_post_meta( $post_id, '_lesszns_rhythm_info', $rhythms );
}
add_action( 'save_post', 'lesszns_save_rhythm_info_postdata' );

// Add Editor Style
function plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

//	$mce_css .= plugins_url( 'editor.css', __FILE__ );
	$mce_css .= get_stylesheet_directory_uri().'/styles/lesszns/css/layout.css';

	return $mce_css;
}

add_filter( 'mce_css', 'plugin_mce_css' );


/* -------------------------------------------------- */
/* SHOW FEATURED IMAGE ON POST AND PAGES COLUMN 
 *
/* -------------------------------------------------- */
// GET FEATURED IMAGE  
function wp_lesszns_admin_get_featured_image($post_ID) {  
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);  
    if ($post_thumbnail_id) {  
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');  
        return $post_thumbnail_img[0];  
    }else{ }
}  

// ADD NEW COLUMN
function wp_lesszns_admin_columns_head($defaults) {
	$defaults['featured_image'] = 'Featured Image';
	return $defaults;
}

// SHOW THE FEATURED IMAGE
function wp_lesszns_admin_columns_content($column_name, $post_ID) {
	if ($column_name == 'featured_image') {
		$post_featured_image = wp_lesszns_admin_get_featured_image($post_ID); 
		if ($post_featured_image) {
			echo '<img src="' . $post_featured_image . '" style="width: 100px; height:auto;"/>';
		}
	}
}

// Show featured images on posts
add_filter('manage_posts_columns', 'wp_lesszns_admin_columns_head');  
add_action('manage_posts_custom_column', 'wp_lesszns_admin_columns_content', 10, 2);  

// Show featured images on pages
add_filter('manage_pages_columns', 'wp_lesszns_admin_columns_head');  
add_action('manage_pages_custom_column', 'wp_lesszns_admin_columns_content', 10, 2);  
/* -------------------------------------------------- */

// Change Author to Profile on URL:
add_action('init', 'lesszns_author_base');
function lesszns_author_base() {
    global $wp_rewrite;
    $author_slug = 'profile'; // change slug name
    $wp_rewrite->author_base = $author_slug;
}


/* HELPERS AND FUNCTIONS */


// Show recent users: 
function lesszns_show_recent_users() {
	global $wpdb;
	
	$querystr = "
		SELECT $wpdb->posts.* 
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		AND $wpdb->postmeta.meta_key = 'tag' 
		AND $wpdb->postmeta.meta_value = 'email' 
		AND $wpdb->posts.post_status = 'publish' 
		AND $wpdb->posts.post_type = 'post'
		AND $wpdb->posts.post_date < NOW()
		ORDER BY $wpdb->posts.post_date DESC
	 ";
	//$usernames = $wpdb->get_results("SELECT $wpdb->users.user_nicename, $wpdb->users.user_url FROM $wpdb->users ORDER BY $wpdb->users.ID DESC LIMIT 5", OBJECT);

	if ($usernames) {
	?>
		<h2>Latest registered users</h2>
		<ul>
			<?php
			foreach ($usernames as $username) {
				echo '<li><a href="'.get_author_posts_url(11).'">'.$username->user_nicename."</a></li>";
			}
			?>
		</ul>
	<?php 
	} 
}


function lesszns_add_tablature(){
  $context = '';
  $tabs = '
  	<div id="tablature">
			<div class="content">
				<div class="string four"> |                       |                          |                          |</div>
				<div class="string four"> |                       |                          |                          |</div>
				<div class="string four"> |                       |                          |                          |</div>
				<div class="string four"> |                       |                          |                          |</div>
			</div>
		</div>
  ';
  $mintabs = str_replace(array("\r", "\n"), '', $tabs);
  
  //append the content
  $context .= '
		<a id="add_tablature" title="Tabs" href="#">
          Tabs</a>
		<script>
		jQuery(document).ready(function(){
			jQuery("#add_tablature").click(function(){
				event.preventDefault();
				data = \' '.$mintabs.' \';
				console.log("Content: ");
				console.log(content);
				content = tinyMCE.activeEditor.getContent();
					// Grab the current content and new data all together:
					tinyMCE.activeEditor.setContent(content+data);
				console.log("New Content: ");
				console.log(content);
			
				tb_remove();
			});
		});
		</script>
  ';
  return $context;
}
add_action('media_buttons_context',  'lesszns_add_tablature');

// Shortcode [levels]basic[/levels]
function lesszns_levels_shortcode( $atts, $content = null ) {
	$list = '<div class="lessons list">';
	
	// Add styles and scripts:
	$list .= '
	<style>
		.lessons.list { margin: 15px; width:100%; }
		.lessons.list .thistype .title { display: inline-block; vertical-align: middle; font-size: 14px; padding: 5px; width: 100px; text-align: right; }
		.lessons.list .thistype .count { display: inline-block; vertical-align: middle; height: 14px; width: 100%; max-width:400px; margin: 4px; background: rgb(235, 229, 229); }
		.lessons.list .thistype .count span { width: 0%; height: 100%; display: block; transition: all 0.7s; -webkit-transition: all 0.7s;}
		.lessons.list .thistype .count span.brownbar { background: rgba(182, 127, 29, 0.87); border-radius: 2px; }
		.lessons.list .thistype .count span.greenbar { background-image: -webkit-linear-gradient(-45deg, transparent 33%, rgba(0, 0, 0, .1) 33%, rgba(0,0, 0, .1) 66%, transparent 66%), -webkit-linear-gradient(top, rgba(255, 255, 255, .25), rgba(0, 0, 0, .25)), -webkit-linear-gradient(left, #64F122, #64F122); background-size: 35px 20px, 100% 100%, 100% 100%; }
		.lessons.list .counterpanel { display:inline-block; vertical-align:top; min-width:100px; margin: 8px -4px; text-align:center; }
		.lessons.list .counterpanel .count { display: inline-block; }
	</style>';
	$list .= "
	<script type='text/javascript' src='".get_stylesheet_directory_uri()."/styles/lesszns/js/progressbar.js'></script>
	<script>
	jQuery(document).ready(function(){
		jQuery('.lessons.list .count').each(function(){
		 value = jQuery(this).attr('data-value')*100; 
		 jQuery(this).children('span').delay(5000).css('width',value+'%');
		 console.log(value);
		})
	})
	</script>
	";
	
	// Query to find approved lessons per user:
	$types = get_terms( array('lesson-type'),  array('orderby' => 'name', 'hide_empty' => 1)); 
	$current_user = wp_get_current_user(); 
	foreach ($types as $type) {
		// Search how many lessons the student has completed for this type:
		$args = array(
			'post_type' => 'lesson',
			'posts_per_page' => -1 ,
			'lesson-type' => $type->slug,
		);
		$lessons = new WP_Query($args);
		$lessonscount = 0; 
		
		// find approved lessons
		if ($lessons->have_posts()) { while ($lessons->have_posts()) { $lessons->the_post(); 
			
			
			//print_r($current_user);
			$approved_lessons_for_this_user = get_user_meta($current_user->ID, 'lessons_approved',true);
			if ( is_array($approved_lessons_for_this_user) ){
				if ( array_key_exists(get_the_ID(), $approved_lessons_for_this_user) ) {
					$lessonscount++;
				}
			}
		}}
		
		$usercount = $lessonscount;
		$total = $type->count; 
		$id = substr(md5(microtime()),rand(0,26),5);
		
		//Change to false to use green bars instead of round circles:
		$counter = true;
		if ($counter == false){
			$list .= '<div class="thistype">';
				$list .= '<div class="title">'.$type->name.'</div>';
				$list .= '<div class="count" data-value="'.$usercount/$total.'">
							<span id="'.$id.'" class="greenbar" ></span>
						  </div>';
			$list .= '</div>';	
		} else {
			$list .= '<div class="counterpanel">';
				$list .= '<div class="count" data-value="'.$usercount/$total.'">
							<span id="'.$id.'" ></span>
						  </div>';
				$list .= '<div class="title">
							<a href="' . get_term_link( $type ) . '" title="' . sprintf(__('%s lessons', 'lesszns'), $type->name) . '">'.$type->name.'</a>
							</div>';
		
			$list .= "<script>
				jQuery(document).ready(function(){
					jQuery('.count span#".$id."').percentageLoader({ width : 70, height : 70, progress : ".$usercount/$total.", value : ''});
				});
			</script>";
			$list .= '</div>';
		}
	}
	$list .= '</div>';
		
	// return results
	return $list;


}
add_shortcode( 'levels', 'lesszns_levels_shortcode' );

// Shortcode [levels]basic[/levels]
function lesszns_user_profile( $atts, $content = null ) {
	$current_user = wp_get_current_user(); 
	$user_info = '';
	
	$approved_lessons_for_this_user = get_user_meta($current_user->ID, 'lessons_approved',true);
	$lessons_responses = get_user_meta($current_user->ID, 'lessons_responses',true);
	$lessons_status = get_user_meta($current_user->ID, 'lessons_status',true);
	$approved_total = count($approved_lessons_for_this_user);
	$approved_intro = !empty($lessons_status['introduction']) ? $lessons_status['introduction'] : '0';
	$approved_basic = !empty($lessons_status['basic']) ? $lessons_status['basic'] : '0';
	$approved_intermediate = !empty($lessons_status['intermediate']) ? $lessons_status['intermediate'] : '0'; 
	$approved_advanced = !empty($lessons_status['advanced']) ? $lessons_status['advanced'] : '0'; 
	
	//I need something like this:
	
	/* 
	'lessons_status' user meta
	array (
		'approved' => (111,222,333,444,555,666,777),
		'answers' => array(
			'111' => array(
				'question 1' => 'answer 1',
				'question 2' => 'answer 2',
				'question 3' => 'answer 3',
			)	
		),
		'total' => array(
				'basic' => 41,
				'intermediate' => 72
				'advanced' => 5
		)		
	);
	
	
	*/
	
	
	if ($current_user) {	
		$user_info .= '
		<div class="user-profile">
			<div class="image">'.get_avatar($current_user->ID).'</div>
			<div class="details">
				<div class="name">Name: '.$current_user->display_name.'</div>
				<div class="username">Login: '.$current_user->user_login.'</div>
		 		<div class="email">E-mail: '.$current_user->user_email.'</div>
		 	</div>
		 	<div class="lessons-status">
		 		<div class="how-many total">
		 			<div class="title">'.__('Completed','lesszns').'</div>
			 		<div class="count">'.$approved_total.'</div>
				</div>
				<div class="how-many intro">
		 			<div class="title">'.__('Intro','lesszns').'</div>
			 		<div class="count">'.$approved_intro.'</div>
				</div>
				<div class="how-many basic">
		 			<div class="title">'.__('Basic','lesszns').'</div>
			 		<div class="count">'.$approved_basic.'</div>
				</div>
				<div class="how-many intermediate">
		 			<div class="title">'.__('Intermediate','lesszns').'</div>
			 		<div class="count">'.$approved_intermediate.'</div>
				</div>
				<div class="how-many advanced">
		 			<div class="title">'.__('Advanced','lesszns').'</div>
			 		<div class="count">'.$approved_advanced.'</div>
				</div>
		 	</div> 
		</div>';
		
		$user_info .= '<div class="edit-profile"><a>Edit my profile</a></div>';
		$user_info .= "
			<script>
				jQuery(document).ready(function(){
					jQuery('.edit-profile').click(function(){
						jQuery('form#ws-plugin--s2member-profile').slideToggle('slow');
					});
				})
			</script>
		";
	}
	return $user_info;



}
add_shortcode( 'user_profile', 'lesszns_user_profile' ); 


/* Add custom post types for Paid Memberships Pro */
function my_page_meta_wrapper()
{
	//duplicate this row for each CPT
	add_meta_box('pmpro_page_meta', __('Require Membership','lesszns'), 'pmpro_page_meta', 'lesson', 'side');	
	add_meta_box('pmpro_page_meta', __('Require Membership','lesszns'), 'pmpro_page_meta', 'song', 'side');	
	add_meta_box('pmpro_page_meta', __('Require Membership','lesszns'), 'pmpro_page_meta', 'ebook', 'side');	
}
function pmpro_cpt_init()
{
	if (is_admin())
	{
		add_action('admin_menu', 'my_page_meta_wrapper');
	}
}
add_action("init", "pmpro_cpt_init", 20);

