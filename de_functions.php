<?php
/*
Plugin Name: deguto-functions
Plugin Script: deguto-functions.php
Plugin URI: http://deguto.de (where should people go for this plugin?)
Description: (...)
Version: 0.2
Author: Christian Winkens, Stefan Andernach
Author URI: http://... (your blog/site URL)
Template by: http://web.forret.com/tools/wp-plugin.asp

=== RELEASE NOTES ===
2013-08-28 - v1.0 - first version
*/

// uncomment next line if you need functions in external PHP script;
if (1)
 include_once(dirname(__FILE__).'/helperFunctions.php');
 if (1)
 include_once(dirname(__FILE__).'/KlonerFunctions.php');
 if (1)
 include_once(dirname(__FILE__).'/p2phelper.php');
 if (1)
 include_once(dirname(__FILE__).'/de_GMaps.php');

//needed to add to main loop
/* function custom_conference_in_home_loop( $query ) {
  if ( is_home() && $query->is_main_query() )
  $query->set( 'post_type', array( 'post', 'your_custom_post_type_here') );
  return $query;
  }
  add_filter( 'pre_get_posts', 'custom_conference_in_home_loop' );
 */
function get_meta($key, $echo = true) {
    global $wp_query;

    $arr_postmeta = get_post_custom();
    if (isset($arr_postmeta[$key][0])) {
        if ($echo)
            echo $arr_postmeta[$key][0];
        return $arr_postmeta[$key][0];
    }else {
        if ($echo)
            echo "";
        return false;
    }
}
//################################################################################
function write_small_posts($post){
	setup_postdata($post); ?>
	<div class="recent">
		<div class="list_title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></div>
		
<?
        $bildurl ="";
        $valid = de_post_thumbnail($bildurl,get_the_ID());
    if(!$valid )
        $bildurl = "";

?>
<?php	

		if(strlen($bildurl) > 5): ?>
            <div class="recent_list_image_small">
                <a href="<?php the_permalink() ?>" rel="bookmark"><img title="default" alt="alt" src="<?php echo $bildurl ?>" /></a>
            </div>
		<?php endif; ?>
		<div class="recent_list_content">
			<?php	//Text abschneiden
			if(!isset($textlen)){
				$textlen = 190;
			}

			$text = strip_tags(get_the_content());

			if(strlen($text) < 1){
				$text = strip_tags(get_post_meta(get_the_id(), "text", true));
			}
			if(strlen($text) > $textlen){
				$textlen = strpos($text, " ", $textlen);
			}
			echo $text_kurz = substr($text, 0, $textlen);
			echo "&hellip;";
			$more_link='<a class="more-link-block" href="'.get_permalink().'"> ...weiterlesen</a>';
			//ENDE Text abschneiden
			?>
		</div>
		<?php echo $more_link; ?>
	</div>
	<?php return get_the_id(); ?>
<?php }
//################################################################################
//#################################################################################
/**
 * \brief  get_Desc
 * 		- returns the description for category or tag
 *
 */
function the_Desc() {
    $patt_1 = '~#?(#[0-9a-f]{3}\b|#[0-9a-f]{6}\b)~i';
    if (is_category()) {
        $cat_desc = preg_replace($patt_1, "", category_description());

        if (strlen($cat_desc) > 8) {
            echo $cat_desc;
        } else {
            $cat_option = get_option('headspace_category');
            $description = $cat_option['description'];
            $description = str_replace('%%category%%', wp_title('', 0), $description);
            echo $description;
        }
    } elseif (is_tag()) {
        $tag_desc = preg_replace($patt_1, "", tag_description());

        if (strlen($tag_desc) > 8) {
            echo $tag_desc;
        } else {
            $cat_option = get_option('headspace_tag');
            $description = $cat_option['description'];
            $description = str_replace('%%tag%%', wp_title('', 0), $description);
            echo $description;
        }
    }
}

/* * ############################################################################ */
/* * ############################################################################ */

function getContentUrl() {
    global $ccounter, $settings;

    if ($settings['enable_static_urls']) {
        $ccounter ? null : $ccounter = 1;
        ($ccounter > $settings['max_static_urls']) ? $ccounter = 1 : null;
        $url = get_bloginfo("url");
        $url = str_replace('www.', $settings['static_url'] . $ccounter . '.', $url);
        $ccounter++;
    } else {
        $url = get_bloginfo("url") . '/wp-content';
        //$url = '/wp-content';
    }
    return $url;
}

/* * ############################################################################ */
/* * ############################################################################ */

function getContentWithoutScript($content = "", $len = 200) {
    if ($content == "") {
        ob_start();
        //the_excerpt();
        the_excerpt_rss($len, 2);
        $content = ob_get_clean();
    }

    $regex = "/<[^>]*>/i";
    preg_match_all($regex, $content, $finds);

    foreach ($finds[0] as $key => $value) {
        $content = str_replace($value, "", $content);
    }
    return $content;
}

/* * ############################################################################ */
/* * ############################################################################ */

function get_the_excerpt_deguto($len = 200, $text = "", $more = false, $moreLinkURL = "", $moreLinkName = "weiter lesen", $moreLinkClass = "morelink") {
    //echo "<!-- ### get_the_excerpt_klon # $text ## ".strlen($text)." -->";
    if (strlen($text) < 10) {
        $text = getContentWithoutScript("", $len); // make Text
    }

    if ($moreLinkURL == "")
        $moreLinkURL = get_permalink(); // make moreURL
    $moreLink = '<a class="' . $moreLinkClass . '" href="' . $moreLinkURL . '">' . $moreLinkName . '</a>'; // make moreLink

    $text = str_replace("[...]", "", $text); // clean Text
    if (strlen($text) > $len) {
        $text = substr($text, 0, $len);
        $spacePos = strripos($text, " ");
        $text = substr($text, 0, $spacePos);
    }
    if (strlen($text) > 10) {
        if ($more) {
            $text.="&hellip;" . $moreLink . " ";
        } else {
            $text.="&hellip;";
        }
    }

    return $text; // final excerptText
}

/* * ############################################################################ */
/* * ############################################################################ */

function the_excerpt_deguto($len = 200, $text = "", $more = false) {

        $text = get_the_excerpt_deguto($len, $text, $more);
    if (strlen($text) > 0) {
        echo $text;
    }
}

function writeContentWithoutImages($content = "", $default = "", $postmeta = false, $echo = true) {
    global $galleries, $flickrGalleries; 
    //get the content
    if (!$content) {
        ob_start();
        the_content();
        $content = ob_get_clean();
    }
    //remove Images
    $finds = filterImages($content);
    foreach ($finds[0] as $key => $value) {
        $content = str_replace($value, "", $content);
    }

    //remove Galleries
    $galleries = filterGalleries($content);
    if ($galleries) {
        /* foreach($galleries[0] as $key => $value){
          echo "<h1> $value </h1>";
          $content = str_replace($value, "", $content);
          } */
        $content = str_replace($galleries[0][0], "", $content);
    }
    //remove Flickr stuff
    $flickrGalleries = filterFlickr($content);
    if ($flickrGalleries) {
        foreach ($flickrGalleries[0] as $key => $value) {
            $content = str_replace($value, "", $content);
        }
    }

    /* Am 14.06.2011 erneut auskommentiert da überall riesige Umbrüche zustande kamen. aus einem </p>/r/n->(vermutlich ungewollt durch ein editor)<p> wurde </p><br><p> */
//  $content = nl2br($content);

    if (strlen($content) < 10) {
        $content = $default;
    }

    if ($echo) {
        echo $content;
        if ($postmeta) {
            ?>
            <p class="postmetadata"><?php the_tags('Tags: ' . ' ', ', ', '<br />'); ?> <?php printf('Abgelegt in: %s', get_the_category_list(', ')); ?><?php edit_post_link('<img src="' . get_bloginfo("template_url") . '/images/edit.png" title="Artikel bearbeiten" />', ' ', ''); ?>  <?php comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;'); ?></p><?php
        }
    } else {
        if ($postmeta) {
            return $content . '<p class="postmetadata">' . the_tags('Tags: ' . ' ', ', ', '<br />') . ' ' . printf('Abgelegt in: %s', get_the_category_list(', ')) . edit_post_link('<img src="' . get_bloginfo("template_url") . '/images/edit.png" title="Artikel bearbeiten" />', ' ', '') . comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;') . '</p>';
        } else {
            return $content;
        }
    }
}

/* * ############################################################################ */
/* * ############################################################################ */

function get_content() {
    ob_start();
    the_content();
    $content = ob_get_clean();
    return $content;
}


/* * ############################################################################ */
/* * ############################################################################ */

function writeImagesFromPost($width = 200, $mode = "image", &$content = "", $offset = 0) {
    global $blog_id;    
    //$liburl = "../wp-content/plugins/_libs/phpthumb/phpThumb.php?w=";
//Problem mit custom post types.. die benötigen nämlich das untere
    $liburl = "/wp-content/plugins/_libs/phpthumb/phpThumb.php?w=";
    $liburl =get_bloginfo( 'url').$liburl;
//find images using preg_match
    $finds = filterImages($content);
    
    //find alternate texts

    $altTags =  filterAltTags($content);
    //
    $post_thumb_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );


    if (isset($finds[2])) {
        $c_images = $finds[2];
    }
    //TODO: In Funktion auslagern!!!!

    if (count($c_images) == 0) {
        $arr_postmeta = get_post_custom();
        //print_r($arr_postmeta);
        if ($arr_postmeta['pictures'][0] != 0) {
            for ($i = 0; $i < $arr_postmeta['pictures'][0]; $i++) {
                $img = get_post($arr_postmeta['pictures_' . $i . '_picture'][0]);
                $c_images[] = $img->guid;
            }
        }
    }
    /* CBW: 18.02.2010: hier ist es auskommentiert in dem kram oben nicht, wieso wird das doppelt gemacht??!?! in dieser mehtode und in writeContentWithoutImages
      $regex = "/<(a).*?href\s*=\s*['\"](.*?)['\"].*?>/i";
      preg_match_all($regex, $content, $finds); */

//iterate over images and send them through our pipeline
    for ($o = 0; $o < $offset; $o++) { // unset offsets
        unset($c_images[$o]);
    }
    //new cbw 10.06.2015
    /*if(strlen($post_thumb_url)  > 1)
        array_unshift($c_images, $post_thumb_url);*/


    switch ($mode) {
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        case "float":
            if (count($c_images) > 0) {
                ?>
                <?php
                foreach ($c_images as $key => $bildurl) {
                    if (substr_count($bildurl, 'flickr.com') > 0) {
                        $constructUrl2 = $liburl . $width . "&f=png&src=" . $bildurl;
                        //$constructUrl1 = substr($bildurl,0,-5)."b.jpg";
                        $bildurl = str_replace("_s", "_b", $bildurl); //get the big image
                        $constructUrl1 = $bildurl;
                    } elseif (substr_count($bildurl, 'thumbs/thumbs_') > 0) {
                        $constructUrl2 = $bildurl;
                        $bildurl = str_replace("thumbs/thumbs_", "", $bildurl);
                        $constructUrl1 = $liburl . (4 * $width) . "&f=png&src=" . $bildurl;
                    } else {
                        //$bildurl = str_replace('wp-content/uploads/', 'files/', $bildurl);
                        //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl);					 					 		
                        //$bildurl = str_replace("_b", "", $bildurl);	                        
                        $constructUrl1 = $liburl . "800&f=png&src=" . $bildurl;
                        $constructUrl2 = $liburl . $width . "&f=png&src=" . $bildurl;
                    }
                    ?>

                    <div class="highslide-gallery" style="float: left;">
                        <a id="thumb1" class="highslide" onclick="return hs.expand(this,config1)" href="<?= $constructUrl1 ?>" title="<?php echo $altTags[1][$key]; ?>"><img  src="<?= $constructUrl2 ?>" /></a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="detail_image empty">
                </div>
                <?
            }
            break;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        case "image":
            if (count($c_images) > 0) {
                ?>
                <?php
                foreach ($c_images as $key => $bildurl) {
                    if (substr_count($bildurl, 'flickr.com') > 0) {
                        $constructUrl2 = $liburl . $width . "&f=png&src=" . $bildurl;
                        //$constructUrl1 = substr($bildurl,0,-5)."b.jpg";
                        $constructUrl1 = $bildurl;
                    } elseif (substr_count($bildurl, 'thumbs/thumbs_') > 0) {
                        $constructUrl2 = $bildurl;
                        $bildurl = str_replace("thumbs/thumbs_", "", $bildurl);
                        //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl);
                        $constructUrl1 = $liburl . $width . "&f=png&src=" . $bildurl;
                    } else {
                        //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl);              
                        $constructUrl2 = $liburl . $width . "&f=png&src=" . $bildurl;
                    }
                    ?>
                    <div class="detail_image">
                        <img src="<?= $constructUrl2 ?>" />
                    </div>
                    <?
                }
            }
            break;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        case "listimage":
            if (count($c_images) > 0) {
                ?>
                <?php
                $bildurl = $c_images[0];
                if (substr_count($bildurl, 'flickr.com') > 0) {
                    $constructUrl2 = $liburl . $width . "&sw=200&sh=200&aoe=1&f=png&src=" . $bildurl;
                    //$constructUrl1 = substr($bildurl,0,-5)."b.jpg";
                    $constructUrl1 = $bildurl;
                } elseif (substr_count($bildurl, 'thumbs/thumbs_') > 0) {
                    $constructUrl2 = $bildurl;
                    //$bildurl = str_replace("thumbs/thumbs_","", $bildurl);
                    //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl);
                    $constructUrl1 = $liburl . $width . "&f=png&src=" . $bildurl;
                } else {
                    //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl);
                    $constructUrl2 = $liburl . $width . "&sw=200&sh=200&aoe=1&f=png&src=" . $bildurl;
                }
                ?>
                <img src="<?= $constructUrl2 ?>" />
                <?
            }
            break;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        case "listimage":
            if (count($c_images) > 0) {
                ?>
                <?php
                foreach ($c_images as $key => $bildurl) {
                    if (substr_count($bildurl, 'flickr.com') > 0) {
                        $constructUrl2 = $liburl . $width . "&sw=200&sh=200&aoe=1&f=png&src=" . $bildurl;
                        //$constructUrl1 = substr($bildurl,0,-5)."b.jpg";
                        $constructUrl1 = $bildurl;
                    } elseif (substr_count($bildurl, 'thumbs/thumbs_') > 0) {
                        $constructUrl2 = $bildurl;
                        $bildurl = str_replace("thumbs/thumbs_", "", $bildurl);
                        $bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/" . $blog_id, $bildurl);
                        $constructUrl1 = $liburl . $width . "&f=png&src=" . $bildurl;
                    } else {
                        $bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/" . $blog_id, $bildurl);
                        $constructUrl2 = $liburl . $width . "&sw=200&sh=200&aoe=1&f=png&src=" . $bildurl;
                    }
                    ?>
                    <div class="detail_image" style="float: left;">
                        <img src="<?= $constructUrl2 ?>" />
                    </div>
                    <?
                }
            }
            break;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        default:
            if (count($c_images) > 0) {
                ?>
                <?php
                foreach ($c_images as $key => $bildurl) {
                    //$bildurl = str_replace(get_bloginfo('url'), "/wp-content/blogs.dir/".$blog_id, $bildurl); 
                    ?>

                    <div class="detail_image">
                        <a class="zoom highslide" onclick="return hs.expand(this)" href="<?php bloginfo('url') ?>/wp-content/plugins/_libs/phpthumb/phpThumb.php?w=800&f=png&src=<?= $bildurl ?>" rel="lightbox[gallery]"><img  src="<?php bloginfo('url') ?>/wp-content/plugins/_libs/phpthumb/phpThumb.php?&w=200&f=png&src=<?= $bildurl ?>" /></a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="detail_image" style="display: block; height: 250px; background-image: url(<?php bloginfo('template_directory'); ?>/images/trans.png);">
                </div>
                <?
            }
            break;
    }
}


/* * ############################################################################ */
/* * ############################################################################ */

function the_deguto_thumb($width = 100) {
    $bildurl = getImageFromPost();
    ?>
    <img alt="" src="<?php bloginfo('url') ?>/wp-content/plugins/_libs/thirdparty/phpthumb/phpThumb.php?w=<?= $width; ?>&src=<?= $bildurl; ?>" />
    <?php
}

/* * ############################################################################ */
/* * ############################################################################ */

function write_tag_cloud($tags) {
    // for example print out the tags
    ksort($tags);
    $list = "";
    foreach ($tags as $tag) { // go through tags
        //echo '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a> <b>&middot;</b> ';
        $list.=$tag->term_id . ',';
    }

    return array(
        "smallest" => 8,
        "largest" => 19,
        "unit" => "pt",
        "number" => 30,
        "format" => "flat",
        "separator" => " <b>&middot;</b> ",
        "orderby" => "name",
        "order" => "ASC",
        "include" => $list,
        "link" => "view",
        "taxonomy" => "post_tag",
        "echo" => false);
}

/* * ############################################################################ */
/* * ############################################################################ */

function get_season() {
    $season = date("z");
    $year = date('Y');

    if (($season >= 1 and $season <= 81)
            or ($season >= 305 and $season <= 366))
        $season2 = "Winter";

    if ($season >= 82 and $season <= 150)
        $season2 = "Fr&uuml;hling";
    if ($season >= 151 and $season <= 243)
        $season2 = "Sommer";
    if ($season >= 244 and $season <= 304)
        $season2 = "Herbst";
    echo $season2 . " " . $year;
}

/* * ############################################################################ */
/* * ############################################################################ */

function next_posts_link_custom($linkname) {
    global $paged, $max_page;
    $precpage = $paged - 1;
    $nextpage = $paged + 1;

    echo "<!-- next:$nextpage | max:$max_page -->";

    if ($nextpage <= $max_page) {
        if ($paged == 1) {
            echo ('<a href="./page/');
        } else {
            echo ('<a href="../');
        }
        echo ($nextpage . '/">' . $linkname . '</a>');
    }
}

/* * ############################################################################ */
/* * ############################################################################ */

function previous_posts_link_custom($linkname) {
    global $paged, $max_page;

    $precpage = $paged - 1;
    $nextpage = $paged + 1;

    if ($paged > 1) {
        if ($precpage == 1) {
            echo '<a href="../../">' . $linkname . '</a>';
        } else {
            echo '<a href="../' . $precpage . '/">' . $linkname . '</a>'; // <a href="../../">&laquo;&laquo; zum Anfang</a>
        }
    }
}
?>
