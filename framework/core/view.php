<?php

class HercView extends HercAbstract
{
    function __construct()
    {
        $this->template = empty( $this->template ) ? 'template.php' : $this->template;
        $this->name = empty( $this->name ) ? '' : $this->name;

        $this->Initialize();
    }

    function Render()
    {
        if( file_exists( $this->directory . DIRECTORY_SEPARATOR . $this->template ) )
        {
            $template = file_get_contents($this->directory . DIRECTORY_SEPARATOR . $this->template);

            echo $this->Helper( 'handlebars' )->Render( $template );
        }
    }

    function EnqueueScript( $script )
    {

    }

    function EnqueueStyleSheet( $style, $handle = '' )
    {
        wp_enqueue_style( ( empty( $handle ) ? __CLASS__ . '_' . sanitize_title( $style ) : $handle ), $this->GetUrl( $style ) );
    }

    function EnqueueBootstrap()
    {
        $this->EnqueueStyleSheet( 'framework/assets/css/bootstrap.css', sanitize_title( $this->GetPluginFolderName() . '_bootstrap' ) );
    }

    function IncludeBootstrap()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'EnqueueBootstrap' ) );
        add_action( 'admin_print_styles', array( $this, 'EnqueueBootstrap' ) );
    }

    function RegisterMetaboxes()
    {
        foreach( $this->metabox_positions as $key=>$val )
        {
            if( !empty( $val['post_type'] ) )
            {
                if( empty( $val['position'] ) )
                    $val['position'] = 'normal';
                if( empty( $val['priority'] ) )
                    $val['position'] = 'default';

                add_meta_box( 'metabox_' . __CLASS__, $this->name, array( $this, 'Render' ), $val['post_type'], $val['position'], $val['priority'] );
            }
        }
    }

    function Initialize()
    {
        if( $this->type = 'metabox' && !empty( $this->metabox_positions ) )
            add_action( 'add_meta_boxes', array( $this, 'RegisterMetaboxes' ) );
    }
}