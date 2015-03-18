<?php

/*
Plugin Name: Equipment Post Type
Description: functions for the custom post type 'equipment' for a Genesis site.  Templates are part of the theme
Version: 1.0
Author: Ken Nawrocki

*/

/*
* Define custom post type 'equipment'
*/

function my_custom_post_equipment() {
		$labels = array('name' => _x('Equipment', 'post type general name'), 'singular_name' => _x('Piece of Equipment', 'post type singular name'), 'add_new' => _x('Add New', 'Equipment'), 'add_new_item' => __('Add New Equipment'), 'edit_item' => __('Edit Equipment'), 'new_item' => __('New Equipment'), 'all_items' => __('All Equipment'), 'view_item' => __('View Equipment'), 'search_items' => __('Search Equipment'), 'not_found' => __('No equipment found'), 'not_found_in_trash' => __('No equipment found in the Trash'), 'parent_item_colon' => '', 'menu_name' => 'Equipment');
		$args = array('labels' => $labels, 'description' => 'Holds our equipment and equipment specific data', 'rewrite' => array('slug' => 'equipment', 'with_front' => false), 'public' => true, 'menu_position' => 5, 'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'), 'has_archive' => true,);
		register_post_type('equipment', $args);
		flush_rewrite_rules(false);
}

add_action('init', 'my_custom_post_equipment');

/*
* define custom messages for post type 'equipment'
*/
function my_updated_messages($messages) {
		global $post, $post_ID;
		$messages['equipment'] = array(0 => '', 1 => sprintf(__('Equipment updated. <a href="%s">View Equipment</a>'), esc_url(get_permalink($post_ID))), 2 => __('Custom field updated.'), 3 => __('Custom field deleted.'), 4 => __('Equipment updated.'), 5 => isset ($_GET['revision']) ? sprintf(__('Equipment restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false, 6 => sprintf(__('Equipment published. <a href="%s">View Equipment</a>'), esc_url(get_permalink($post_ID))), 7 => __('equipment saved.'), 8 => sprintf(__('Equipment submitted. <a target="_blank" href="%s">Preview Equipment</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))), 9 => sprintf(__('Equipment scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Equipment</a>'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))), 10 => sprintf(__('equipment draft updated. <a target="_blank" href="%s">Preview equipment</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),);
		return $messages;
}

add_filter('post_updated_messages', 'my_updated_messages');

/*
* The contextual help feature
*/
function my_contextual_help($contextual_help, $screen_id, $screen) {
		if ('equipment' == $screen->id) {
				$contextual_help = '<h2>Equipment</h2>
		<p>Equipment show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p>
		<p>You can view/edit the details of each piece of equipment by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
		}
		elseif ('edit-equipment' == $screen->id) {
				$contextual_help = '<h2>Editing Equipment</h2>
		<p>This page allows you to view/modify equipment details. Please make sure to fill out the available boxes with the appropriate details (equipment image, price, brand) and <strong>not</strong> add these details to the equipment description.</p>';
		}
		return $contextual_help;
}

add_action('contextual_help', 'my_contextual_help', 10, 3);

/*
* Define taxonomies for custom post type 'equipment'
*/
function taxonomies_equipment() {
		$labels = array('name' => _x('Equipment Categories', 'taxonomy general name'), 'singular_name' => _x('Equipment Category', 'taxonomy singular name'), 'search_items' => __('Search Equipment Categories'), 'all_items' => __('All Equipment Categories'), 'parent_item' => __('Parent Equipment Category'), 'parent_item_colon' => __('Parent Equipment Category:'), 'edit_item' => __('Edit Equipment Category'), 'update_item' => __('Update Equipment Category'), 'add_new_item' => __('Add New Equipment Category'), 'new_item_name' => __('New Equipment Category'), 'menu_name' => __('Equipment Categories'),);
		$args = array('labels' => $labels, 'hierarchical' => true,);
		register_taxonomy('equipment_category', 'equipment', $args);
}

add_action('init', 'taxonomies_equipment', 0);

/*
* Add meta box for post type 'equipment'
*/
add_action('admin_init', 'my_equipment_metabox');

function my_equipment_metabox() {
		add_meta_box('display_equipment_details_meta_box', 'equipment Details', 'display_equipment_details_meta_box', 'equipment', 'side', 'high');
}

function display_equipment_details_meta_box($equipment) {
// Retrieve meta box details based on equipment ID
		$equipment_price    = esc_html(get_post_meta($equipment->ID, 'Price', true));
		$equipment_make     = esc_html(get_post_meta($equipment->ID, 'Make', true));
		$equipment_refID    = esc_html(get_post_meta($equipment->ID, 'ID', true));
		$equipment_year     = esc_html(get_post_meta($equipment->ID, 'Year', true));
		$equipment_model    = esc_html(get_post_meta($equipment->ID, 'Model', true));
		$equipment_capacity = esc_html(get_post_meta($equipment->ID, 'Capacity', true));
		$equipment_new      = esc_html(get_post_meta($equipment->ID, 'New', true));
		?>

        <table>
          <tr>
            <td><span class="equipment-meta-box-label">Tracking Number:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_refID" value="<?php echo $equipment_refID;?>" />
              </span></td>
          </tr>
          <tr>
            <td><span class="equipment-meta-box-label">Price:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_price" value="<?php echo $equipment_price;?>" />
              </span></td>
          </tr>
          <tr>
            <td><span class="equipment-meta-box-label">Year:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_year" value="<?php echo $equipment_year;?>" />
              </span></td>
          </tr>
          <tr>
            <td><span class="equipment-meta-box-label">Make:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_make" value="<?php echo $equipment_make;?>" />
              </span></td>
          </tr>
          <tr>
            <td><span class="equipment-meta-box-label">Model:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_model" value="<?php echo $equipment_model;?>" />
              </span></td>
          </tr>
          <tr>
            <td><span class="equipment-meta-box-label">Capacity:</span></td>
            <td><span class="equipment-meta-box-value">
              <input type="text" size="20" name="equipment_capacity" value="<?php echo $equipment_capacity;?>" />
              </span></td>
          </tr>
         </table>
<?php   }   ?>
<?php
register_nav_menu('equipment', 'Equipment');

/*
* Save the equipment meta box contents
*/
add_action('save_post', 'add_equipment_details_fields', 10, 2);

function add_equipment_details_fields($equipment_id, $equipment) {
        // Check post type for equipment
		if ($equipment->post_type == 'equipment') {
                // Store data in post meta table if present in post data
				if (isset ($_POST['equipment_refID']) && $_POST['equipment_refID'] != '') {
						update_post_meta($equipment_id, 'ID', $_POST['equipment_refID']);
				}
				if (isset ($_POST['equipment_price']) && $_POST['equipment_price'] != '') {
						update_post_meta($equipment_id, 'Price', $_POST['equipment_price']);
				}
				if (isset ($_POST['equipment_year']) && $_POST['equipment_year'] != '') {
						update_post_meta($equipment_id, 'Year', $_POST['equipment_year']);
				}
				if (isset ($_POST['equipment_make']) && $_POST['equipment_make'] != '') {
						update_post_meta($equipment_id, 'Make', $_POST['equipment_make']);
				}
				if (isset ($_POST['equipment_model']) && $_POST['equipment_model'] != '') {
						update_post_meta($equipment_id, 'Model', $_POST['equipment_model']);
				}
				if (isset ($_POST['equipment_capacity']) && $_POST['equipment_capacity'] != '') {
						update_post_meta($equipment_id, 'Capacity', $_POST['equipment_capacity']);
				}
		}
}

/*
* Display a single equipment post
*/
function equipment_singlepost() {
		while (have_posts()) : the_post();
    		$custom             = get_post_custom();
    		$equipment_price    = esc_html($custom['Price'][0]);
    		$equipment_make     = esc_html($custom['Make'][0]);
    		$equipment_refID    = esc_html($custom['ID'][0]);
    		$equipment_year     = esc_html($custom['Year'][0]);
    		$equipment_model    = esc_html($custom['Model'][0]);
    		$equipment_capacity = esc_html($custom['Capacity'][0]);
    		$content            = get_the_content();
    		$clean_content      = strip_shortcodes($content);
    		$shortcode          = str_replace($clean_content, "", $content);
            ?>
            <div id="post-<?php the_ID();?>" <?php post_class();?>> <?php echo do_shortcode($shortcode);?>
              <div class="entry-content" id="equipment_content">
                <h4>
                  <?php the_title();?>
                </h4>
                <div id="inner_equipment_content"> <span class="equipment_label">Make:</span><span class="equipment_data"><?php echo $equipment_make;?></span> <span class="equipment_label">Model:</span><span class="equipment_data"><?php echo $equipment_model;?></span> <span class="equipment_label">Price:</span><span class="equipment_data"><?php echo $equipment_price;?></span> <span class="equipment_label">Year:</span><span class="equipment_data"><?php echo $equipment_year;?></span> <span class="equipment_label">Capacity:</span><span class="equipment_data"><?php echo $equipment_capacity;?></span> <span class="equipment_label">Details:</span><span class="equipment_data"> <?php echo $clean_content;?> </span> <!--<span class="equipment_label">Price:</span><span class="equipment_data"><?php echo $equipment_price;?></span>--> </div>
              </div>
            </div><!--end #post -->
        <?php endwhile;?>

<?php 	}
// Lists out equipment types
function list_equipment_types() {
		$taxonomy        = 'equipment_category';
		$orderby         = 'name';
		$show_count      = 0; 		// 1 for yes, 0 for no
		$pad_counts      = 0; 		// 1 for yes, 0 for no
		$hierarchical    = 1; 		// 1 for yes, 0 for no
		$title           = '';
		$args            = array('taxonomy' => $taxonomy, 'orderby' => $orderby, 'show_count' => $show_count, 'pad_counts' => $pad_counts, 'hierarchical' => $hierarchical, 'title_li' => $title);
?>
        <div id="post-<?php the_ID();?>" <?php post_class();?> >
          <div class="entry-content">
    		<?php
    		$terms = get_terms($taxonomy, $args);
    		$count = count($terms);
    		$i = 0;
    		if ($count > 0) {
    				$cape_list = '<p class="equipment_category-archive">';
    				foreach ($terms as $term) {
    						$i++;
    						echo '<div class="equipment_category">';
    						echo '<div class="equipment-category-title"><a href="' . site_url() . '/' . $taxonomy . '/' . $term->slug . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a></div>';
    						if (function_exists('s8_taxonomy_image')) {
    								echo '<div class="taxonomy-term-image-container">';
    								s8_taxonomy_image($term, 'thumbnail');
    								echo '</div>';
    						}
    						echo "<p class='equipment_category_description'>$term->description</p>";
    						echo '<div class="clear"></div></div>';
    				}
    		}
    		?>
          </div>
          <!-- end .entry-content -->
        </div>
        <!--end  #post -->
<?php }
function showroom_archive_loop() {
		while (have_posts()) : the_post();
		$custom = get_post_custom();
		$equipment_price = esc_html($custom['Price'][0]);
		$equipment_make = esc_html($custom['Make'][0]);
		$equipment_refID = esc_html($custom['ID'][0]);
		$equipment_year = esc_html($custom['Year'][0]);
		$equipment_model = esc_html($custom['Model'][0]);
		$equipment_capacity = esc_html($custom['Capacity'][0]);
		echo '<div class="equipment_box">';
		if (has_post_thumbnail()) {   	?>
                <div class="equipment_thumb_wrapper"><div class="equipment_thumb"> <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>" >
                  <?php the_post_thumbnail('medium');?>
                  </a></div></div>
              <?php }?>
                <h6><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>" >
                  <?php the_title();?>
                  </a></h6>
                <span class="equipment_label">Year:</span><span class="equipment_data"><?php echo $equipment_year;?></span> <span class="equipment_label">Capacity:</span><span class="equipment_data"><?php echo $equipment_capacity;?></span> <span class="equipment_label">Price:</span><span class="equipment_data"><?php echo $equipment_price;?></span> <span class="equipment_label">ID:</span><span class="equipment_data"><?php echo $equipment_refID;?></span>
                </div>
              <?php endwhile;?>
<?php }?>
<?php

function equipment_search_form() {
		$search_text = get_search_query() ? esc_attr(apply_filters('the_search_query', get_search_query())) : apply_filters('genesis_search_text', esc_attr__('SEARCH OUR EQUIPMENT', 'genesis') . '&#x02026;');
		$button_text = apply_filters('genesis_search_button_text', esc_attr__('Search', 'genesis'));
		$onfocus = " onfocus=\"if (this.value == '$search_text') {this.value = '';}\"";
		$onblur = " onblur=\"if (this.value == '') {this.value = '$search_text';}\"";
        //* Don't apply JS events to user input
		if (is_search())
				$onfocus = $onblur = '';

        /** Empty label, by default. Filterable. */
		$label = apply_filters('genesis_search_form_label', '');
		$form = '
		<form method="get" class="searchform search-form" action="' . home_url() . '/" >
			' . $label . '
			<input type="text" value="' . esc_attr($search_text) . '" name="s" class="s search-input"' . $onfocus . $onblur . ' />
			<input type="submit" class="searchsubmit search-submit" value="' . esc_attr($button_text) . '" />
			<input type="hidden" name="post_type" value="equipment" />
		</form>
        ';
		echo $form;
}
/*
* Add stylesheet
*/

wp_enqueue_style('equipment', plugins_url('equipment') . '/equipment.css');


?>
