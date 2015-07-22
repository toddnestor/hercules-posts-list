<?php
/**
 * Created by PhpStorm.
 * User: Todd
 * Date: 7/21/2015
 * Time: 2:00 PM
 */

class HercHelper_Tools extends HercHelper
{
    function __construct()
    {
        add_filter( 'the_content', array( $this, 'Testing' ) );
    }

    function Testing()
    {
        $this->DebugIt( $this->Helper( 'handlebars' )->TestingIt() );
    }

    /**
     *
     */
    function DebugIt()
    {
        $args = func_get_args();

        if( count( $args ) > 0 )
        {
            echo "\n<pre>";

            foreach ($args as $key => $val)
            {
                echo "\n\n========== Degugging Item " . ($key + 1) . " ==========\n\n";
                var_dump($val);
            }

            echo "\n\n========== END OF DEBUGGING ==========";
            echo "\n</pre>\n\n";
        }
    }

    function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }
}