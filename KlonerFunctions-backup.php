<?php
/* * ** HELPER FUNCTIONS * */
/**
 * Construct a slider out of images from post
 */
//------------------equal to de_addressbook------------------------------------------------------------------------
function slider()
{
//get post custom data
    $arr_postmeta = get_post_custom();
    if ($arr_postmeta['pictures'][0] != 0) {
        ?>
        <div id="wrapper">
            <div class="slider-wrapper theme-default">
                <div id="slider" class="nivoSlider">
                    <?
                    for ($i = 0; $i < $arr_postmeta['pictures'][0]; $i++) {
                        $img = get_post($arr_postmeta['pictures_' . $i . '_picture'][0]);
                        ?>
                        <img src="<? echo $img->guid; ?>" data-thumb="<? echo $img->post_title; ?>" alt=""/>
                    <? }
                    ?>
                </div>
            </div>
        </div>
        <?
    }
}

//-----------------------------------------------------------
/**
 * Echo Image if Post is address
 */
function isAddress(&$post)
{
    if ($post->post_type == 'address')
        echo '<img src="' . get_bloginfo("template_url") . '/images/home.png" title="Addresseintrag" /> ';
}

//-----------------------------------------------------------
/**
 * Get Pictures from post_meta
 * Param: needs correct post ID
 */
//-----------------------------------------------------------
function get_pic($id = 0)
{
    global $wp_query;
    $c_images = array();
    if ($id != 0) {
        $arr_postmeta = get_post_custom($id);
        if ($arr_postmeta['pictures'][0] != 0) {
            for ($i = 0; $i < $arr_postmeta['pictures'][0]; $i++) {
                $img = get_post($arr_postmeta['pictures_' . $i . '_picture'][0]);
                $c_images[] = $img->guid;
            }
        }
    }

    return $c_images;
}

/**
 * @param int $id
 * @return returns membership of post
 */
//-----------------------------------------------------------
function get_membership($id = 0)
{
    $arr_postmeta = array();

    if ($id != 0) {

        $arr_postmeta = get_post_custom($id);
    } else {
        $arr_postmeta = get_post_custom();
    }

    if ($arr_postmeta)
        return $arr_postmeta['membership'][0];
    else
        return 0;
}

function isPremium($id = 0)
{
    $membership = get_membership($id);

    if (strpos($membership, 'premium') !== false)
        return true;
    else
        return false;
}

function the_Address_Showrow($id = 0)
{
    global $wp_query;

    $arr_postmeta = array();
    if ($id == 0) {
        $arr_postmeta = get_post_custom();
    } else {
        $arr_postmeta = get_post_custom($id);
    }


    if ($arr_postmeta) {
        echo "<address>";
        echo "<div >";
        echo " <p style='/*border:1px solid black;*/ width:95%; /*margin:1em auto;*/ padding:0.7em'>";
        //if (isset($arr_postmeta['address_line'][0]))
        //    echo $arr_postmeta['address_line'][0] . " | ";  //"<br /> ";
        if (isset($arr_postmeta['stars'][0]))
            echo "<strong>Kategorie: </strong>" . $arr_postmeta['stars'][0] . "<br />";
        $location = get_field_object('location');

        $value = $location['value'];
        $choices = $location['choices'];
        if ($value) {
            echo "<strong>Lage: </strong>";
            foreach ($value as $v):
                echo $choices[$v] . " ";
            endforeach;
            echo "<br />";
        }

        if (isset($arr_postmeta['postcode'][0]))
            echo "<strong>Ort: </strong>" . $arr_postmeta['postcode'][0] . " ";
        if (isset($arr_postmeta['city'][0]))
            echo $arr_postmeta['city'][0] . "<br />";

        if (isset($arr_postmeta['state'][0]))
            echo "<strong>Bundesland: </strong>" . $arr_postmeta['state'][0] . "<br />";
        if (isset($arr_postmeta['country'][0]))
            echo "<strong>Land: </strong>" . $arr_postmeta['country'][0] . "<br />";


        //if (strlen($arr_postmeta['website'][0]) > 4)
        //   echo '<a href="http://' . $arr_postmeta['website'][0] . '" target="_blank" class="blocked"> zur Website</a>&nbsp;';
        // if (isset($arr_postmeta['email'][0]))
        //    echo ' | <a href="mailto:'.$arr_postmeta['email'][0].'?subject=Anfrage%20ueber%kuschelhotels.de" target="_blank" class="blocked">eMail</a>&nbsp;';

        echo " </p>
               </div>
               </address>";
    }
}

/**
 * @param int $id = post ID
 * echos Addressdata
 */
//------------------equal to de_addressbook------------------------------------------------------------------------
function the_Address($id = 0)
{
    global $wp_query;

    $arr_postmeta = array();
    if ($id == 0) {
        $arr_postmeta = get_post_custom();
    } else {
        $arr_postmeta = get_post_custom($id);
    }


    if ($arr_postmeta) {
        echo "<address>";
        echo "<div >";
        echo " <p style='/*border:1px solid black;*/ width:100%; /*margin:1em auto;*/ padding:0.7em'>";
        if (isset($arr_postmeta['name'][0]))
            echo "<b>" . $arr_postmeta['name'][0] . "</b><br />";
        if (isset($arr_postmeta['atitle'][0]))
            echo $arr_postmeta['atitle'][0] . " <br />";
        if (isset($arr_postmeta['last_name'][0]))
            echo $arr_postmeta['last_name'][0] . ", ";
        if (isset($arr_postmeta['first_name'][0]))
            echo $arr_postmeta['first_name'][0] . "<br />";
        if (isset($arr_postmeta['address_line'][0]))
            echo $arr_postmeta['address_line'][0] . " | ";  //"<br /> ";
        if (isset($arr_postmeta['postcode'][0]))
            echo $arr_postmeta['postcode'][0] . " ";
        if (isset($arr_postmeta['city'][0]))
            echo $arr_postmeta['city'][0] . "<br />";
        if (strlen($arr_postmeta['phone'][0]) > 1)
            echo "Telefon: ";
        if (strlen($arr_postmeta['phone_intprefix'][0]) > 1)
            echo "(" . $arr_postmeta['phone_intprefix'][0] . ") ";
        else
            echo "(0049) ";
        if (strlen($arr_postmeta['phone'][0]) > 1)
            echo $arr_postmeta['phone_prefix'][0] . " " . $arr_postmeta['phone'][0] . "<br />";
        if (strlen($arr_postmeta['fax'][0]) > 1)
            echo "Fax: ";
        if (strlen($arr_postmeta['fax_intprefix'][0]) > 1)
            echo "(" . $arr_postmeta['phone_intprefix'][0] . ") ";
        if (strlen($arr_postmeta['fax'][0]) > 1)
            echo $arr_postmeta['fax_prefix'][0] . " " . $arr_postmeta['fax'][0] . "<br />";
        /* if (isset($arr_postmeta['email'][0]))
          echo '<a href="javascript: void(\'0\');" onClick="toggle(\'email\');" class="blocked">E-Mail</a><br>
          <div id="email" style="display: none;">
          <br>
          <strong>Nehmen sie Kontakt auf mit "'.$arr_postmeta['email'][0].'"</strong>
          <?
          insert_cform(\'E-Mail Kundenkontakt\'); ?>
          </div>'; */
        if (strlen($arr_postmeta['website'][0]) > 4)
            echo '<a href="http://' . $arr_postmeta['website'][0] . '" target="_blank" class="blocked"> zur Website</a>&nbsp;';
        if (isset($arr_postmeta['email'][0]))
            echo ' | <a href="mailto:' . $arr_postmeta['email'][0] . '?subject=Anfrage%20ueber%kuschelhotels.de" target="_blank" class="blocked">eMail</a>&nbsp;';

        echo " </p>
               </div>
               </address>";
    }
}

/**
 * @param int $id
 */
function the_Address_inline($id = 0, $stitle = true)
{
    global $wp_query;

    $arr_postmeta = array();

    if ($id != 0) {
        $arr_postmeta = get_post_custom();
    } else {
        $arr_postmeta = get_post_custom($id);
    }


    if ($arr_postmeta) {
        echo "<address style='margin:1px; !important'>";
        //echo "<div >";
        echo " <p style='/*border:1px solid black;*/ width:100%; /*margin:1px;*/ padding:0.05em'>";
        if (isset($arr_postmeta['name'][0]) && $stitle)
            echo $arr_postmeta['name'][0] . "<br />";
        if (isset($arr_postmeta['atitle'][0]) && $stitle)
            echo $arr_postmeta['atitle'][0] . " <br />";
        if (isset($arr_postmeta['last_name'][0]) && $stitle)
            echo $arr_postmeta['last_name'][0] . ", ";
        if (isset($arr_postmeta['first_name'][0]) && $stitle)
            echo $arr_postmeta['first_name'][0] . "<br />";
        if (isset($arr_postmeta['address_line'][0]))
            echo $arr_postmeta['address_line'][0];
        if (isset($arr_postmeta['postcode'][0]))
            echo " | " . $arr_postmeta['postcode'][0] . " ";
        if (isset($arr_postmeta['city'][0]))
            echo $arr_postmeta['city'][0] . "<br />";
        if (isset($arr_postmeta['phone_intprefix'][0]) && $stitle)
            echo $arr_postmeta['phone_intprefix'][0] . " " . $arr_postmeta['phone_prefix'][0] . " " . $arr_postmeta['phone'][0];
        /* if (isset($arr_postmeta['email'][0]))
          echo '<a href="javascript: void(\'0\');" onClick="toggle(\'email\');" class="blocked">E-Mail</a><br>
          <div id="email" style="display: none;">
          <br>
          <strong>Nehmen sie Kontakt auf mit "'.$arr_postmeta['email'][0].'"</strong>
          <?
          insert_cform(\'E-Mail Kundenkontakt\'); ?>
          </div>'; */
        if (strlen($arr_postmeta['website'][0]) > 4 && $stitle)
            echo " | " . '<a href="http://' . $arr_postmeta['website'][0] . '" target="_blank" class="blocked">Website</a>&nbsp;';

        echo " </p>
               <!--/div-->
    </address>";
    }
}

/* * ** HELPER FUNCTIONS * */
//-----------------------------------------------------------
/**
 * Add Post type for addresses
 */
add_action('init', 'de_create_post_type');
//-----------------------------------------------------------
function de_create_post_type()
{
    register_post_type('address', array(
            'labels' => array(
                'name' => __('Adressen'),
                'singular_name' => __('Adresse')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => ''),
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revision', 'post-formats')
        )
    );
}


//-----------------------------------------------------------
/**
 * Parse PLZ from slug or Post Title
 */
//-----------------------------------------------------------

function getplz()
{
    global $wp_query, $query;
    $slug = basename(get_permalink());
    $tPLZ = substr($slug, -1, 1);

    return $tPLZ;
}

//-----------------------------------------------------------
/**
 * Parse last word from slug
 */
//-----------------------------------------------------------

function getLWord()
{
    global $wp_query, $query;
    $slug = basename(get_permalink());
    $pieces = explode('-', $slug);
    $last = array_pop($pieces);

    return $last;
}


//-----------------------------------------------------------
/**
 * @param string $needle : search needle
 * @return mixed|null|string
 * Parse Attribute from slug or Post Title
 */
//-----------------------------------------------------------
function getAttr($needle = "")
{
    global $wp_query, $query, $post;
    if (!is_single() && !is_page()) {
        $slug = single_cat_title('', false);
    } else {
        $finds = $post->post_name;
        if ($needle != "") {
            $pos = strpos($finds, $needle);
            if ($pos !== false) {
                $finds = str_replace($needle, "", $finds);
            }

        }
        $slug = $finds;
    }
    return $slug;
}

//-----------------------------------------------------------
/**
 * @param $pieces
 * @return mixed
 * allows filtering like '5%'
 */
function filter_meta_query($pieces)
{

    if (!empty($pieces['where'])) {
        $pieces['where'] = preg_replace('/(.*?)LIKE \'\%(.*?)\%\'/', "\$1LIKE '\$2%'", $pieces['where']);
    }

    return $pieces;
}

/**
 * @param $term_id : ID of term
 * @param string $type : post type neeedd
 * @param int $ppp : posts per page
 * @param int $paged : current page
 * Returns Posts or else given a term (like tag or something else)
 */
//-----------------------------------------------------------
// EVTL. deprecated (cbw, 17.03.2016)
function the_tagArchive($term_id, $type = 'address', $ppp = 10, $paged = 1)
{

    $att = 'plz_list';

    //old style till 20.04.2015 (by cbw)
    $types = array('posts');
    $types = array_push($types, $type);

    //new style
    add_filter('get_meta_sql', 'filter_meta_query');
    $args = array(
        'tag_id' => $term_id,
        'post_type' => $types,
        'posts_per_page' => $ppp,
        'order' => 'DESC',
        'orderby' => 'meta_value',
        'meta_key' => 'membership',
        'paged' => $paged
    );
    $posts = new WP_Query($args);

    // remove it after the query.
    remove_filter('get_meta_sql', 'filter_meta_query');

    //Sort Posts in correct order
    OsortPosts($posts, $att, $paged, $ppp);

}

//-----------------------------------------------------------
function the_Attribute($att, $needle = "", $comp = 'LIKE', $type = 'address', $ppp = 10, $paged = 1)
{

    //new style
    add_filter('get_meta_sql', 'filter_meta_query');
    $value = "";
    if (strlen($needle) > 0) {
        $value = getAttr($needle);
    } else {
        $value = getLWord();
    }

    $args = array(
        'post_type' => $type,
        'posts_per_page' => $ppp,
        'order' => 'DESC',
        'paged' => $paged,
        'meta_query' => array(
            array(
                'key' => $att,
                'value' => $value,
                'compare' => $comp,
                //'type' => 'NUMERIC'
            ),
            'membership' => array(
                'key' => 'membership',
                'compare' => 'EXISTS',
            )
        ),
        'orderby' => array(
            'membership' => 'DESC',
            'ID' => 'DESC'
        )
    );
    $args['facetwp'] = true;
    $posts = new WP_Query($args);

    // remove it after the query.
    remove_filter('get_meta_sql', 'filter_meta_query');

    return $posts;
}

//-----------------------------------------------------------
/**
 * @param string $type : for css
 * @param int $ppp : posts per page
 * @param int $paged : current page
 */
function get_the_PLZ($type = 'address', $ppp = 10, $paged = 1)
{
    $att = 'postcode';

    //new style (20.04.2015 by CBW)
    add_filter('get_meta_sql', 'filter_meta_query');
    $args = array(
        'post_type' => $type,
        'posts_per_page' => $ppp,
        'order' => 'DESC',
        'paged' => $paged,

        'meta_query' => array(
            'relation' => 'AND',
            'post_code' => array(
                'key' => $att,
                'value' => getplz(),
                'compare' => 'LIKE',
                //'type' => 'NUMERIC'
            ),
            'country' => array(
                'key' => 'country',
                'value' => 'Deutschland',
                'compare' => 'LIKE',
                //'type' => 'NUMERIC'
            ),
            'membership' => array(
                'key' => 'membership',
                'compare' => 'EXISTS',
            )
        ),
        'orderby' => array(
            'membership' => 'DESC',
            'ID' => 'DESC'
        )
    );
    $args['facetwp'] = true;
    $posts = new WP_Query($args);

    // remove it after the query.
    remove_filter('get_meta_sql', 'filter_meta_query');
    $sortedPosts = OsortPosts($posts, $style, $paged, $ppp);
    return $sortedPosts;
}
//TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
function the_PLZ($type = 'address', $ppp = 10, $paged = 1)
{
    global $post;
    $sortedPosts = get_the_PLZ($type,$ppp,$paged);


    if (count($sortedPosts) > 0) {
        ?>
        <div class="facetwp-template">

            <? foreach ($sortedPosts as $post):

                setup_postdata($post);
                write_post_klon($post);

            endforeach; ?>
        </div>

        <div class="navigation">
            <div
                class="alignleft fixedleft"><?php previous_posts_link_custom(__('&laquo; Vorherige Eintraege')) ?></div>
            <div class="aligncenter fixedcenter"><?php page_nums($posts->found_posts); ?></div>
            <div class="alignright fixedright"><?php next_posts_link_custom(__('Weitere Eintraege &raquo;')) ?></div>
        </div>
        </div> <!-- this div is only needed in kuschelhotels old... DEPRECATED)-->
        <?
    } else {
        ?> <p><?php if ($style == 'plz_list') _e('Sorry, no posts matched your criteria.'); ?></p> <?
    }

}

function get_the_State($type = 'address', $ppp = 10, $paged)
{
    global $post;
    $att = 'state';
    $needle = "";

    $posts = the_Attribute($att, $needle, 'LIKE', $type, $ppp, $paged);
    $sortedPosts = OsortPosts($posts, $style, $paged, $ppp);
    return $sortedPosts;
}

function get_the_Proximity($type = 'address', $ppp = 10, $paged)
{
    global $post;
    $style = "plz";

    $args = array(
        'post_type' => $type,
        'posts_per_page' => $ppp,
        'order' => 'DESC',
        'paged' => $paged,
        'meta_query' => array(
            array(
                'key' => $att,
                'value' => $value,
                'compare' => $comp,
                //'type' => 'NUMERIC'
            ),
            'membership' => array(
                'key' => 'membership',
                'compare' => 'EXISTS',
            )
        ),
        'orderby' => array(
            'membership' => 'DESC',
            'ID' => 'DESC'
        )
    );
    $args['facetwp'] = true;    
    $posts = new WP_Query($args);

    // remove it after the query.
    remove_filter('get_meta_sql', 'filter_meta_query'); 
    
    $sortedPosts = OsortPosts($posts, $style, $paged, $ppp);

    return $sortedPosts;
}

//-----------------------------------------------------------
/**
 * Print the Posts with correct State
 */
//TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
function the_State($type = 'address', $ppp = 10, $paged)
{
global $post;
    $att = 'state';
    $needle = "";

    $posts = the_Attribute($att, $needle, 'LIKE', $type, $ppp, $paged);
    $sortedPosts = OsortPosts($posts, $style, $paged, $ppp);

    if (count($sortedPosts) > 0) {
        ?>
        <div class="facetwp-template">

            <? foreach ($sortedPosts as $post):

                setup_postdata($post);
                write_post_klon($post);

            endforeach; ?>
        </div>

        <div class="navigation">
            <div
                class="alignleft fixedleft"><?php previous_posts_link_custom(__('&laquo; Vorherige Eintraege')) ?></div>
            <div class="aligncenter fixedcenter"><?php page_nums($posts->found_posts); ?></div>
            <div class="alignright fixedright"><?php next_posts_link_custom(__('Weitere Eintraege &raquo;')) ?></div>
        </div>
        </div> <!-- this div is only needed in kuschelhotels old... DEPRECATED)-->
        <?
    } else {
        ?> <p><?php if ($style == 'plz_list') _e('Sorry, no posts matched your criteria.'); ?></p> <?
    }
}

/**
 * Print the Posts with correct Country
 */
function get_the_Country($type = 'address', $ppp = 10, $paged)
{
    $att = 'country';
    $needle = "";
    //new style
    $posts = the_Attribute($att, $needle, 'LIKE', $type, $ppp, $paged, false);
    $sortedPosts = OsortPosts($posts, 'plz_list', $paged, $ppp);

    return $sortedPosts;
}

//-----------------------------------------------------------
// TODO: !!DEPRECATED !!!not working at the moment...!!!! (CBW 15.06.2016)
//TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
function the_Country($type = 'address', $ppp = 10, $paged)
{
    global $post, $max_page;
    $att = 'country';
    $needle = "";
    $style = 'plz_list';
    //new style
    $posts = the_Attribute($att, $needle, 'LIKE', $type, $ppp, $paged);
    $sortedPosts = OsortPosts($posts, $style, $paged, $ppp);

    if (count($sortedPosts) > 0) {
        ?>
        <div class="facetwp-template">

            <? foreach ($sortedPosts as $post):

                setup_postdata($post);
                write_post_klon($post);

            endforeach; ?>
        </div>

        <div class="navigation">
            <div
                class="alignleft fixedleft"><?php previous_posts_link_custom(__('&laquo; Vorherige Eintraege')) ?></div>
            <div class="aligncenter fixedcenter"><?php page_nums($posts->found_posts); ?></div>
            <div class="alignright fixedright"><?php next_posts_link_custom(__('Weitere Eintraege &raquo;')) ?></div>
        </div>
        </div> <!-- this div is only needed in kuschelhotels old... DEPRECATED)-->
        <?
    } else {
        ?> <p><?php if ($style == 'plz_list') _e('Sorry, no posts matched your criteria.'); ?></p> <?
    }
}

//-------------------------------------------------------------
/**
 * Sort Posts by membership
 */
function OsortPosts($posts, $style = "plz_list", $paged = 1, $postsperpage = 10, $slice = false)
{
    global $post, $max_page; //necessary for setup_postdata

    $postslist = array();

    $offset = ($paged - 1) * $postsperpage;
    $numposts = $posts->found_posts;

    $max_page = $posts->max_num_pages;

    $pageposts = $posts->posts;

    /** initialize arrays */
    $sortingPosts = array();
    $WithPhoto = array();
    $WithoutPhoto = array();
    $Standard = array();
    $Premium = array();
    $TestPremium = array();
    $Sticky = array();

    if ($pageposts): // Posts sortieren
        /** do the magic */
        foreach ($pageposts as $post) {

            setup_postdata($post);
            $id = get_the_id();
            /** membership ordering * */
            $membership = get_post_meta($id, "membership", true);
            if (is_sticky($post->ID)) {
                $Sticky[] = $post;
            } elseif ($membership == '3-premium') {
                $Premium[] = $post;
            } elseif ($membership == '2-test-premium') {
                $TestPremium[] = $post;
            } elseif ($membership == '1-standard' || $membership == '1-standard') {
                $Standard[] = $post;
            } elseif (strlen(get_post_meta($id, "pictures", true)) > 0 || has_post_thumbnail($id)) { //photo ?
                $WithPhoto[] = $post;
            } else {
                $WithoutPhoto[] = $post;
            }
        }

        $sortingPosts = array_merge($Sticky, $Premium);
        $sortingPosts = array_merge($sortingPosts, $TestPremium);
        $sortingPosts = array_merge($sortingPosts, $Standard);
        $sortingPosts = array_merge($sortingPosts, $WithPhoto);
        $sortingPosts = array_merge($sortingPosts, $WithoutPhoto);

        /** membership ordering * */

        //cut array to needed length
        if ($slice) {
            $sortingPosts = array_slice($pageposts, $offset, $postsperpage - 1);
        }
        if ($sortingPosts):
            for ($i = 0; $i < ($postsperpage); $i++):
                if ($i < count($sortingPosts)) {
                    $postslist[] = $sortingPosts[$i];
                }
            endfor;
        endif;
    endif;

    return $postslist;
}

//-------------------------------------------------------------
/**
 * write custom post
 */
//TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
function write_post_klon_old($post) { //PRIMARY KUSCHELHOTELS!!! FIX THIS!!
//global $post;
//global $wp_query;

if (is_int($post)) {
    $post = get_post($post);
    setup_postdata($post);
}
//print_r($post);
?>
<div class="recent">
    <div class="list_title">
        <a href="<?php the_permalink() ?>" rel="bookmark"><?php isAddress($post);
            the_title(); ?></a>
    </div>
    <?php
    $text_len = 350;
    $bildurl = "";
    $valid = de_post_thumbnail($bildurl, get_the_ID());

    if ($valid):
    $text_len = 350;
    ?>
    <div class="recent_list_image">
        <a href="<?php the_permalink() ?>" rel="bookmark"><img title="default" alt="alt" src="<?php echo $bildurl ?>"/></a>
    </div>
    <div class="recent_list_content">
        <?php else:
        $text_len = 250; ?>
        <div class="list_content_only">
            <?php
            endif;
            $text = getContentWithoutScript($post->post_content, $text_len);
            if (strlen($text) < 1) {
                $text = strip_tags(get_meta("text", false));
            }
            the_excerpt_deguto($text_len, $text);
            ?>
            <div class="list_footer">
                <?php
                //if(strlen(get("name", 1, 1, false)) > 1) echo "<strong>".get("name", 1, 1, false)."</strong><br />\n";
                //if(strlen(get("postcode", 1, 1, false)) > 1) echo get("postcode", 1, 1, false)." ";
                //if(strlen(get("state", 1, 1, false)) > 1) echo "&#1769;&nbsp; <i>".get("state", 1, 1, false)."</i>"; //." | ";
                //if(strlen(get("city", 1, 1, false)) > 1) echo "<i>&nbsp; -&nbsp; ".get("city", 1, 1, false)."</i>"; //." | ";
                if (get_meta("city", false))
                    echo '<img src="' . get_bloginfo("template_url") . '/images/home.png" title="Ort:" />' . " <i>" . get_meta("city", false) . "</i>"; //." | house-->&#1769;";
                if (get_meta("state", false))
                    echo " (<i>" . get_meta("state", false) . "</i>)"; //." | ";


                //if(strlen(get("adress_line", 1, 1, false)) > 1) echo get("adress_line", 1, 1, false)." | ";
                //if(strlen(get("contact_phone_prefix", 1, 1, false)) > 1) echo "Tel: ".get("contact_phone_prefix", 1, 1, false).' - '.get("contact_phone", 1, 1, false)."<br />\n";
                ?>
                <a href="<?php the_permalink() ?>" class="more-link">&hellip;weiter lesen</a>
                <?php if (!(is_category() OR is_tag())): ?>
                    <p class="postmetadata"><?php edit_post_link('<img src="' . get_bloginfo("template_url") . '/images/edit.png" title="Artikel bearbeiten" />', ' ', ''); ?><?php // comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;');  ?></p>
                    <?php
                else:
                    edit_post_link('<img src="' . get_bloginfo("template_url") . '/images/edit.png" title="Artikel bearbeiten" />', ' | ', '');
                endif;
                ?>
            </div>
        </div>
    </div>
    <!-- ENDE class="recent" -->
    <?php
    }

    //TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
    function write_post_klon($post, $style = 'old')
    {
//global $post;
//global $wp_query;

        if (is_int($post)) {
            $post = get_post($post);
            setup_postdata($post);
        }
        $user = wp_get_current_user();

        write_post_klon_old($post);
    }

    //-----------------------------------------------------------
    function getCustomContents($type, $cContents)
    {
        $i = 0;
        if (get_field($type)) {
            while (has_sub_field($type)) {
                $subfieldHead = get_sub_field('head');
                if (strlen($subfieldHead) < 2)
                    $subfieldHead = $cContents[$i];


                $subfield = get_sub_field('text');
                if (strlen($subfield) > 1) {
                    echo '<h2>' . $subfieldHead . '</h2>';
                    echo '' . $subfield . '';
                }
                $i++;
            }
        }
    }

    //-----------------------------------------------------------
    //search for address posts in tag sites

    function de_acpt2q($query)
    {
        if ((!is_page_template("startseite.php") && is_category()) || is_tag()) {
            $post_type = get_query_var('post_type');
            if ($post_type)
                $post_type = $post_type;
            else
                $post_type = array('address', post); // replace cpt to your custom post type
            $query->set('post_type', $post_type);
            return $query;
        }
    }

/** AB hier evtentuell auslagern in anderes Plugin */
//TODO: DEPRECATED!!! DISPLAY STUFF SHOULD ONLY BE IN THEME FILES!!! FIX THIS! (CBW 15.06.2016)
    //*********FUNCTION FOR ENTRIES ON SITES**/
    function de_pagePosts(&$post, $offset = 0, $ppp = 10)
    {
    $cArgs = de_getPageQueryArgs($post, $offset, $ppp);

    //print_r($cArgs);
    $the_query = new WP_Query($cArgs);
    //print_r($the_query);
    OsortPosts($the_query, "page_list", $paged, $ppp);
    //OsortPosts($posts, $style = "plz_list", $paged = 1, $postsperpage = 10, $slice = false)
    wp_reset_postdata();
    ?>

</div>
<?
}

/**
 * @param $post
 * @param int $paged
 * @param int $ppp
 * @param bool $membership
 * @return array
 * BUILD ARRAY FOR getting POSTS
 */
function de_getPageQueryArgs(&$post, $paged = 0, $ppp = 10, $membership = false)
{
    //$offset = $ppp * (max($paged-1,0));
    //test if there exist some subpages....
    $submenu = wp_list_pages
    (
        array
        (
            'child_of' => $post->ID,
            'echo' => false
        )
    );

    if ($submenu) { //new 12.11.2014 CBW

        $post_type = 'page';
        /* Unterseiten dieser Seite anzeigen */
        $cArgs =
            array(
                'showposts' => $ppp,
                'post_type' => $post_type,
                'post_parent' => $post->ID,
                'orderby' => 'menu_order',
                'order' => 'asc'
            );


    } else {
        $post_type = 'address';
        $termId = getTermID($post->ID); //needs termrobot --> daher besser in termrobot packen

        $cArgs = array(
            'post_type' => $post_type,
            'posts_per_page' => $ppp,
            //'offset'=>$offset,
            'paged' => $paged,
            'tax_query' => array(
                array(
                    'taxonomy' => 'seiten',
                    'field' => 'term_id',
                    'terms' => $termId

                ),
            )
        );
        if ($membership) {
            /*    $cArgs['meta_query'] = array(
                array(
                    'key'     => 'membership',
                    'value'   => $membership,
                    'compare' => 'LIKE',

                ),
            );*/
            $cArgs['orderby'] = 'meta_value';
            $cArgs['meta_key'] = 'membership';
            $cArgs['order'] = 'DESC';
        }
    }
    return $cArgs;
}

function de_doPageQuery(&$post, $offset = 0, $ppp = 10)
{
    $cArgs = de_getPageQueryArgs($post, $offset, $ppp);

    $the_query = new WP_Query($cArgs);
    $posts = $the_query->posts;

    wp_reset_postdata();

    return $posts;

}

//*********FUNCTION FOR ENTRIES ON SITES**/


function de_customTags($id, $taxonomy)
{
//print_r($taxonomy);

    $tag_ids = wp_get_object_terms($id, $taxonomy, array('fields' => 'ids'));
    //print_r($tag_ids);
    if (!empty($tag_ids)) {
        if (!is_wp_error($tag_ids)) {
            ?><h3> Folgendes im Sortiment:</h3> <?
            $args = array(
                'smallest' => 8,
                'largest' => 22,
                'unit' => 'pt',
                'number' => 45,
                'format' => 'flat',
                //'separator'                 => \\"\n\\",
                'orderby' => 'name',
                'order' => 'ASC',
                'exclude' => null,
                'include' => $tag_ids,
                'topic_count_text_callback' => default_topic_count_text,
                'link' => 'view',
                'taxonomy' => $taxonomy,
                'echo' => true,
                //'child_of'                   => null//(see Note!)
            );
            wp_tag_cloud($args);
        }
    }
}

add_action('save_post', 'de_geCode', 20, 2);
/** GEOCODER */
function de_geCode($post_id, $post)
{
    if ('revision' == $post->post_type || $post->post_type != 'address' || $post->post_status == 'auto-draft') {
        // don't store custom data twice
        return $post->ID;
    }

    if (isset($_POST['fields']['field_14'])) {
        $address = $_POST['fields']['field_15'] . ", " . $_POST['fields']['field_13'] . ", " . $_POST['fields']['field_14'] . ", " . $_POST['fields']['field_11'];

        $resp = wp_remote_get("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false");

        if (200 == $resp['response']['code']) {
            $body = $resp['body'];
            $data = json_decode($body);
            if ($data->status == "OK") {
                $latitude = $data->results[0]->geometry->location->lat;
                $longitude = $data->results[0]->geometry->location->lng;
                //$longitude = $data->results[0]->address_components->location->lng;
                $addressData = array("address" => $address, "lat" => $latitude, "long" => $longitude);
                update_post_meta($post_id, 'maps_location', $addressData);
            }
        }
    }

    return $post->ID;

}

/** ZUM AUSLAGERN!!!! */

function de_SubPosts($ID, $post_type)
{

    // Artikel die mit dieser Seite angehakt wurden anzeigen
    $args = array(
        'post_parent' => $ID,
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order');

    $childList = get_posts($args);

    return $childList;

}

/** NEW MAPS */
/*
function os_do_loop(&$obj) {
    // Create the section that will hold the google map
    // The name used here for the class is repeated in JS and CSS files


    echo '<div class="google-acfmap">';

    foreach($obj as $post)
    {
        //  Get location coordinates from google maps field in ACF
        $location = get_field('coords',$post->ID);
    
        if(strlen($location['lat'] < 1)||strlen($location['lng'] < 1) )
            continue;
        //  Set required Google Maps Marker info with lat/lng
        //  Use post link and title for HTML info window on marker on map
        //  Show address on HTML info window also
        $thumb_id = get_post_thumbnail_id($post);
        if($thumb_id !="" && 0)
            $thumb_url = 'data-icon="'.wp_get_attachment_image_src($thumb_id,'thumbnail', true)[0].'">';
        else
            $thumb_url = ">";
        ?>
        <div class="marker" data-lat="<?php echo $location[lat]; ?>" data-lng="<?php echo $location[lng]; ?>"

             <?php echo $thumb_url; ?>

            <h4><a href="<?php echo get_permalink($post); ?>"><?php echo $post->post_title ?></a></h4>

            <div class="location-image"><?php echo get_the_post_thumbnail($post,'thumbnail');
                ?></div>

            <p><?php echo $location['address']; ?></p>
        </div>
        <?
    }
    echo '</div><!-- .google-acfmap -->';
    //endif;
}
*/
/** NEW MAPS */

function os_do_loop(&$obj) {
    // Create the section that will hold the google map
    // The name used here for the class is repeated in JS and CSS files


    echo '<div class="google-acfmap">';

    foreach($obj as $post)
    {
        //  Get location coordinates from google maps field in ACF
        $location = get_field('coords',$post->ID);
    
        if(strlen($location['lat'] < 1)||strlen($location['lng'] < 1) )
            continue;
        //  Set required Google Maps Marker info with lat/lng
        //  Use post link and title for HTML info window on marker on map
        //  Show address on HTML info window also
        $thumb_id = get_post_thumbnail_id($post);
        if($thumb_id !="" && 0)
            $thumb_url = 'data-icon="'.wp_get_attachment_image_src($thumb_id,'thumbnail', true)[0].'">';
        else
            $thumb_url = ">";
        ?>
        <div class="marker" data-lat="<?php echo $location[lat]; ?>" data-lng="<?php echo $location[lng]; ?>"

             <?php echo $thumb_url; ?>

            <h4><a href="<?php echo get_permalink($post); ?>"><?php echo $post->post_title ?></a></h4>

            <div class="location-image"><?php echo get_the_post_thumbnail($post,'thumbnail');
                ?></div>

            <p><?php echo $location['address']; ?></p>
        </div>
        <?
    }
    echo '</div><!-- .google-acfmap -->';
    //endif;
}
/*
function os_do_loop(&$obj) {
    // Create the section that will hold the google map
    // The name used here for the class is repeated in JS and CSS files


    echo '<div class="google-acfmap">';
    
    foreach($obj as $post)
    {
        //  Get location coordinates from google maps field in ACF
        $location = get_field('coords',$post->ID);
        if(strlen($location['lat'] < 1)||strlen($location['lng'] < 1) )
            continue;

        echo '<div class="leaflet-popup-content-wrapper">';
        echo '<div class="leaflet-popup-content" style="width: 201px;">';   
        ?>
        <a class="popup" href="<?php echo get_permalink($post); ?>">

       
        <?
        $thumb_id = get_post_thumbnail_id($post);
        if($thumb_id !="" && 0)
            $thumb_url = 'data-icon="'.wp_get_attachment_image_src($thumb_id,'thumbnail', true)[0].'">';
        else
            $thumb_url = ">";
        ?>
        <div class="popup__image" style="background-image: url(<?php  echo $thumb_url; ?>);"></div>
        <div class="marker" data-lat="<?php echo $location[lat]; ?>" data-lng="<?php echo $location[lng]; ?>" </div>
        <div class="popup__content">   
            <h3 class="popup__title"><?php  echo get_the_title($post->ID); ?></h3>
            <div class="popup__footer">
            <div class="popup__rating">
            <span>4.5</span>
            </div>
            <div class="popup__address">
            <div itemprop="streetAddress">
            <span class="address__street-no"></span>
            <span class="address__street"><? the_field('address_line',$post->ID); ?></span>
            </div>
            <span class="address__city" itemprop="addressLocality"><?the_field('city',$post->ID);?></span>
            <span class="address__state-short" itemprop="addressRegion"><?the_field('state',$post->ID);?></span>
            <span class="address__country-short" itemprop="addressCountry"><?the_field('country',$post->ID);?></span>
            <span class="address__postcode" itemprop="postalCode"><?the_field('postcode',$post->ID);?></span>            
        </div>  
        </div> <!-- popup content -->
        </div>

    </a>
        <?
        echo '</div><!--leaflet end-->';
        echo '</div><!--leaflet end-->';
    }
    
    echo '</div><!-- .google-acfmap -->';
    //endif;
}
*/

?>

