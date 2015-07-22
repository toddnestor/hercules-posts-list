<?php
/**
 * Created by PhpStorm.
 * User: Todd
 * Date: 7/21/2015
 * Time: 6:00 PM
 */

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Handlebars' . DIRECTORY_SEPARATOR . 'Autoloader.php' );

Handlebars\Autoloader::register();

use Handlebars\Handlebars;

class HercHelper_Handlebars extends HercHelper
{
    function __construct()
    {
        //add_filter( 'the_content', array( $this, 'TestingIt' ) );
    }

    function Initialize()
    {
        if( empty( $this->handlebars_object ) )
        {
            $this->handlebars_object = new Handlebars;
        }

        return $this->handlebars_object;
    }

    function TestingIt()
    {
        return $this->Initialize()->render(
            'Planets:<br />{{#each planets}}<h6>{{this}}</h6>{{/each}}',
            array(
                'planets' => array(
                    "Mercury",
                    "Venus",
                    "Earth",
                    "Mars"
                )
            )
        );
    }
}