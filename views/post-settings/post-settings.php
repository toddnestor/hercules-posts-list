<?php

class HercView_PostSettings extends HercView
{
    function __construct()
    {
        $this->directory = dirname( __FILE__ );
        $this->name = 'Display Post List';
        $this->type = 'metabox';
        $this->metabox_positions = array(
            array(
                'post_type' => 'page',
                'position' => 'side',
                'priority' => 'high'
            ),
            array(
                'post_type' => 'post',
                'position' => 'side',
                'priority' => 'high'
            ),
        );

        parent::__construct();
    }
}