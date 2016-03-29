<?php

use Hercules\View;

class HercView_PostsList extends View
{
    function __construct()
    {
        $this->directory = dirname( __FILE__ );
        $this->name = 'Display Post List';
        $this->type = 'post-add-on';
        $this->class_name = __CLASS__;
        $this->location = 'after';
        $this->model = 'post-settings';

        $this->IncludeBootstrap();

        parent::__construct();
    }

	/**
	 * Generate data for rendering the post list, first do the parent data render then do the query to get the posts list based on settings for the current post.
	 */
    function GenerateData()
    {
        parent::GenerateData();

		if( !property_exists( $this, 'post_list_generated' ) || empty( $this->post_list_generated ) )
		{
			if( property_exists( $this, 'data' ) && !empty( $this->data[ 'show_herc_posts_list' ] ) )
			{
				global $post;

				$category_id = $this->data[ 'post_category' ];

				$posts_per_page = is_numeric( $this->data[ 'posts_to_show' ] ) ? $this->data[ 'posts_to_show' ] : 10;

				$page = isset( $_GET[ 'pg' ] ) && is_numeric( $_GET[ 'pg' ] ) ? $_GET[ 'pg' ] : 1;

				$args = array(
					'cat' => $category_id,
					'paged' => $page,
					'posts_per_page' => ( $posts_per_page > 0 ? $posts_per_page : -1 ),
					'post__not_in' => array( $post->ID )
				);

				$shons_query = new WP_Query( $args );

				if( $shons_query->have_posts() ):

					if( $page > $shons_query->max_num_pages )
						$page = $shons_query->max_num_pages;

					$pagination = array(
						'base' => @add_query_arg( 'pg', '%#%' ),
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

					$this->data[ 'pagination' ] = paginate_links( $pagination );

					$this->data[ 'posts' ] = array();

					while( $shons_query->have_posts() ):

						$shons_query->the_post();

						global $post;

						$excerpt = $this->Helper( 'string' )
							->LimitText( ( empty( $post->post_excerpt ) ? strip_tags( $post->post_content ) : $post->post_excerpt ), 100 );

						if( isset( $this->data[ 'max_excerpt_length' ] ) && is_numeric( $this->data[ 'max_excerpt_length' ] ) && $this->data[ 'max_excerpt_length' ] > 0 )
							$excerpt = $this->Helper( 'string' )
								->LimitText( $excerpt, $this->data[ 'max_excerpt_length' ] );

						$this->data[ 'posts' ][] = array(
							'permalink' => get_the_permalink(),
							'title' => $post->post_title,
							'excerpt' => $excerpt
						);

					endwhile;
				endif;

				$this->post_list_generated = true;
			}
		}
    }
}