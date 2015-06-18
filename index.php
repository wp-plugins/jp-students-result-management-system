<?php

/**
 * Plugin Name: JP Students Result Management System
 * Plugin URI: http://skjoy.info/plugins/jp-student-result-management-system.html
 * Description: Simple But Powerful Students Result Management System.You can add,edit,delete,publish students result form regular wordpress admin panel.Use shortcode [jp_students_result_sc] to post or page for searching students result.
 * Version: 1.0
 * Author: Skjoy
 * Author URI: http://skjoy.info
 * Requires at least: 3.0
 * Tested Up to: 4.2.2
 * Stable Tag: 2.0
 * License: GPL v2
 * Shortname: jsrms or jsrms_
 */

 include_once('cmb/functions.php');
 
/* ====================================
  Define plugin url ==================
====================================== */

 
define('JP_SRMS_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/* Adding Plugin javascript file */
wp_enqueue_script('jp-srms-js', plugin_dir_url(__FILE__).'js/scripts.js', array('jquery'));

/* Adding Plugin custm CSS file */
wp_enqueue_style('jp-srms-css', plugin_dir_url(__FILE__).'css/style.css');

function jsrms_add_jquery() {
        wp_enqueue_script('jquery');
}
add_action('init', 'jsrms_add_jquery');
 
/* --------------------------------------------------------------
-------------- Change custom post type title placeholder --------
--------------------------------------------------------------- */

function jsrms_change_default_title($title) {
	$screen = get_current_screen();
	if('jp_students_result' == $screen->post_type) {
		$title = 'Enter student name';
	}
	return $title;
}

add_filter('enter_title_here','jsrms_change_default_title');

/* -----------------------------------------------------------------
---------------- Register new post type Result ---------------------
------------------------------------------------------------------- */
 
function jsrms_students_result_reg() {
  $labels = array(
    'name'               => _x( 'Students Result', 'post type general name' ),
    'singular_name'      => _x( 'Students Result', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'result' ),
    'add_new_item'       => __( 'Add New Result' ),
    'edit_item'          => __( 'Edit Result' ),
    'new_item'           => __( 'New Result' ),
    'all_items'          => __( 'All Students Result' ),
    'view_item'          => __( 'View Result' ),
    'search_items'       => __( 'Search Results' ),
    'not_found'          => __( 'No result found' ),
    'not_found_in_trash' => __( 'No result found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Students Result'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Add new custom post type students result',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'thumbnail','title', ),
	'taxonomies' => array(  'classes' ),
    'has_archive'   => true,
	'menu_icon' => JP_SRMS_PATH.'/images/students_result.ico'
  );
  register_post_type( 'jp_students_result', $args ); 
}
add_action( 'init', 'jsrms_students_result_reg' );

/* ---------------------------------------------
------------ Add Students Classes --------------
---------------------------------------------- */

add_action( 'init', 'jsrms_students_classes_reg', 0 );

function jsrms_students_classes_reg() {
	// Classes taxonomy
	$labels = array(
		'name'              => _x( 'Classes', 'taxonomy general name' ),
		'singular_name'     => _x( 'Class', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Class' ),
		'all_items'         => __( 'All Class' ),
		'parent_item'       => __( 'Parent Class' ),
		'parent_item_colon' => __( 'Parent Class:' ),
		'edit_item'         => __( 'Edit Class' ),
		'update_item'       => __( 'Update Class' ),
		'add_new_item'      => __( 'Add New Class' ),
		'new_item_name'     => __( 'New Class' ),
		'menu_name'         => __( 'Classes' ),
	);
	
	register_taxonomy( 'classes', 'jp_students_result', array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true	
	) );
	
}

/* ---------------------------------------
------- Add students result year ---------
--------------------------------------- */

add_action( 'init', 'jsrms_students_result_year_reg', 0 );

function jsrms_students_result_year_reg() {
	// Years taxonomy
	$labels = array(
		'name'              => _x( 'Years', 'taxonomy general name' ),
		'singular_name'     => _x( 'year', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Year' ),
		'all_items'         => __( 'All Year' ),
		'parent_item'       => __( 'Parent Year' ),
		'parent_item_colon' => __( 'Parent Year:' ),
		'edit_item'         => __( 'Edit Year' ),
		'update_item'       => __( 'Update Year' ),
		'add_new_item'      => __( 'Add New Year' ),
		'new_item_name'     => __( 'New Year' ),
		'menu_name'         => __( 'Years' ),
	);
	
	register_taxonomy( 'years', 'jp_students_result', array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true	
	) );
	
}

/* ----------------------------------------------------
------- Change student result upadate message ---------
----------------------------------------------------- */

function jsrms_students_result_update_message( $messages ) {
  global $post, $post_ID;
  $messages['jp_students_result'] = array(
    0 => '', 
    1 => sprintf( __('Result updated. <a href="%s">View result</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Result updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Result restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Result published. <a href="%s">View result</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Result saved.'),
    8 => sprintf( __('Result submitted. <a target="_blank" href="%s">Preview result</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Result scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview result</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Result draft updated. <a target="_blank" href="%s">Preview result</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
}
add_filter( 'post_updated_messages', 'jsrms_students_result_update_message' );

/* ---------------------------------------------
------------ Result search & view ------------
--------------------------------------------- */

function jsrms_result_search_and_view() { ?>

	<div class="result-search-form">
		<form action="" id="result-form" method="post">
			
			<div class="form-row">
				<label for="exam-reg">Registration No:</label>
				<input type="text" id="exam-reg" name="exam_reg" />
			</div>
			
			<div class="form-row">
				<input type="submit" value="Result" class="result-submit-btn" name="exam_result" /> <img class="loader" src="<?php echo plugin_dir_url(__FILE__).'images/spinner.gif' ?>" alt="" />
			</div>
			
		</form>
   </div>
   
   <div class="result-container">
		<div class="result">
			
		</div>
   </div>




<?php }

add_shortcode('jp_students_result_sc','jsrms_result_search_and_view');

/*--------------------------------------------------*/
/* Result using ajax------------------------------- */
/*-------------------------------------------------*/

function jsrms_result_using_ajax() {
		
		$exam_reg = $_POST['examroll'];
		
		if(!empty($exam_reg))
			query_posts( array( 
				'post_type' => 'jp_students_result',
				'meta_query' => array(
				   array(
					   'key' => '_jp_student_reg',
					   'value' => $exam_reg,
				   )),
				'posts_per_page' => 1
				) 
			);
		 ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="student-result">
				
				<table cellpadding="5" class="student-info">
					<tbody>
						<tr>
							<td>Name</td>
							<td><?php the_title(); ?></td>
						</tr>
						
						<?php 
							$student_father_name = get_post_meta( get_the_ID(),'_jp_student_father_name',true );
							if($student_father_name):
						?>
						<tr>
							<td>Father Name</td>
							<td><?php echo $student_father_name; ?></td>
						</tr>
						<?php endif; ?>
						
						
						<?php 
							$student_mother_name = get_post_meta( get_the_ID(),'_jp_student_mother_name',true );
							if($student_mother_name):
						?>
						<tr>
							<td>Mother Name</td>
							<td><?php echo $student_mother_name; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_type = get_post_meta( get_the_ID(),'_jp_student_type',true );
							if($student_type):
						?>
						<tr>
							<td>Type</td>
							<td><?php echo $student_type; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$date_of_birth = get_post_meta( get_the_ID(),'_jp_student_birth_date',true );
							if($date_of_birth):
						?>
						<tr>
							<td>Date Of Birth</td>
							<td><?php echo $date_of_birth; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_sex = get_post_meta( get_the_ID(),'_jp_student_sex',true );
							if($student_sex):
						?>
						<tr>
							<td>Sex</td>
							<td><?php echo $student_sex; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_religion = get_post_meta( get_the_ID(),'_jp_student_religion',true );
							if($student_religion):
						?>
						<tr>
							<td>Religion</td>
							<td><?php echo $student_religion; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_roll = get_post_meta( get_the_ID(),'_jp_student_roll',true );
							if($student_roll):
						?>
						<tr>
							<td>Roll No</td>
							<td><?php echo $student_roll; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_reg = get_post_meta( get_the_ID(),'_jp_student_reg',true );
							if($student_reg):
						?>
						<tr>
							<td>Registration No</td>
							<td><?php echo $student_reg; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							if(get_the_term_list( $post->ID, 'years' ) != null):
						?>
						<tr>
							<td>Year</td>
							<td><?php $year = get_the_term_list( $post->ID, 'years', '', ', ', '' ); $year = strip_tags($year); echo $year; ?></td>
						</tr>
						<?php endif; ?>
						
						<?php 
							$student_gpa = get_post_meta( get_the_ID(),'_jp_total_gpa',true );
							if($student_gpa):
						?>
						<tr>
							<td>GPA</td>
							<td><?php echo $student_gpa; ?></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				
			</div>
			
		<?php endwhile; else: ?>
		
			<div class="student-result">
				<div class="result-error">
					<span>Sorry,something went wrong.Result not found.</span>
				</div>
			</div>
		<?php endif;
	
	
	
	die();
}

add_action('wp_ajax_jsrms_student_result_view','jsrms_result_using_ajax');
add_action('wp_ajax_nopriv_jsrms_student_result_view','jsrms_result_using_ajax');


?>