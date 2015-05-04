<?php
/*

**************************************************************************

Plugin Name:  WP Hide Category
Plugin URI:   http://www.arefly.com/wordpress-index-hide-category/
Description:  Hide specific category in your blog.
Version:      1.0.7
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  wp-hide-category
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

**************************************************************************/

define("WP_HIDE_CATEGORY_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("WP_HIDE_CATEGORY_FULL_DIR", plugin_dir_path( __FILE__ ));
define("WP_HIDE_CATEGORY_TEXT_DOMAIN", "wp-hide-category");

/* Plugin Localize */
function wp_hide_category_load_plugin_textdomain() {
	load_plugin_textdomain(WP_HIDE_CATEGORY_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'wp_hide_category_load_plugin_textdomain');

include_once WP_HIDE_CATEGORY_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function wp_hide_category_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.WP_HIDE_CATEGORY_TEXT_DOMAIN.'-options').'">'.__("Settings", WP_HIDE_CATEGORY_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wp_hide_category_action_links');

function wp_hide_category($query) {  
	if(!is_admin() && $query->is_main_query()) {
		$wp_hide_category_hide_place = get_option('wp_hide_category_hide_place');
		$category_hide = get_option('wp_hide_category_id', array());
		if(!empty( $wp_hide_category_hide_place)) {
			foreach($wp_hide_category_hide_place as $template_name){
				$call_name = 'is_' . $template_name;
				if($query->$call_name()){
					if(!empty($category_hide)) {
						foreach($category_hide as $cat_id) {
							$query->set('cat', '-' . $cat_id);
						}
					}
				}
			}
		}
	}
}
add_action('pre_get_posts', 'wp_hide_category');
