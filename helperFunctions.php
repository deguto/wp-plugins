<?php

/*
 * Helper Functions
 */

/* * ############################################################################ */
/* * ############################################################################ */
/* new version by cbw */


function de_addImageSizes()
{
    add_image_size( "de_single_thumb", 200, 138, true );
    add_image_size( "thumbnail", 75, 75, true );
    add_image_size( "de_single_max", 900, 900, false );
}

function writeRepeaterImages($width)
{
    $img_var = 'pictures';
    $img_name = 'picture';
    if( have_rows($img_var) ): ?>


        <?php while( have_rows($img_var) ): the_row();
            echo" mua";
            // vars
            $img_id = get_sub_field($img_name);
            $img_title = get_sub_field('picture_title');
            $img_source = get_sub_field('picture_source');
            $img_link = get_sub_field('picture_link');

            $constructUrl1 = wp_get_attachment_image_src($img_id,'de_single_max')[0];
            $constructUrl2 = wp_get_attachment_image_src($img_id,'de_single_thumb')[0];

            ?>

            <?php if( $img_id ): ?>
                <div class="detail_image" style="float: left;">
                    <a id="thumb1" rel="lightbox" onclick="return hs.expand(this,config1)" href="<?=$constructUrl1 ?>" title="<?php echo $img_title; ?>"><img alt="<?php echo $img_title;?>" src="<?=$constructUrl2?>" /></a>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>

    <?php endif;
}

function get_repeater_images()
{
    $arr_postmeta = get_post_custom();

    if ($arr_postmeta['pictures'][0] != 0) {
        for ($i = 0; $i < $arr_postmeta['pictures'][0]; $i++) {
            $img = get_post($arr_postmeta['pictures_' . $i . '_picture'][0]);
            $c_images[] = $img;
        }
    }

    return $c_images;
}



function page_nums($numposts){
    global $paged, $wp_query,$max_page;

    if ( !$max_page ) { $max_page = $wp_query->max_num_pages; }
    if ( !$numposts ) { $numposts = $wp_query->max_rows; }

    if ( !$paged ) { $paged = 1; }

    echo (''.$numposts.' Eintraege auf ');

    if(strlen($_SERVER['QUERY_STRING']) > 0) {
        $queryString = "?".$_SERVER['QUERY_STRING'];
    }

    //$base = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
    $base = $_SERVER['SCRIPT_URI'];

    $i=1;
    while ($i<=$max_page) {
        if ($i==$paged){
            echo (' <b>'.$i.'</b>');
        }else{
            if ($i==1){
                echo (' <a href="../../">'.$i.'</a>');
            }else{
                if ($paged==1){
                    echo (' <a href="'.$base.'./page/'.$i.'/'.$queryString.'">'.$i.'</a>');
                }else{
                    echo (' <a href="../'.$i.'/'.$queryString.'">'.$i.'</a>');
                }
            }
        }
        $i++;
    }
    echo (' Seite(n)');
}

function de_post_thumbnail(&$bildurl,$postId ="",$width=75,$height=75) {
    global $post;

    $valid = false;
    $blogurl = get_bloginfo("url");
    if (has_post_thumbnail($postId)) {

        $post_thumbnail_id = get_post_thumbnail_id($postId);
        //echo $post_thumbnail_id;
        $bildurl = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail', true);
        $bildurl = $bildurl[0];
        //echo $bildurl;
        if (@fopen($bildurl, "r"))
            $valid = true;
    }

    if (!$valid) {
        $bildurl = getImageFromPost();
        if (@fopen($bildurl, "r"))
            $valid = true;
        $bildurl = $blogurl."/wp-content/plugins/_libs/phpthumb/phpThumb.php?w=$width&h=$height&zc=1&src=" . $bildurl;
    }
    return $valid;
}

//fallback function
function getImageFromPost() {
    $c_images = getImagesFromDb(get_the_ID(), 1);
    //print_r($c_images);
    if (count($c_images) > 0) {
        //$img_url = str_replace(get_bloginfo('url'), "", $c_images[0]);
        $img_url = $c_images[0];
        return $img_url;
    } else {
            ob_start();
            the_content();
            $content = ob_get_clean();
            //echo $content;

            $regex = "/<(img|image).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
            preg_match_all($regex, $content, $finds);

            foreach($finds[0] as $key => $value){
                $content = str_replace($value, "", $content);
            }

            if(count($finds[2]) > 0){
                $img_url = $finds[2][0];
                return $img_url;
            }

        }
        return false;
}

/* * ############################################################################ */
/* * ############################################################################ */
/* new by cbw, think this is even mor effective than parsing the_content */

function getImagesFromDb($id, $urls = 0) {    
    global $wpdb, $table_prefix;

    //$attachments = get_children(array('post_parent' => $id, 'post_type' => 'attachment', 'orderby' => 'menu_order ASC, ID', 'order' => 'DESC'));
    global $wp_query;
    //print_r($wp_query);
    /* alternativ */// $wpdb->get_results('SELECT SQL_CALC_FOUND_ROWS * FROM '.$table_prefix.'posts WHERE 1=1 AND post_type = "attachment" '.$where_customer.' ORDER BY post_date DESC LIMIT 0, 100');
      $attachments = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_parent =".$id);

    //print_r($attachments);
    if ($attachments) {
        if (!$urls) {
            return $attachments;
        } else {
            //print_r($attachments);
            foreach ($attachments as $attachment) {
                $bildurls[] = $attachment->guid;
            }
            return $bildurls;
        }
    }
}

//-----------------------------------------------------------
/**
 * Filter Alt Tags from Images
 */
function filterAltTags(&$content) {
    $finds = array();
    $regex2 = "/alt=\"([A-Z0-9._%+-]*?)\"/i";
    preg_match_all($regex2, $content, $finds);
    return $finds;
}

//-----------------------------------------------------------
/**
 * Filter Images from html content based on regex
 */
function filterImages(&$content) {
    $finds = array();
    $regex = '/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*><(img|image).*?src\s*=\s*["\'](.*?)["\'].*?><\/a>(\r\n)*/si';
    $regex = "/<(img|script).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
    preg_match_all($regex, $content, $finds);

    return $finds;
}

//-----------------------------------------------------------
/**
 * Filter Galleries from html content based on regex
 */
function filterGalleries(&$content) {
    $finds = array();
    $regex = "@\[nggallery id=(.*?)\]@i";
    preg_match_all($regex, $content, $finds, PREG_SET_ORDER);

    return $finds;
}

//-----------------------------------------------------------
/**
 * Filter Flickr stuff from html content based on regex
 */
function filterFlickr(&$content) {
    $finds = array();
    $regex = "@\[flickrset id=(.*?)\]@i";
    preg_match_all($regex, $content, $finds, PREG_SET_ORDER);
    return $finds;
}

/* * ############################################################################ */
/* * ############################################################################ */

function filter_special_chars($content) {
    $content = str_replace(array('&bdquo;', '&ldquo;', '&amp;'), array('', '', '&'), htmlspecialchars($content));
    /* '&szlig;','&uuml;','&auml;','&ouml;','&Uuml;','&Auml;','&Ouml;' */
    return $content;
}

/* * ############################################################################ */
/* * ############################################################################ */

/* * ############################################################################ */
/* * ############################################################################ */

function wget() {
    if ($_SERVER['HTTP_USER_AGENT'] == "Wget/1.11.4") {
        return true;
    } else {
        return false;
    }
}

/* * ############################################################################ */
/* * ############################################################################ */

function printWgetUrl($type = "url") {
    if (wget()) {
        $url = get_bloginfo($type);
        $url = str_replace('live.', '', $url);
    } else {
        $url = get_bloginfo($type);
    }
    echo $url;
}

/* * ############################################################################ */
/* * ############################################################################ */

function debug_num_queries($title = "", $status = "") {
    global $start, $start_time;
    $num_queries = get_num_queries();
    $time = timer_stop(0, 3);
    if (strtoupper($status) == "START") {
        $start = $num_queries;
        $start_time = $time;
        echo "<!-- Q: " . $num_queries . " START | " . $title . " -->\n";
    } elseif (strtoupper($status) == "STOP") {
        $stop = $num_queries - $start;
        $stop_time = $time - $start_time;
        echo "<!-- Q: " . $num_queries . " STOP: " . $stop . " in " . $stop_time . " | " . $title . " -->\n";
    } else {
        echo "<!-- Q: " . $num_queries . " | " . $title . " -->\n";
    }
}
// this can live in /themes/mytheme/functions.php, or maybe as a dev plugin?
function get_template_name () {
	foreach ( debug_backtrace() as $called_file ) {
		foreach ( $called_file as $index ) {
			if ( !is_array($index[0]) AND strstr($index[0],'/themes/') AND !strstr($index[0],'footer.php') ) {
				$template_file = $index[0] ;
			}
		}
	}
	$template_contents = file_get_contents($template_file) ;
	preg_match_all("(Template Name:(.*)\n)siU",$template_contents,$template_name);
	$template_name = trim($template_name[1][0]);
	if ( !$template_name ) { $template_name = '(default)' ; }
	$template_file = array_pop(explode('/themes/', basename($template_file)));
	return $template_file . ' > '. $template_name ;
}

//customize backend
if (is_admin()) :
function my_remove_meta_boxes() {
        remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); //remove tags from backend
        remove_meta_box('categorydiv', 'post', 'normal'); //remove categories from backend
}
add_action( 'admin_menu', 'my_remove_meta_boxes' );
endif;

?>
