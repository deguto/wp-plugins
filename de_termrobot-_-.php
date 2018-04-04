<?php
/*
Plugin Name: Deguto Termrobot new
Plugin URI:
Description: Termrobot
Version: 2.3
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
//cleanTerms();
$de_termrobot_version = '2.3';
add_action('save_post', 'de_termr_save_postdata', 1, 2);
//add_action('deleted_post', 'de_termr_delete_postdata', 1, 2);


global $types, $deTermiTable, $deTermiTable_prefix;
$types = array (
	"post",
	"rechte_box",
  "mittlere_box"
);
$deTermiTable = $deTermiTable_prefix . "termrobot";
//install();

/* When the post is saved, saves our custom data */
if (!function_exists('de_termr_save_postdata'))
	: function de_termr_save_postdata($post_id, $post) {
  //echo "<br> <br> aufgerufen!!!";
	//print_r($post);
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	// Is the user allowed to edit the post or page?

	if ('post' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post->ID))
			return $post->ID;
	} else {
		if (!current_user_can('edit_post', $post->ID))
			return $post->ID;
	}


	if ('revision' == $post->post_type || $post->post_type != 'page' ||  $post->post_status =='auto-draft' ||  $post->post_status =='draft' ) {
		// don't store custom data twice
		return $post->ID;
	}
	// Add values of $my_data as custom fields
	// Let's cycle through the $my_data array!
	// Add values of $my_data as custom fields
	// Let's cycle through the $my_data array!
	//insert new term
	if (isNew($post->ID, $post)) {				
		$parent_term_id = getParentTermID($post);				

			$term_arr = wp_insert_term($post->post_title, // the term
		'seiten', // the taxonomy
	array (
			'description' => '',
			'slug' => $post->post_name,
			'parent' => $parent_term_id
		));

		$termID = term_exists($post->post_title, 'seiten');
		addConnection($post->ID, $termID['term_id']);
	} else {
		$termID = getTermID($post->ID);
		$args = array (
			'name' => $post->post_title,
			'slug' => $post->post_name,
      'parent'=> getParentTermID($post)
		);
		wp_update_term($termID, 'seiten', $args);
	}
	return $post->ID;
}
endif;

/* When the post is saved, saves our custom data */
if (!function_exists('de_termr_delete_postdata'))
	: function de_termr_delete_postdataa($postID) {
		echo "<br> <br> <br>Loesch mich 111!!!!!";
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	// Is the user allowed to edit the post or page?

	if ('post' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $postID))
			return $postID;
	} else {
		if (!current_user_can('edit_post', $postID))
			return $postID;
	}
	// Add values of $my_data as custom fields
	// Let's cycle through the $my_data array!

	/*if ('revision' == $post->post_type || $post->post_type != 'page' ||  $post->post_status =='auto-draft' ) {
		// don't store custom data twice
		return;
	}*/
	//delete corresponding term
	$termID = getTermID($postID);
	echo "lösch mich!";
 	print_r($termID);
  if(!empty($termID)){
		wp_delete_term( $termID, 'seiten');
		deleteTermRobotRow($postID,$termid);
	}
	
	
}
endif;

function getParentTermID($post){
if ($post->post_parent != 0) {
			$parent = get_post($post->post_parent);
			$parent_term = term_exists($parent->post_name, 'seiten'); // array is returned if taxonomy is given			
			if (!isset ($parent_term)) {
				echo "kick it";
				$parent_term_id = 0;
			}
			$parent_term_id = $parent_term['term_id']; // get numeric term id
		} else {
			$parent_term_id = 0;
		}
return $parent_term_id;

}
function deleteTermRobotRow($pageID,$termID){
	global $deTermiTable, $wpdb;	
	$query = "DELETE FROM $deTermiTable
							  WHERE
							  pageID = $pageID AND termID = $termID LIMIT 1 ";

	$result = $wpdb->get_results($query);	
}
function de_termr_all() {
	$pages = get_pages();
	foreach ($pages as $pagg) {
		de_termr_save_postdata($pagg->ID, $pagg);
	}
}

if ($_REQUEST['crawlall'] == 1) {
	//echo "Adding all Sites";
	de_termr_all();

}
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

function install() {
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$pages = get_pages();
	foreach ($pages as $pagg) {
		$termID = term_exists($pagg->post_title, 'seiten');

		$query = "INSERT INTO $deTermiTable
								          (pageID,termID)
								          VALUES ($pagg->ID," . $termID['term_id'] . ")";

		$wpdb->query($query);

	}
}

function addConnection($pageID, $termID) {
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$query = "INSERT INTO $deTermiTable
				          (pageID,termID)
				          VALUES ($pageID," . $termID . ")";

	$wpdb->query($query);
}

function getTermID($pageID) {
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$query = "SELECT * FROM $deTermiTable
							  WHERE
							  pageID = $pageID ";


	$result = $wpdb->get_results($query);

	return $result[0]->termID;
}
function getPageID($termID) {
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$query = "SELECT * FROM $deTermiTable
							  WHERE
							  termID = $termID ";


	$result = $wpdb->get_results($query);

	return $result[0]->pageID;
}

function isNew($post_id, $post) {
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$query = "SELECT * FROM $deTermiTable WHERE pageID = '" . $post_id . "'";
	//echo $query;
	$result = $wpdb->get_results($query);
	if ($wpdb->num_rows > 0) {
		return false;
	} else {
		return true;
	}
}

function cleanTerms() {
//echo "helloooooo";
	global $deTermiTable, $wpdb;
	//$deTermiTable = $deTermiTable_prefix."termrobot"; // table name
	$query = "SELECT wptax.term_id
FROM `wp_terms` as wpt,`wp_term_taxonomy` as wptax
WHERE wpt.term_id NOT
IN (
SELECT termID
FROM wp_termrobot
)
AND wpt.term_id = wptax.term_id AND wptax.taxonomy = 'seiten'";

	$result = $wpdb->get_results($query);
//print_r($result);
foreach($result as $term){
//print_r($term);
//echo "deleting: ".$term->term_id." <br>";
wp_delete_term( $term->term_id, 'seiten', $args );

}

	
}
?>
