<?php
/*
Plugin Name: Shon's Posts List
Description: Adds a list of posts in a category to the bottom of a page with a title of the same name as the category
Version: 1.0
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	echo "Oops! No direct access please :)";
	exit;
}

if( !function_exists("ShonsBottomOfPosts"))
{
	/*	add our filter function to the hook */
	add_filter('the_content', 'ShonsBottomOfPosts');
	
	function limit_text($text, $limit) {
		if (str_word_count($text, 0) > $limit) {
				$words = str_word_count($text, 2);
				$pos = array_keys($words);
				$text = substr($text, 0, $pos[$limit]) . '...';
		}
		return $text;
	}
	
	function ShonsBottomOfPosts( $content )
	{
		global $post;
		
		$custom = get_post_custom( $post->ID );
		
		if( !isset( $custom['custom_category_toggle'][0] ) || $custom['custom_category_toggle'][0] !== 'true' || !isset( $custom['custom_category_id'][0] ) )
			return $content;
		
		$category_id = $custom['custom_category_id'][0];
		
		$posts_per_page = isset( $custom['custom_category_posts_per_page'][0] ) && is_numeric( $custom['custom_category_posts_per_page'][0] ) ? $custom['custom_category_posts_per_page'][0] : 10;
		
		$page = isset( $_GET['pg'] ) && is_numeric( $_GET['pg'] ) ? $_GET['pg'] : 1;
		
		$args = array(
			'cat'									=>	$category_id,
			'paged'								=>	$page,
			'posts_per_page'			=> ( $posts_per_page > 0 ? $posts_per_page : -1 ),
		);
		
		$shons_query = new WP_Query( $args );
		
		$category_list = '';
		
		if ( $shons_query->have_posts() ):
		
		if( $page > $shons_query->max_num_pages ) $page = $shons_query->max_num_pages;
		
		$pagination = array(
						'base' => @add_query_arg('pg', '%#%'),
						'format' => '',
						'total' => $shons_query->max_num_pages,
						'current' => $page,
						'prev_next' => true,
						'prev_text' => 'Prev',
						'next_text' => 'Next',
						'show_all' => false,
						'end_size' => 0,
						'mid_size' => 2,
						'type' => 'plain'
				);
		
		$pagination_links = paginate_links( $pagination );
		
		$category_list .= $pagination_links;
		
			while ( $shons_query->have_posts() ):
			
				$shons_query->the_post();
				
				$category_list .= '<h2><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
				
				$excerpt = isset( $custom['custom_category_excerpt_length'][0] ) && is_numeric( $custom['custom_category_excerpt_length'][0] ) && $custom['custom_category_excerpt_length'][0] > 0 ? limit_text( get_the_excerpt(), $custom['custom_category_excerpt_length'][0] ) : get_the_excerpt();
				
				$category_list .= !isset( $custom['custom_category_excerpt_toggle'][0] ) || $custom['custom_category_excerpt_toggle'][0] == 'true' ? '<p>' . $excerpt . ' <a href="' . get_the_permalink() . '">Read more</a></p>' : '';
			
			endwhile;
		
		$category_list .= $pagination_links;
		
		endif;
		
		wp_reset_postdata();
		
		return $content . $category_list;
	}
}

$create_instance = new ShonsPostsList();

  class ShonsPostsList
	{
		function __construct()
		{
			add_action('add_meta_boxes', array( $this, 'PostCategoriesMetaboxAddMetabox'));
			add_action('save_post', array( $this, 'PostCategoriesMetaboxSavePost'), 10, 1);
		}
    function GetTheCategoryArray(  )
		{
			
			$category_list = get_categories( array() );
			
			foreach ( $category_list as $key=>$val )
			{
				$select_box_options[ $val->term_id ] = $val->name . ' (' . $val->count . ')';
			}
			
			return $select_box_options;
		}
	
			//this is the main function that generates the HTML for the metabox
			function PostCategoriesMetabox($post, $metabox) {
				echo '<div id="entheme3-in">';
			
				$select_box_items = $this->GetTheCategoryArray(2);
				
				//$this->ItemDebug( $select_box_items );
			
				$values = $this->PostCategoriesMetaboxValues('metabox_postcategories');
			
				$this->PostCategoriesPluginMetaboxForm( $metabox['id'], array(
						array(
							'type'	=> 'checkbox',
							'id'		=> 'togglelist',
							'name'	=> 'custom_category_toggle',
							'text'	=> 'Show',
							'value'	=> $values['custom_category_toggle'],
						),
						array(
							'type'	=> 'numberbox',
							'id'		=> 'custom_category_posts_per_page',
							'name'	=> 'custom_category_posts_per_page',
							'text'	=> '# of Posts to show per page <br> (use 0 to show all)',
							'value'	=> $values['custom_category_posts_per_page'],
						),
						array(
							'type' 	=> 'selectbox',
							'id' 		=> 'select',
							'name' 	=> 'custom_category_id',
							'text' 	=> 'Choose Category',
							'value' => $values['custom_category_id'],
							'items' => $select_box_items,
						),
						array(
							'type'	=> 'checkbox',
							'id'		=> 'excerpttogglelist',
							'name'	=> 'custom_category_excerpt_toggle',
							'text'	=> 'Show excerpt',
							'value'	=> $values['custom_category_excerpt_toggle'],
						),
						array(
							'type'	=> 'numberbox',
							'id'		=> 'custom_category_excerpt_length',
							'name'	=> 'custom_category_excerpt_length',
							'text'	=> 'Excerpt max length (in words) <br> (use 0 to show full excerpt)',
							'value'	=> $values['custom_category_excerpt_length'],
						),
				) );
			
				echo '</div>';
			}
	
			/* this function builds the form inputs for each item by calling the UI builder function */
			function PostCategoriesPluginMetaboxForm( $metabox, $options) {
				foreach ($options as $key => $option) {
						$options[$key]['name'] = $option['name'];
				}
				$this->PostCategoriesMetaboxUIBuild($options);
			}
			
			/* This will return the values for each of the form items in each metabox, you do case followed by the metabox title
			 * then set $result = array() with the array items being the form item name as the key and default value as the value,
			 * in this case we only have one metabox named 'metabox_postcategories' and it has one field named 'custom_category_id' and we
			 * gave it an empty string as the default value.  the function will check for set values for each field and set the resulting
			 * array with those values if they exist or the default value if they don't. */
			function PostCategoriesMetaboxValues( $name ) {
				// Fill out metabox default values
				$result = array();
				switch ($name) {
						case 'metabox_postcategories': {
							$result = array(
									'custom_category_id' 							=> '',
									'custom_category_toggle' 					=> 'false',
									'custom_category_excerpt_toggle' 	=> 'true',
									'custom_category_posts_per_page' 	=> 0,
									'custom_category_excerpt_length'	=> 0
							);
							break;
						}
				}
				if (!is_404() && !is_search() && !is_category() && !is_date()) {
						foreach($result as $key=>$value) {
					$option = get_post_meta(get_the_ID(), $key, true);
					if($option != '') {
							$result[$key] = $option;
					}
						}
				}
				return $result;
			}
	
			/* this function actually adds the metaboxes to the approriate areas, we are adding it to pages and posts, see the WordPress documentation
			 * for more information about the add_meta_box function */
			function PostCategoriesMetaboxAddMetabox() {
				add_meta_box('metabox_postcategories', 'Display Post List on Bottom', array($this,'PostCategoriesMetabox'), 'page','side','high');
			}
	
			/* this function takes all of our form fields and saves them when the post/page is saved, or updates them if need be
			 * currently it is not very dynamic, we have to update each field using static code from here, that will need fixed in
			 * the future to make this code more flexible */
			function PostCategoriesMetaboxSavePost($post_id) {
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  {
						return;
				}
				
				if ( isset( $_POST ) && $_POST) {
					$values = $this->PostCategoriesMetaboxValues('metabox_postcategories');
					
					foreach( $values as $key => $val )
						update_post_meta($post_id, $key, $this->PostCategoriesGlobalsStripCrl($_POST[ $key ]));
				}
			}
	
			/* this function strips certain characters from the values */
			function PostCategoriesGlobalsStripCrl($value) {
				if (is_array($value)) {
						foreach ($value as $key => $val) {
					$value[$key] = $this->PostCategoriesGlobalsStripCrl($val);
						}
				} elseif (is_string($value)) {
						$value = str_replace(chr(13).chr(10), chr(10), $value);
						//$value = str_replace('"',"'",$value);//this replaces double quotes with single, it was needed for the Facebook Social sharing features, not here necessarily
				}
				return $value;
			}
	
			/* this function will build out all of the metabox form items by calling the other functions
			 * that build the individual form inputs, textareas, and dropdown boxes. In this class we have
			 * only included the select box constructer since it is the only one used in this metabox
			 * see the EN theme 3 functions.php if we need any of the other constructers */
			function PostCategoriesMetaboxUIBuild($options) {
				?>
				<style>
					#metabox_metabox_postcategories__backgroundcontent_ div.span4 h3 {
						border-bottom: 0;
					}
					#metabox_metabox_postcategories__backgroundcontent_ div.span4 {
						height: 0;
					}
				</style>
				<?php
				foreach ($options as $option)
				{
					$callback = 'PostCategoriesUI'.$option['type'];
				?>
					<div class="section" id="<?php echo str_replace(array('[',']'),array('_','_'),$option['name']);?>">
						<div class="row-fluid">
								<div class="span4">
							<h3><?php echo $option['text']; ?></h3>
							<div class="desc">
									<?php echo $option['hint']; ?>
							</div>
								</div>
								<div class="span7 offset1">
								<?php
									$this->$callback($option);
								?>
								</div>
						</div>
					</div>
			<?php
				}
			?>
	
			<?php
			}
			/* this function builds the selectbox form input for the metabox, other constructers are available in the theme 3 functions.php file */
			function PostCategoriesUIselectbox($option) {
				//$this->ItemDebug($option);
			?>
				<div class="control">
			<?php
				echo '<select name="'.$option['name'].'">';
						if(count($option['items']) < 1) {
					global $current_user;
					get_currentuserinfo();
					$option['items'][0] = $current_user->display_name;
						}
						foreach ($option['items'] as $key => $item) {
					if($option['value']==$key) $selected = 'selected';
					else $selected = '';
					echo '<option '.$selected.' value="'.$key.'">'.$item.'</option>';
						}
				echo '</select>';
			?>
				</div>
			<?php
			}
			
			/* this function builds the checkbox form input for the metabox, other constructers are available in the theme 3 functions.php file */
			function PostCategoriesUIcheckbox( $option ) {
			?>
				<div class="control">
			<?php
				echo '<input type="hidden" name="' . $option['name'] . '" value="false" />';
				
				echo '<input type="checkbox" name="' . $option['name'] . '" value="true"' . ( $option['value'] == 'true' ? 'checked':'' ) . ' />';
			?>
				</div>
			<?php
			}
			
			function PostCategoriesUInumberbox( $option ) {
			?>
				<div class="control">
			<?php
				echo '<input type="number" name="' . $option['name'] . '" value="' . $option['value'] . '" />';
			?>
				</div>
			<?php
			}
}
?>