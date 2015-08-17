<?php
/*
Plugin Name: Hercules Posts List
Description: Adds a list of posts in a category to the bottom of a page with a title of the same name as the category
Version: 1.1
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) )
{
    echo "Oops! No direct access please :)";
    exit;
}

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'start.php' );

$herc_posts = new HercFramework;