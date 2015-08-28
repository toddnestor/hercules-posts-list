<?php
/**
 * Created by PhpStorm.
 * User: Todd
 * Date: 7/21/2015
 * Time: 2:29 PM
 */

class HercModel extends HercAbstract
{
    function __construct()
    {
        $this->class_name = empty( $this->class_name ) ? __CLASS__ : $this->class_name;
    }

    function RegisterPostMetaSave()
    {
        $this->Helper( 'tools' )->DebugIt( 'your mom!' );
    }

    function Initialize()
    {
        //$this->Helper( 'tools' )->DebugIt( $this->CurrentSlug() );
        if( $this->View( $this->CurrentSlug() )->type == 'metabox' && !empty( $this->metabox_positions ) )
            $this->RegisterPostMetaSave();
    }
}