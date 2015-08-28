<?php

class HercView_PostSettings extends HercView
{
    function __construct()
    {
        $this->directory = dirname( __FILE__ );
        $this->name = 'Display Post List';
        $this->type = 'metabox';
        $this->class_name = __CLASS__;
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

        $this->IncludeBootstrap();

        $this->data = array(
            'categories' => $this->GetTheCategoryArray()
        );

        parent::__construct();
    }

    function GetTheCategoryArray()
    {
        $select_box_options = array();

        $category_list = get_categories( array() );

        foreach ($category_list as $key => $val)
        {
            $select_box_options[] = array(
                'id' => $val->term_id,
                'name' => $val->name . ' (' . $val->count . ')'
            );
        }

        return $select_box_options;
    }
}