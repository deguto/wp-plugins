<?php
// create custom plugin settings menu
add_action('admin_menu', 'de_tr_menu');

function de_tr_menu() {

	//create new top-level menu
	add_menu_page('Termrobot Settings', 'Termrobot Settings', 'administrator', __FILE__, 'de_tr_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_de_tr_settings' );
}


function register_de_tr_settings() {
	//register our settings
	register_setting( 'de_tr_options', 'de_tr_rCat' );
	register_setting( 'de_tr_options', 'de_tr_cptb' );
}

function get_cpt (){
		global $wpdb,$table_prefix;
		$a_types = array();
		$a_verbose = array('revision','attachment');
		$query = "SELECT DISTINCT post_type FROM ".$table_prefix."posts";
		$rows = $wpdb->get_results( $query,"ARRAY_A" );
		foreach($rows as $k => $v){
			if(!in_array($v['post_type'],$a_verbose)){
				$a_types[] = $v['post_type'];
			}
		}
		return $a_types;
	}
	function checkbox_cpt (){
		global $dec;
		$a_cpt = get_cpt();
		$a_cpt_db = get_option('de_tr_cptb');		
		?><p>
<?php foreach($a_cpt as $k => $v){ ?>
			 <input type ="checkbox" name="de_tr_cptb[]" value=<?php echo $v; if(@in_array($v,$a_cpt_db)) echo " checked='checked'" ?> > <?php echo $v ?> </input> <br>
	<?php	}?>
		</p>
	<?php }

function de_tr_page() {
?>
<div class="wrap">
<h2>Termrobot Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'de_tr_options' ); ?>
    <?php //do_settings( 'de_tr_options' ); ?>
    <table class="form-table"> 
        <tr valign="top">
        <th scope="row">Hide Categories Metabox</th>
        <td><input type ="checkbox" name="de_tr_rCat" value="1" <? checked( 1, get_option('de_tr_rCat'), true )?> ></td>
        </tr>
				<tr valign="top">
        <th scope="row">Activate for following post types</th>
        <td><? checkbox_cpt(); ?></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>
