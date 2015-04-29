<?php
function wp_hide_category_register_settings() {
	add_option('wp_hide_category_hide_place', array("front_page"));
	add_option('wp_hide_category_id', array());
	register_setting('wp_hide_category_options', 'wp_hide_category_hide_place');
	register_setting('wp_hide_category_options', 'wp_hide_category_id');
}
add_action('admin_init', 'wp_hide_category_register_settings');

function wp_hide_category_register_options_page() {
	add_options_page(__('WP Hide Category Options Page', WP_HIDE_CATEGORY_TEXT_DOMAIN), __('WP Hide Category', WP_HIDE_CATEGORY_TEXT_DOMAIN), 'manage_options', WP_HIDE_CATEGORY_TEXT_DOMAIN.'-options', 'wp_hide_category_options_page');
}
add_action('admin_menu', 'wp_hide_category_register_options_page');

function wp_hide_category_get_checked($checkbox_name, $check_value){
	if(is_array($checkbox_name)){
		if(in_array($check_value, $checkbox_name)){
			?> checked="checked"<?php
		}
	}
}

function wp_hide_category_get_checkbox_option($checkbox_name, $checkbox_value, $checkbox_id){
	for($num = 0; $num < count($checkbox_id); $num++){
		$checkbox_value_each = $checkbox_value[$num];
		$checkbox_id_each = $checkbox_id[$num];
		?>
		<input type="checkbox" name="<?php echo $checkbox_name; ?>[]" id="<?php echo $checkbox_id_each; ?>" value="<?php echo $checkbox_id_each; ?>"<?php wp_hide_category_get_checked(get_option($checkbox_name), $checkbox_id_each); ?>><label for="<?php echo $checkbox_id_each; ?>"><?php echo $checkbox_value_each; ?></label>
	<?php
	}
}

function cat_list_recursive($parentid = 0, $selected = array()){
	$cats = get_categories('hide_empty=0&child_of='.$parentid.'&parent='.$parentid);
	if(count($cats) > 0){
		$str = '<ul class="children">';
		foreach($cats as $c){
			$sel = (isset($selected) && in_array($c->cat_ID, $selected)) ? true : false;
			$str .= '<li><input type="checkbox" name="wp_hide_category_id[]" value="'.$c->cat_ID.'" id="catid_'.$c->cat_ID.'"'.(($sel)?' checked="checked"':'').' /> <label for="catid_'.$c->cat_ID.'">'.esc_html($c->cat_name).'</label>'.cat_list_recursive($c->cat_ID,$selected).'</li>';
		}
		$str .= '</ul>';
		return $str;
	}
	return '';
}

function wp_hide_category_options_page() {
?>
<div class="wrap">
	<h2><?php _e("WP Hide Category Options Page", WP_HIDE_CATEGORY_TEXT_DOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('wp_hide_category_options'); ?>
		<h3><?php _e("General Options", WP_HIDE_CATEGORY_TEXT_DOMAIN); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="wp_hide_category_hide_place"><?php _e("Where do you want to hide the category?", WP_HIDE_CATEGORY_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php wp_hide_category_get_checkbox_option("wp_hide_category_hide_place", array(__('Home', WP_HIDE_CATEGORY_TEXT_DOMAIN), __('Post', WP_HIDE_CATEGORY_TEXT_DOMAIN), __('Page', WP_HIDE_CATEGORY_TEXT_DOMAIN), __('Archive', WP_HIDE_CATEGORY_TEXT_DOMAIN), __('Search Result', WP_HIDE_CATEGORY_TEXT_DOMAIN)), array('front_page', 'single', 'page', 'archive', 'search')); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wp_hide_category_id"><?php _e("The Category ID that you want to hide: ", WP_HIDE_CATEGORY_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php echo cat_list_recursive(0, get_option('wp_hide_category_id', array())) ?>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>