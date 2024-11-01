<?php
/*
Plugin Name: Z Flakera na Blog
Plugin URI: http://www.muzungu.pl
Description: Plugin łączy się raz na jakiś czas z flakerem, pobiera z niego nasze wpisy, łączy w jeden i publikuje raz dziennie na blogu.
Version: 0.1
Author: Konrad Karpieszuk
Author URI: http://www.muzungu.pl
* Plugin URI: http://www.muzungu.pl/moje-pluginy-do-wordpressa/z-flakera-na-blog/
*/
include_once('libs.php');
include_once('mzgadmin.php');

register_activation_hook(__FILE__, 'mzg_flak_install');
 
function mzg_flak_install() {
	add_option('mzg_flak_login', 'wpisztuswojloginzflakera');
	add_option('mzg_flak_tag', 'miniblog');
	add_option('mzg_flak_title', 'Moja ostatnia aktywność na Flakerze');
	add_option('mzg_flak_since', time()-5400);
	wp_schedule_event(time()-81200, 'daily', 'mzg_flak_hook');
	}
	
add_action('mzg_flak_hook', 'mzg_add_flaks');


register_deactivation_hook(__FILE__, 'mzg_flak_remove');  

function mzg_flak_remove() {
	delete_option('mzg_flak_login');
	delete_option('mzg_flak_tag');
	delete_option('mzg_flak_title');
	delete_option('mzg_flak_since');
	wp_clear_scheduled_hook('mzg_flak_hook');  
	} 





function mzg_add_flaks() {

$flaker = new mzg_flaker(get_option('mzg_flak_login'), get_option('mzg_flak_tag'));
$contents = $flaker->connect();

$json = new mzg_json();
$json_decoded = $json->mzg_json_decode($contents, true);
$entries = $json_decoded['entries'];

if (!empty($entries)) {



$mzg_post = array();
$mzg_post['post_title'] = get_option('mzg_flak_title');
$mzg_post['post_status'] = 'publish';
$mzg_post['post_author'] = 1;

include_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

$mzg_cat_ID = (int) get_cat_ID( get_option('mzg_flak_tag') );
if ($mzg_cat_ID == 0) {
	$mzg_cat_ID = (int) wp_create_category(get_option('mzg_flak_tag')); 
}

$mzg_post['post_category'] = array($mzg_cat_ID);
foreach ($entries as $entry) {
	$mzg_post['post_content'] .= "<div class='singleFlakerEntry'>" . $entry["text"] . "</div>";
	$mzg_tags = $entry["tags"];
	if (count($mzg_tags) != 0) {
		foreach ($mzg_tags as $tag) {
			$mzg_post['tags_input'] .= str_replace("#", "", $tag) . ", ";
		}
	}
	}
wp_insert_post( $mzg_post );
update_option('mzg_flak_since', $entries[0]["timestamp"]);
}

}

function mzg_show_flaks() {
$flaker = new mzg_flaker(get_option('mzg_flak_login'), get_option('mzg_flak_tag'));
$contents = $flaker->connect();

$json = new mzg_json();
$json_decoded = $json->mzg_json_decode($contents, true);

?>
<pre style="background-color: white; font-size: 14px; text-align: left">
<?php
echo $flaker->get_zapytanie() . "\n";
var_dump($json_decoded);
?>
</pre>
<?php

}

// add_action('init', 'mzg_add_flaks');
// add_action('wp_footer', 'mzg_show_flaks');

?>
