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
    }
}