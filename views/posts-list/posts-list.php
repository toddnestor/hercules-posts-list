<?php

class HercView_PostsList extends HercView
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

    function GenerateData()
    {
        parent::GenerateData();

        if( property_exists( $this, 'data' ) && !empty( $this->data['show_herc_posts_list'] ) )
        {
            global $post;

            $category_id = $this->data['post_category'];

            $posts_per_page = is_numeric( $this->data['posts_to_show'] ) ? $this->data['posts_to_show'] : 10;

            $page = isset( $_GET['pg'] ) && is_numeric($_GET['pg']) ? $_GET['pg'] : 1;

            $args = array(
                'cat' => $category_id,
                'paged' => $page,
                'posts_per_page' => ( $posts_per_page > 0 ? $posts_per_page : -1 ),
                'post__not_in' => array( $post->ID )
            );

            $shons_query = new WP_Query($args);

            $category_list = '';

            if ($shons_query->have_posts()):

                if ($page > $shons_query->max_num_pages)
                    $page = $shons_query->max_num_pages;

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

                $pagination_links = paginate_links($pagination);

                $category_list .= $pagination_links;

                while ( $shons_query->have_posts() ):

                    $shons_query->the_post();

                    $category_list .= '<h2><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';

                    $excerpt = isset( $this->data['max_excerpt_length']) && is_numeric( $this->data['max_excerpt_length'] ) && $this->data['max_excerpt_length'] > 0 ? $this->Helper('string')->LimitText( get_the_excerpt(), $this->data['max_excerpt_length'] ) : get_the_excerpt();

                    $category_list .= $this->data['show_herc_posts_list_excerpt'] == 'true' ? '<p>' . $excerpt . ' <a href="' . get_the_permalink() . '">Read more</a></p>' : '';

                endwhile;

                $category_list .= $pagination_links;

                echo $category_list;
            endif;
        }
    }

    function PostFilter( $content )
    {
        return parent::PostFilter( $content );
    }
}