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

if( !function_exists("BottomOfPosts"))
{
	/*	add our filter function to the hook */
	add_filter('the_content', 'ShonsBottomOfPosts');
	
	function ShonsBottomOfPosts($content)
	{
		if(is_page())
		{
			$category_id = get_cat_ID(get_the_title());
			$args = array(
				'cat'=>$category_id,
			);
			$shons_query = new WP_Query( $args );
			
			$category_list = '';
			if ( $shons_query->have_posts() ):
			while ( $shons_query->have_posts() ):
			$shons_query->the_post();
			
			$category_list .= '<h1><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h1><p> ' . get_the_content() . '</p>';
			
			endwhile; endif;
			return $content . $category_list;
			wp_reset_postdata();
		}
		return $content;
	}
}


?>
