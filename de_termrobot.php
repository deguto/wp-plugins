<?php
/*
Plugin Name: Deguto Termrobot new
Plugin URI:
Description: Termrobot
Version: 2.5.2
Author: Christian Winkens
Author URI: http://deguto.com/

----------------------------------------
    Copyright (C)  2011 Christian Winkens

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	http://www.gnu.org/licenses/
----------------------------------------
*/
/* pre stuff*/
//update();

global $typenow, $types, $TrTable, $wpdb;
$de_termrobot_version = '2.5.2';
$TrTable = $wpdb->prefix. "termrobot";
require_once("de_tr_options.php");
$types = get_option('de_tr_cptb');

/*if (is_admin()) :
add_action( 'admin_menu', 'my_remove_meta_boxes' );
function my_remove_meta_boxes() {
if(get_option('de_tr_rCat'))
	remove_meta_box('categorydiv', 'post', 'side');
echo "hello ".get_option('de_tr_rCat');
foreach($types as $key => $value){
	remove_meta_box('custom_taxonomy_seitendiv ','post','side');
}
}
//print_r($types);
endif;*/

//load options page



//Install new table
function TrInstallDB() {
	global $TrTable, $wpdb;	 
      
   $sql = "CREATE TABLE IF NOT EXISTS `".$TrTable."` (
  `pageID` int(11) NOT NULL,
  `termID` int(11) NOT NULL,
  UNIQUE KEY `pageID` (`pageID`,`termID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";	
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql); //for future upgrade
	    
}
//remove new table
function TrDropDB() {
	global $TrTable, $wpdb;	 
      
   $sql = "DROP TABLE `".$TrTable."`";	
   $result = $wpdb->get_results($sql);
	    
}

//Register new taxonomy 
//this is necessary!!!!(only if not already added like in hotelheinz.de :-( --> causes failures)
/*function TrRegisterTax() {
	// create a new taxonomy
	register_taxonomy('seiten',array (
  0 => 'post', 1 => 'rechte_box',  2 => 'mittlere_box'
	),array( 'hierarchical' => true, 'label' => 'Seiten','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Seite') );
}*/

function TrRegisterTax() {
	// create a new taxonomy
	register_taxonomy('seiten',array (
  0 => 'post',
  1 => 'rechte_box',
  2 => 'mittlere_box',
),array( 'hierarchical' => true, 'label' => 'Seiten','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => ''),'singular_label' => 'Seite') );
}


/* When the post is saved, saves our custom data */
if (!function_exists('de_termr_save_postdata'))
	: function de_termr_save_postdata($post_id, $post) {
  //echo "<br> <br> aufgerufen!!!";
	//print_r($post);
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	// Is the user allowed to edit the post or page?
  // fire only for post_type == PAGE!!
	if ('revision' == $post->post_type || $post->post_type != 'page' ||  $post->post_status =='auto-draft' ||  $post->post_status =='draft' ) {
		// don't store custom data twice
		return $post->ID;
	}

	/*if ('post' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post->ID))
			return $post->ID;
	} else {
		if (!current_user_can('edit_post', $post->ID))
			return $post->ID;
	}*/
		
	if (TrIsNew($post->ID)) {	//ask if this term (page) is new
		
    //add new term to taxonomy
		//try to find somehow lost connection
        $termID = get_term_by('slug',$post->post_name, 'seiten',ARRAY_A); //ask for current termID
        if($termID == 0) {
            $term_arr = wp_insert_term($post->post_title, // the term //title will be sanitized
                'seiten', // the taxonomy
                array(
                    'description' => '',
                    'slug' => $post->post_name, //Post name for slug
                    'parent' => getParentTermID($post) //add Parent if exists
                ));
            $termID = term_exists($post->post_title, 'seiten'); //ask for current termID
        }
	//print_r($term_arr);
		//echo "hello-->".$termID;
		addConnection($post->ID, $termID['term_id']); //add connection between post (page) and term to my database
	} else {
		//if term already exists, do a term update (in case something changed)
		$termID = getTermID($post->ID); //ask for termID from my database
		$args = array (
			'name' => $post->post_title, //update post title
			'slug' => $post->post_name, //update post name
      'parent'=> getParentTermID($post) //update parent (if changed)
		);
		wp_update_term($termID, 'seiten', $args); //do the magic
	}
	return $post->ID;
}
endif;

//if (current_user_can( 'manage_options' ))
{
   // add_action( 'wp_head', 'TrRecTerms' );
}
//loops over all pages and ads them to the database
//calling hook which is also executed every time a post is saved
function TrAddAllPages() {
//echo" installing";
	$pages = get_pages(); //get alle pages
	foreach ($pages as $pagg) {
		de_termr_save_postdata($pagg->ID, $pagg);
	}
}

function TrRecTerms()
{
    $pages = get_pages(); //get alle pages
    //print_r($pages);
    $terms = get_terms('seiten');
    foreach($pages as $page)
    {
        foreach($terms as $term)
        {
            if($page->post_name == $term->slug)
            {
                if(getTermID($page->ID) == $term->term_id && getPageID($term->term_id)) {
                    echo "Safe: ".$page->ID." <--> ".$term->term_id."<br>";
                }else{
                    //add to db
                    addConnection($page->ID,$term->term_id);
                    echo "Added connection: PageID: ".$page->ID." TermID: ".$term->term_id."<br>";
                }



            }
        }
    }
    //print_r($terms);
    //die();
}

/* When the post is saved, saves our custom data */
if (!function_exists('de_termr_delete_postdata'))
	: function de_termr_delete_postdata($postID) {		
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	// Is the user allowed to edit the post or page?
	// Add values of $my_data as custom fields
	// Let's cycle through the $my_data array!
  global $post;  
  
	if ($post->post_type != 'page') {
		// don't store custom data twice
		return $post->ID;
	} 
  
  //echo" kill it ";
  //die("miau");
	//delete corresponding term
	//echo "Post ID <<>>".$post->ID."<<>>";
	$termID = getTermID($post->ID);
	//echo "Lösche Term!-->".$termID."<<>>";
 	
  if(!empty($termID)){
		wp_delete_term( $termID, 'seiten');
		deleteTermRobotRow($post->ID,$termID);
	}	
}
endif;

//tries to find out TermID of Parent Post
//if term is parent itself, it returns 0
function getParentTermID($post){
if ($post->post_parent != 0) {
			$parent = get_post($post->post_parent);
			$parent_term = term_exists($parent->post_name, 'seiten'); // array is returned if taxonomy is given, else 0 is returned		
			if ($parent_term==0) {//post is parent itself or parent has no termID till now
				echo "kick it";
				$parent_term_id = 0;
			}else{
				$parent_term_id = $parent_term['term_id']; // get numeric term id
			}
		} else {
			$parent_term_id = 0; //post is parent itself
		}
return $parent_term_id;
}

//Deletes Connection if Post (Page) is removed
//cleans ip database
function deleteTermRobotRow($pageID,$termID){
	global $TrTable, $wpdb;	
	$query = "DELETE FROM $TrTable
							  WHERE
							  pageID = $pageID AND termID = $termID LIMIT 1 ";

	$result = $wpdb->get_results($query);	
}



##############BACKEND POST MAGIC #######################################
#####################################################
function custom_post_columns($defaults) {
	return $defaults;
}

//filter stuff for taxonomies and terms
add_action('restrict_manage_posts', 'restrict_listings_by_business');
function restrict_listings_by_business() {
	global $typenow, $types;
	global $wp_query;
	if (in_array($typenow, $types)) {
		$taxonomy = 'seiten';
		$own_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array (
			'show_option_all' => __("Alle {$own_taxonomy->label}"
		), 'taxonomy' => $taxonomy, 'name' => 'seiten', 'orderby' => 'name', 'selected' => $wp_query->query['term'], 'hierarchical' => true, 'depth' => 3, 'show_count' => true, // Show # listings in parents
		'hide_empty' => true, // Don't show seiten w/o listings
		));
	}
}

add_filter('parse_query', 'convert_id_to_taxonomy_term');
function convert_id_to_taxonomy_term($query) {
	global $pagenow;
        $term = 'seiten';
	$qv = &$query->query_vars;     
	if ($pagenow == 'edit.php' && isset ($qv[$term]) && is_numeric($qv[$term])) {
		$termarr = get_term_by('ID', $qv[$term], $term);              
		$qv[$term] = $termarr->slug;                
	}
}
//add column
add_filter('manage_posts_columns', 'add_column_to_list');
function add_column_to_list($posts_columns) {
	if (!isset ($posts_columns['author'])) {
		$new_posts_columns = $posts_columns;
	} else {
		$new_posts_columns = array ();
		$index = 0;
		/*foreach ($posts_columns as $key => $posts_column) {
			if ($key == 'author') {
				$new_posts_columns['seiten'] = null;
				//$new_posts_columns[$key] = $posts_column;
			} else {
				$new_posts_columns[$key] = $posts_column;
			}
		}*/
                $new_posts_columns = $posts_columns;
	}
	$new_posts_columns['seiten'] = 'Seiten';
	return $new_posts_columns;
}

//fill column with data
add_action('manage_posts_custom_column', 'show_column_data', 10, 2);
function show_column_data($column_id, $post_id) {
	global $typenow, $pagenow, $types;

	if (in_array($typenow, $types)) {
		$taxonomy = 'seiten';
		switch ($column_id) {
			case 'seiten' :
				$seiten = get_the_terms($post_id, $taxonomy);
				if (is_array($seiten)) {
					$count = 0;
					foreach ($seiten as $key => $value) {
						if ($count < 5) {
							$edit_link = admin_url() . $pagenow . "?seiten=" . $value->slug;                                                      
							$seiten2[$key] = '<a href="' . $edit_link . '">' . $value->name . '</a>';
						}
						$count++;
					}
					echo implode(" | ", $seiten2);
				}
				break;
		}
	}
}
##############BACKEND POST MAGIC #######################################
#####################################################

//maybe deprecated the new function is more clever and does more magic
/*
function install() {
	global $TrTable, $wpdb;	
	$pages = get_pages();
	foreach ($pages as $pagg) {
		$termID = term_exists($pagg->post_title, 'seiten');

		$query = "INSERT INTO $TrTable
								          (pageID,termID)
								          VALUES ($pagg->ID," . $termID['term_id'] . ")";
		$wpdb->query($query);

	}
}*/




//adds connection betweend post and term to database (very essential)
function addConnection($pageID, $termID) {
	global $TrTable, $wpdb;
	
	$query = "INSERT INTO $TrTable
				          (pageID,termID)
				          VALUES (" . $pageID . "," . $termID . ")";
	$wpdb->query($query);
}

function getTermID($pageID) {
	global $TrTable, $wpdb;	
	$query = "SELECT * FROM $TrTable
							  WHERE
							  pageID = $pageID ";

	$result = $wpdb->get_results($query);

	return $result[0]->termID;
}
function getPageID($termID) {
	global $TrTable, $wpdb;	
	$query = "SELECT * FROM $TrTable
							  WHERE
							  termID = $termID ";

	$result = $wpdb->get_results($query);

	return $result[0]->pageID;
}

//tries to find out, if this term(page) is new to the TrDB
function TrIsNew($post_id) {
	global $TrTable, $wpdb;	
	$query = "SELECT * FROM $TrTable WHERE pageID = '" . $post_id . "'"; //ask TRDB if this page is new	
	$result = $wpdb->get_results($query);
	if ($wpdb->num_rows > 0) {
		return false;
	} else {
		return true;
	}
}

if(!function_exists("get_slug")){
	function get_slug() {
        global $post;
		$post_data = get_post($post->ID, ARRAY_A);
		$slug = $post_data['post_name'];
		return $slug;
	}
}

//deprecated at the moment
function cleanTerms() {
	global $TrTable, $wpdb;
//DRINGEND FIXEN!!
	$query = "SELECT wptax.term_id
						FROM `".$wpdb->prefix."terms` as wpt,`".$wpdb->prefix."term_taxonomy` as wptax
						WHERE wpt.term_id NOT
						IN (
						SELECT termID
						FROM ".$wpdb->prefix."termrobot
						)
						AND wpt.term_id = wptax.term_id AND wptax.taxonomy = 'seiten'";

	$result = $wpdb->get_results($query);
 // print_r($result);
  foreach($result as $term){
   wp_delete_term( $term->term_id, 'seiten', $args );
  }
}
//cleanTerms();
//
//clean skript
//SELECT * FROM `wp_termrobot` WHERE pageID NOT IN (SELECT ID FROM wp_posts WHERE post_type ='page') LIMIT 0 , 30
//SELECT * FROM `wp_posts` WHERE ID NOT IN (SELECT pageID FROM wp_termrobot) AND post_type='page' LIMIT 0 , 30
//TrAddAllPages();

###########ACTIONS#############
//TrRegisterTax();
add_action('save_post', 'de_termr_save_postdata', 1, 2);
//add_action( 'init', 'TrRegisterTax' ); //register the new seiten Taxonomy //fires maybe too late
add_action('deleted_post', 'de_termr_delete_postdata', 1, 2);

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'TrInstallDB'); 
//register_activation_hook(__FILE__,'TrAddAllPages'); 
register_deactivation_hook( __FILE__, 'TrDropDB' );

function de_getTrContent($numPosts = 30, $postType = 'post', $taxonomy = 'seiten', $children = 0 ){
$TrArr = array(
				'showposts' => $numPosts,
				'post_type' => $postType,				
				'orderby' => 'date,menu_order',
				'order' => 'desc',				
				'tax_query' => array( 
        									array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => get_slug(),
            'include_children' => $children
		        )
					)				
);
return $TrArr;
//query_posts($arr);
}


?>
