<?php
$settings['enable_static_urls'] = false; // Dazu m�ssen auch in der http.conf die entsprechenden subdomains angelegt sein.
$settings['max_static_urls'] = 5;
$settings['static_url'] = 'content';

/**
  * \brief  get_Desc
  *		- returns the description for category or tag
  *
  */
function the_Desc(){
	$patt_1 = '~#?(#[0-9a-f]{3}\b|#[0-9a-f]{6}\b)~i';
	if(is_category()){
					$cat_desc = preg_replace($patt_1,"",category_description());

				if(strlen($cat_desc) > 8){
					echo $cat_desc;
				}else{
					$cat_option = get_option ('headspace_category');
					$description = $cat_option['description'];
					$description = str_replace('%%category%%',wp_title('',0),$description);
					echo $description;
				}
	 }elseif(is_tag()){
					$tag_desc =  preg_replace($patt_1,"",tag_description());

					if(strlen($tag_desc) > 8){
						echo $tag_desc;
					}else{
						$cat_option = get_option ('headspace_tag');
						$description = $cat_option['description'];
						$description = str_replace('%%tag%%',wp_title('',0),$description);
						echo $description;
					}
	 }
}

function getContentUrl(){
	global $ccounter, $settings;

	if($settings['enable_static_urls']){
		$ccounter ? null : $ccounter=1;
		($ccounter > $settings['max_static_urls']) ? $ccounter=1 : null;
		$url = get_bloginfo("url");
		$url = str_replace('www.', $settings['static_url'].$ccounter.'.', $url);
		$ccounter++;
	}else{
		$url = get_bloginfo("url").'/wp-content';
		//$url = '/wp-content';
	}
	return $url;
}

function wget(){
	if($_SERVER['HTTP_USER_AGENT'] == "Wget/1.11.4"){
		return true;
	}else{
		return false;
	}
}

function printWgetUrl($type="url"){
	if(wget()){
		$url = get_bloginfo($type);
		$url = str_replace('live.', '', $url);
	}else{
		$url = get_bloginfo($type);
	}
	echo $url;
}

function debug_num_queries($title="", $status=""){
	global $start, $start_time;
	$num_queries = get_num_queries();
	$time = timer_stop(0, 3);
	if(strtoupper($status)=="START"){
		$start = $num_queries;
		$start_time = $time;
		echo "<!-- Q: ".$num_queries." START | ".$title." -->\n";
	}elseif(strtoupper($status)=="STOP"){
		$stop = $num_queries-$start;
		$stop_time = $time - $start_time;
		echo "<!-- Q: ".$num_queries." STOP: ".$stop." in ".$stop_time." | ".$title." -->\n";
	}else{
		echo "<!-- Q: ".$num_queries." | ".$title." -->\n";
	}

}

function the_machine() {
	$aid = "322367";
	if (is_category()){
		$catdesc = category_description();
		parse_str(trim(strip_tags($catdesc)), $ary_catdesc);
		$url = $ary_catdesc['booking'];
	}
	//if (have_posts()) {
		//the_post();
		if (get_post_meta(get_the_id(), "booking", false)){
			$postmeta = get_post_meta(get_the_id(), "booking", false);
			$url = $postmeta[0];
		}
		//rewind_posts();
	//}
	if(strlen($url) > 10){ ?>
		<iframe src="<?=$url.$aid; ?>" frameborder="0" height="1600" width="776"></iframe>
	<?php
	}
}

function getContentWithoutScript($content="", $len=200){
	if($content==""){
		ob_start();
		//the_excerpt();
		the_excerpt_rss($len, 2);
		$content = ob_get_clean();
	}

	$regex = "/<(img|script).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
	preg_match_all($regex, $content, $finds);

	foreach($finds[0] as $key => $value){
		$content = str_replace($value, "", $content);
	}
	return $content;
}


function get_the_excerpt_deguto($len=200, $text="",  $more=false, $moreLinkURL="", $moreLinkName="weiter lesen", $moreLinkClass="morelink") {
	//echo "<!-- ### get_the_excerpt_klon # $text ## ".strlen($text)." -->";
	if(strlen($text) < 10){
		$text = getContentWithoutScript("", $len); // make Text
	}

	if($moreLinkURL == "") $moreLinkURL = get_permalink(); // make moreURL
	$moreLink='<a class="'.$moreLinkClass.'" href="'.$moreLinkURL.'">'.$moreLinkName.'</a>'; // make moreLink

	$text = str_replace("[...]", "", $text); // clean Text

	if(strlen($text) > $len){
		$text = substr($text, 0, $len);
		$spacePos = strripos($text, " ");
		$text = substr($text, 0, $spacePos);
	}
	if(strlen($text) > 10){
		if($more){
			$text.="&hellip;".$moreLink." ";
		}else{
			$text.="&hellip;";
		}
	}

	return $text; // final excerptText
}


function the_excerpt_deguto($len=200, $text="", $more=false) {
	$text = get_the_excerpt_deguto($len, $text="",  $more);
	if(strlen($text) > 0){
		echo $text;
	}
}

function filter_special_chars($content){
	$content = str_replace(array('&bdquo;','&ldquo;','&amp;'),array('','','&'),htmlspecialchars($content));
	/* '&szlig;','&uuml;','&auml;','&ouml;','&Uuml;','&Auml;','&Ouml;' */
	return $content;
}

function writeContentWithoutImages($content="", $default="", $postmeta = false, $echo = true){
	global $galleries,$flickrGalleries;
	if(!$content){
		ob_start();
		the_content();
		$content = ob_get_clean();
	}
/*
	$regex ='/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/si';
	$regex = "/<(img|image).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
*/
	$regex ='/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*><(img|image).*?src\s*=\s*["\'](.*?)["\'].*?><\/a>(\r\n)*/si';
//  $regex = '/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/i';
	preg_match_all($regex, $content, $finds);

	//print_r($finds);

	foreach($finds[0] as $key => $value){
		$content = str_replace($value, "", $content);
	}

//	$search = "@\[randompic=(\d+)(|,\d+|,)(|,\d+|,)(|,watermark|,web20|,)(|,right|,center|,left|,)\]@i";
//	preg_match_all($search, $content, $matches, PREG_SET_ORDER);
        //add_shortcode('flickrset', array(&$this, 'test'));

	$regex = "@\[nggallery id=(.*?)\]@i";
  preg_match_all($regex, $content, $galleries, PREG_SET_ORDER);
  //print_r($galleries);
        if($galleries){
            /*foreach($galleries[0] as $key => $value){
              echo "<h1> $value </h1>";
                    $content = str_replace($value, "", $content);
            }*/
          $content = str_replace($galleries[0][0],"" , $content);
        }

        $regex = "@\[flickrset id=(.*?)\]@i";
	preg_match_all($regex, $content, $flickrGalleries, PREG_SET_ORDER);
  if($flickrGalleries){
     foreach($flickrGalleries[0] as $key => $value){
      $content = str_replace($value, "", $content);
    }
  }
        
	/*$regex ='/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/si';
	preg_match_all($regex, $content, $finds);

	foreach($finds[0] as $key => $value){
		$content = str_replace($value, "", $content);
	}
	*/
  /* Am 14.06.2011 erneut auskommentiert da überall riesige Umbrüche zustande kamen. aus einem </p>/r/n->(vermutlich ungewollt durch ein editor)<p> wurde </p><br><p> */
//  $content = nl2br($content);

	if(strlen($content) < 10){
		$content = $default;
	}

	if($echo){
		echo $content;
		if($postmeta){?>
			<p class="postmetadata"><?php the_tags('Tags: '.' ', ', ', '<br />'); ?> <?php printf('Abgelegt in: %s', get_the_category_list(', ')); ?><?php edit_post_link('<img src="'.get_bloginfo("template_url").'/images/edit.png" title="Artikel bearbeiten" />', ' ', ''); ?>  <?php comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;'); ?></p><?php
		}
	}else{
		if($postmeta){
			return $content.'<p class="postmetadata">'.the_tags('Tags: '.' ', ', ', '<br />').' '.printf('Abgelegt in: %s', get_the_category_list(', ')).edit_post_link('<img src="'.get_bloginfo("template_url").'/images/edit.png" title="Artikel bearbeiten" />', ' ', '').comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;').'</p>';
		}else{
			return $content;
		}
	}
}

function get_content(){
		ob_start();
		the_content();
		$content = ob_get_clean();
		return $content;
}

/* new by cbw, think this is even mor effective than parsing the_content */
function getImagesFromDb($id,$urls = 0){
	global $wpdb, $table_prefix;
	$attachments = get_children( array( 'post_parent' => $id, 'post_type' => 'attachment', 'orderby' => 'menu_order ASC, ID', 'order' => 'DESC') );
//	print_r($attachments);
	/* alternativ */
	/*$wpdb->get_results('SELECT SQL_CALC_FOUND_ROWS * FROM '.$table_prefix.'posts WHERE 1=1 AND post_type = "attachment" '.$where_customer.' ORDER BY post_date DESC LIMIT 0, 100');
	$attachments = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_parent =".$id);
	*/
	//print_r($attachments);
	if($attachments){
		if(!$urls){
			return $attachments;
		}else{
			//print_r($attachments);
			foreach ($attachments as $attachment){
				$bildurls[] = $attachment->guid;
			}
			return $bildurls;
		}
	}
}

/*new version by cbw */
function getImageFromPost(){
	$c_images = getImagesFromDb(get_the_id(),1);

	if(count($c_images) > 0){
		$img_url = str_replace(get_bloginfo('url'), "", $c_images[0]);
		return $img_url;
	}else{
		return false;
	}
}

function getImageFromPost_old(){
	/*global $wp_filter;
	print_r($wp_filter['the_content']);*/

	ob_start();
	the_content();
	$content = ob_get_clean();


	$regex = "/<(img|image).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
	//echo $content;
	preg_match_all($regex, $content, $finds);

	if(count($finds[2]) > 0){
		$img_url = str_replace(get_bloginfo('url'), "", $finds[2][0]);
		return $img_url;
	}else{
		return false;
	}
}


function the_deguto_thumb($width=100) {
	$bildurl = getImageFromPost();
	?>
	<img alt="" src="<?php bloginfo('url') ?>/wp-content/plugins/_libs/thirdparty/phpthumb/phpThumb.php?w=<?=$width;?>&src=<?=$bildurl;?>" />
	<?php
}


function write_post_klon($post){
	setup_postdata($post);?>
<div class="recent">
	<div class="list_title">
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
	</div>
	<?php	$bildurl = get("picture", 1, 1);
	if(!@fopen($bildurl,"r")){
		$bildurl = getImageFromPost();
	}
	if(@fopen($bildurl,"r")):
		$text_len = 210;?>
	<div class="recent_list_image">
		<a href="<?php the_permalink() ?>" rel="bookmark"><img title="<?=get("picture_title", 1, 1, false)?>" alt="<?=get("picture_title", 1, 1, false)?>" src="<?php bloginfo('url') ?>/wp-content/plugins/fresh-page/thirdparty/phpthumb/phpThumb.php?w=100&src=<?php echo $bildurl ?>" /></a>
	</div>
	<div class="recent_list_content">
	<?php else:	$text_len = 290; ?>
	<div class="list_content_only">
	<?php endif;
		$text = getContentWithoutScript("", $text_len);
		if(strlen($text) < 1){
			$text = strip_tags(get("text", 1, 1, false));
		}
		the_excerpt_deguto($text, $text_len);?>
			<div class="list_footer">
				<?php
					//if(strlen(get("name", 1, 1, false)) > 1) echo "<strong>".get("name", 1, 1, false)."</strong><br />\n";
					//if(strlen(get("postcode", 1, 1, false)) > 1) echo get("postcode", 1, 1, false)." ";
					//if(strlen(get("state", 1, 1, false)) > 1) echo "&#1769;&nbsp; <i>".get("state", 1, 1, false)."</i>"; //." | ";
					//if(strlen(get("city", 1, 1, false)) > 1) echo "<i>&nbsp; -&nbsp; ".get("city", 1, 1, false)."</i>"; //." | ";
					if(strlen(get("city", 1, 1, false)) > 1) echo '<img src="'.get_bloginfo("template_url").'/images/home.png" title="Ort:" />'." <i>".get("city", 1, 1, false)."</i>"; //." | house-->&#1769;";
					if(strlen(get("state", 1, 1, false)) > 1) echo " (<i>".get("state", 1, 1, false)."</i>)"; //." | ";
					//if(strlen(get("adress_line", 1, 1, false)) > 1) echo get("adress_line", 1, 1, false)." | ";
					//if(strlen(get("contact_phone_prefix", 1, 1, false)) > 1) echo "Tel: ".get("contact_phone_prefix", 1, 1, false).' - '.get("contact_phone", 1, 1, false)."<br />\n";
				?>
			<a href="<?php the_permalink() ?>" class="morelink">&hellip;weiter lesen</a>
		<?php if (! (is_category() OR is_tag())): ?>
			<p class="postmetadata"><?php the_tags('<img src="'.get_bloginfo("template_url").'/images/tags.png" title="Tags:" /> ', ', ', '<br />'); ?> <?php printf('<img src="'.get_bloginfo("template_url").'/images/folder.png" title="Abgelegt in Kategorien:" /> %s', get_the_category_list(', ')); ?><?php edit_post_link('<img src="'.get_bloginfo("template_url").'/images/edit.png" title="Artikel bearbeiten" />', ' ', ''); ?>  <?php /* comments_popup_link('Keine Kommentare &#187;', '1 Kommentar &#187;', '% Kommentare &#187;');*/ ?></p>
		<?php else:
			edit_post_link('<img src="'.get_bloginfo("template_url").'/images/edit.png" title="Artikel bearbeiten" />', ' | ', '');
		 endif; ?>
			</div>
		</div>
</div>
<!-- ENDE class="recent" -->
<?php }



function write_small_posts($post){
	setup_postdata($post); ?>
	<div class="recent">
		<div class="list_title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>
		<?php $bildurl = get("picture", 1, 1);
		if(strlen($bildurl) > 5){
			$textlen = 100;
		}elseif(strlen($bildurl) < 5){
			ob_start();
			the_content();
			$content = ob_get_clean();

			$regex = "/<(img|image).*?src\s*=\s*['\"](.*?)['\"].*?>/i";
			preg_match_all($regex, $content, $finds);

			foreach($finds[0] as $key => $value){
				$content = str_replace($value, "", $content);
			}

			if(count($finds[2]) > 0){
				$textlen = 100;
				$bildurl = $finds[2][0];
			}

		}
		if(strlen($bildurl) > 5): ?>
		<div class="recent_list_image">
			<a href="<?php the_permalink() ?>" rel="bookmark">
				<img alt="<?=get("picture_title", 1, 1, false)?>" src="<?php bloginfo('url') ?>/wp-content/plugins/fresh-page/thirdparty/phpthumb/phpThumb.php?w=100&src=<?=$bildurl?>" />
			</a>
		</div>
		<?php endif; ?>
		<div class="recent_list_content">
			<?php	//Text abschneiden
			if(!isset($textlen)){
				$textlen = 190;
			}

			$text = strip_tags(get_the_content());

			if(strlen($text) < 1){
				$text = strip_tags(get("text", 1, 1, false));
			}
			if(strlen($text) > $textlen){
				$textlen = strpos($text, " ", $textlen);
			}
			echo $text_kurz = substr($text, 0, $textlen);
			echo "&hellip;";
			$more_link='<a class="more-link-block" href="'.get_permalink().'"> ...mehr</a>';
			//ENDE Text abschneiden
			?>
		</div>

		<?php echo $more_link; ?>
	</div>
	<?php return get_the_id(); ?>
<?php }


function write_post_plz($post){
	setup_postdata($post); ?>
	<div class="recent">
		<div class="list_title">
			<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
		</div>
		<?php	$bildurl = get("picture", 1, 1); ?>
		<?php if(@fopen($bildurl,"r")):
		$text_len = 185; ?>
		<div class="recent_list_image">
			<a href="<?php the_permalink() ?>" rel="bookmark"><img title="<?=get("picture_title", 1, 1, false)?>" alt="<?=get("picture_title", 1, 1, false)?>" src="<?php bloginfo('url') ?>/wp-content/plugins/fresh-page/thirdparty/phpthumb/phpThumb.php?w=100&h=&f=png&src=<?php echo $bildurl ?>" /></a>
		</div>
		<div class="recent_list_content">
		<?php else:	$text_len = 250; ?>
		<div class="list_content_only">
		<?php endif; ?>
			<p>
				<?php
					//Text abschneiden
					$text = strip_tags(get_the_content());

					if(strlen($text) < 1){
						$text = strip_tags(get("text", 1, 1, false));
					}
					if(strlen($text) > 0){
						echo $text_kurz = substr($text, 0, $text_len);
						echo $more_link='<a class="morelink" href="'.get_permalink().'"> ... weiter lesen</a>';
					}
					//ENDE Text abschneiden	?>
			</p>
			<div class="list_footer">
				<?php
				/*
					if(get("membership", 1, 1, false) == "adresse"){
				}
				*/
					//if(strlen(get("name", 1, 1, false)) > 1) echo "<strong>".get("name", 1, 1, false)."</strong><br />\n";
					//if(strlen(get("postcode", 1, 1, false)) > 1) echo get("postcode", 1, 1, false)." ";
					//if(strlen(get("state", 1, 1, false)) > 1) echo "&#1769;&nbsp; <i>".get("state", 1, 1, false)."</i>"; //." | ";
					//if(strlen(get("city", 1, 1, false)) > 1) echo "<i>&nbsp; -&nbsp; ".get("city", 1, 1, false)."</i>"; //." | ";
					if(strlen(get("city", 1, 1, false)) > 1) echo "&#1769;&nbsp; <i>".get("city", 1, 1, false)."</i>"; //." | ";
					if(strlen(get("state", 1, 1, false)) > 1) echo " (<i>".get("state", 1, 1, false)."</i>)"; //." | ";
					//if(strlen(get("adress_line", 1, 1, false)) > 1) echo get("adress_line", 1, 1, false)." | ";
					//if(strlen(get("contact_phone_prefix", 1, 1, false)) > 1) echo "Tel: ".get("contact_phone_prefix", 1, 1, false).' - '.get("contact_phone", 1, 1, false)."<br />\n";
				?>
		<?php if (! (is_category() OR is_tag())): ?>
			<p class="postmetadata"><?php the_tags('<i>Tags: </i>'.' ', ', ', '<br />'); ?> <?php printf('<i>Abgelegt in: </i>%s', get_the_category_list(', ')); ?> | <?php edit_post_link('<img src="'.get_bloginfo("template_url").'/images/edit.png" title="Artikel bearbeiten" />', '', ''); ?></p>
		<?php endif; ?>
			</div>
		</div>
		<!-- ENDE DIV list content -->
	</div>
	<!-- ENDE DIV list -->
	<?php }

function is_tree($pid) {    // $pid = The page we're looking for pages underneath
	global $post;       // We load this as we're outside of the post
	if(is_page()&&($post->post_parent==$pid||is_page($pid))) return true; // Yes, it's in the tree
	else return false;  // No, it's outside
};

function is_subpage($pid) {
	global $post;                                 // load details about this page
  if ( is_page() && $post->post_parent ) {      // test to see if the page has a parent
         $parentID = $post->post_parent;        // the ID of the parent is this
         return ($pid == $parentID) ? true : false;
  } else {                                      // there is no parent so...
         return false;                          // ...the answer to the question is false
  };
};



function write_address_post($row, $post, $parent, $mainpost = 1){
?>
<!-- BEGIN Addresspost -->
<?php
	//setup_postdata($post);
	$membershipdata = $parent->get_membership_data($row->address_id);
	//<div class="addressmanager-list">
	//($mainpost AND $post AND $post->post_status == "publish" AND $main_link = $parent->get_mainpost_permalink($row->address_id)) ? $mainpost=1 : $mainpost=0;
	if ( is_user_logged_in() ){	$editlink = "/wp-admin/admin.php?page=addressmanager/addressmanager.php&action=customer&address_id=".$row->address_id."&_adma_nonce=".wp_create_nonce($row->address_id);
?><a href="<?=$editlink;?>" class="edit" alt="Adresse verwalten" target="_blank"><img src="<?=$parent->plugin_dir;?>/images/icon_edit.gif" /></a>
<?}
	if($main_link = $parent->get_mainpost_permalink($row->address_id) AND $mainpost AND $membershipdata->membership > 0 ){
?><a class="entrylist_wrapper <?php if($mainpost AND $membershipdata->membership > 0) echo "with_mainpost"; ?> <?=get_cat_class($parent,$row->address_id)?> <?=$parent->get_membership($membershipdata->membership);?>" href="<?=$main_link?>" title="zum Haupteintrag"><?
	}else{ ?>
	<span class="entrylist_wrapper <?php if($membershipdata->membership > 0) echo "with_mainpost"; ?> <?=get_cat_class($parent,$row->address_id)?> <?=$parent->get_membership($membershipdata->membership);?>">
<?}
	if($mainpost):?>
	<span class="entrylist_header">
	<h2><strong><?=get($row,"name")?></strong></h2>
	</span><?
	endif;?>
	<span class="entry">
		<span class="entrylist_adress">
		<?=get($row,"address_line")?><br/>
		<?=get($row,"postcode")?>&nbsp;<?=get($row,"city")?><br/>
		<!--<?=get($row,"state")?><br/><?=get($row,"country")?><br/>-->
		Tel.: <?=str_replace("00", "+",get($row,"phone_intprefix"))?> <?=get($row,"phone_prefix")?> <?=get($row,"phone")?><br/>
	  	Fax: <?=str_replace("00", "+",get($row,"fax_intprefix"))?> <?=get($row,"fax_prefix")?> <?=get($row,"fax")?><br/>
		</span><?
      if(strlen(get($row,"email")) > 8):
				if($mainpost AND $membershipdata->membership > 0){?>
		<span class="entrylist_email blocked">E-Mail</span>
<?   	}else{?>
			<a href="mailto:<?=get($row,"email")?>" class="entrylist_email blocked">E-Mail</a>
<?   	}
      endif;
	  	if(strlen(get($row,"website")) > 8):
		  	if($mainpost AND $membershipdata->membership > 0 ){
?>			<span class="entrylist_website blocked">Website</span>
<?			}else{
?>			<a href="<?=get($row,"website")?>" class="blocked" target="_blank">Website</a>
<?			}
			endif;
?>		</span>
<?		if($membershipdata->membership > 0):
      	if($mainpost):
?>		<span class="mainpost">
<?		if($post->post_status == "publish") :
      	setup_postdata($post);
				/*<strong><?php echo $post->post_title; ?></strong><br/>*/?>
		<span class="excerpt">
		<span class="logo">
<?			if($imageurl=getImageFromPost()):
?>				<img width="60" alt="" src="<?=getContentUrl();?>/plugins/_libs/phpthumb/phpThumb.php?&w=60&h=&aoe=1&f=png&src=<?=$imageurl?>" />
<?					endif;
?>			</span>
<?		 	echo substr(get_the_excerpt(), 0, 160)." ..."; ?>
				</span>
<? 			endif;
?>		</span>
<? 		else:
      setup_postdata($post);
?>		<span class="excerpt">
				<span class="logo">
        	<? if($imageurl=getImageFromPost()): ?>
					<img width="" alt="" src="<?=getContentUrl();?>/plugins/_libs/phpthumb/phpThumb.php?&w=220&h=&f=png&src=<?=$imageurl?>" />
<? 				endif; ?>
				</span>
			</span>
<? 		endif; ?>
<? 	endif;
		if($main_link AND $mainpost AND $membershipdata->membership > 0 ){?>
	<span class="morelink">mehr</span>
</a>
<?	}else{?>
</span>
<?	}
//</div>
?>
<!-- END Addresspost -->
<?
}

function write_written_posts($post, &$tags){
		setup_postdata($post);

		$posttags = wp_get_post_tags( $post->ID ); // get tags of the post
		// add new tag objects to the tags array (array_merge does not work for objects)
		foreach( $posttags as $posttag ) {
		  if( !array_key_exists($posttag->slug,$tags) )
		    $tags[ $posttag->slug ] = $posttag;
		}

		//$main_link = $parent->get_mainpost_permalink($row->address_id) ? $adma->get_mainpost_permalink($row->address_id) : get_permalink();
		$main_link = get_permalink();
		if ( is_user_logged_in() ){
			if($address_id = $post->post_parent){
				$editlink = "/wp-admin/admin.php?page=addressmanager/addressmanager.php&action=customer&address_id=".$address_id."&_adma_nonce=".wp_create_nonce($address_id); ?>
		<a href="<?=$editlink;?>" class="edit" alt="Adresse verwalten" target="_blank"><img src="<?=$admaa->plugin_dir;?>images/icon_edit.gif" /></a><?php
			}else{
				edit_post_link('<img src="'.$admaa->plugin_dir.'/images/icon_edit.gif" />', '', '');
			}
		}?>
		<a href="<?php echo $main_link;?>" class="post" title="">
			<h2><?php if(function_exists("sIFR_title")){ sIFR_title(); }else{ the_title(); }?></h2>
			<span class="excerpt">
      	<?php if($imageurl=getImageFromPost()): ?>
				<span class="logo">
        <? /*<a href="<?=getContentUrl();?>/plugins/_libs/phpthumb/phpThumb.php?w=800&f=png&src=<?=$imageurl?>" rel="lightbox[<?=get_post_meta(get_the_id(), "name", true);?>]">*/ ?>
					<img src="<?=getContentUrl();?>/plugins/_libs/phpthumb/phpThumb.php?&w=60&h=&aoe=1&f=png&src=<?=$imageurl?>" width="60" alt="" />
        <? /*</a>*/ ?>
				</span>
        <?php endif; ?>
      <?php echo substr(get_the_excerpt(), 0, 160)." ..."; ?>
			</span>
		</a><?php
}

function write_tag_cloud($tags) {
		// for example print out the tags
		ksort($tags);
		$list = "";
		foreach( $tags as $tag ) { // go through tags
		  //echo '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a> <b>&middot;</b> ';
		  $list.=$tag->term_id.',';
		}

	  return array(
	    "smallest"  => 8,
	    "largest"   => 19,
	    "unit"      => "pt",
	    "number"    => 30,
	    "format"    => "flat",
	    "separator" => " <b>&middot;</b> ",
	    "orderby"   => "name",
	    "order"     => "ASC",
	    "include"   => $list,
	    "link"      => "view",
	    "taxonomy"  => "post_tag",
	    "echo"      => false );
}

function write_capital($name,$alpha) {
	$capital =  substr($name, 0, 1);
	if(ctype_alpha($capital) && strtoupper($capital) != strtoupper($alpha)){ ?>
		<h2 class="capital"><a name="capital_<? echo strtoupper($capital); ?>"><?php echo strtoupper($capital); ?> </a> <a class="ontop" href="#capital_index"><span style="font-familiy: Arial; font-size: 12px;">&#9650</span></a></h2><?php
	}
	return $capital;
}
add_action('init', 'my_custom_init');

	function my_custom_init() {
		add_post_type_support( 'page', 'excerpt' );
	}

function custom_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

?>
