<?php
/*
Plugin Name: Hercules Posts List
Author: Todd Nestor
Author URI: http://toddnestor.com
Description: Adds a list of posts in a category to the bottom of a page with a title of the same name as the category
Version: 1.2.3
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) )
{
    echo "Oops! No direct access please :)";
    exit;
}

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'start.php' );

$var_name = 'herc_posts_list';

global $$var_name;
$$var_name                   = new \Hercules\Framework;
$$var_name->plugin_directory = dirname( __FILE__ );

$$var_name->InitiateAll();