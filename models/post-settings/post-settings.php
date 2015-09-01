<?php

class HercModel_PostSettings extends HercModel
{
    function __construct()
    {
        $this->class_name = __CLASS__;
        $this->directory = dirname( __FILE__ );

        parent::__construct();
    }
}