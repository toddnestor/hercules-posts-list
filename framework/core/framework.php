<?php
/**
 * Created by PhpStorm.
 * User: Todd
 * Date: 7/21/2015
 * Time: 2:29 PM
 */

class HercFramework
{
    function __construct()
    {
        $this->InitiateAll();
    }

    /**
     * Returns an instance of a helper object.
     * @param String $helper Name of the helper to instantiate.
     * @param bool|false $new Set to true to return a new instance and not use a previous instance.
     * @return mixed instance of the class that was previously created, or a new one if need be.
     */
    function Helper( $helper, $new = false )
    {
        return $this->InitiateClass( 'helper', $helper, $new );
    }

    /**
     * Returns an instance of a model object.
     * @param String $model Name of the model to instantiate.
     * @param bool|false $new Set to true to return a new instance and not use a previous instance.
     * @return mixed instance of the class that was previously created, or a new one if need be.
     */
    function Model( $model, $new = false )
    {
        return $this->InitiateClass( 'model', $model, $new );
    }

    /**
     * Returns an instance of a controller object.
     * @param String $controller Name of the controller to instantiate.
     * @param bool|false $new Set to true to return a new instance and not use a previous instance.
     * @return mixed instance of the class that was previously created, or a new one if need be.
     */
    function Controller( $controller, $new = false )
    {
        return $this->InitiateClass( 'controller', $controller, $new );
    }

    /**
     * Returns an instance of a view object.
     * @param String $view Name of the view to instantiate.
     * @param bool|false $new Set to true to return a new instance and not use a previous instance.
     * @return mixed instance of the class that was previously created, or a new one if need be.
     */
    function View( $view, $new = false )
    {
        return $this->InitiateClass( 'view', $view, $new );
    }

    function UpperCamelCaseIt( $string )
    {
        return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $string ) ) );
    }

    /**
     * Returns an instance of a class based on what type it is and what its slug is.
     * @param String $type This can be helper, model, controller, or view depending on what kind of object you are looking for.
     * @param String $class This is the slug, basically the part before the ".php" in the file name.
     * @param bool|false $new Set to true to return a new instance and not use a previous instance.
     * @return mixed instance of the class that was previously created, or a new one if need be.
     */
    function InitiateClass( $type, $class, $new = false )
    {
        $class = strtolower( $class );

        switch( $type )
        {
            case 'helper':
                $folder = 'helpers';
                $file = 'helper.php';
                $class_prefix = 'Helper';
                break;
            case 'model':
                $folder = 'models';
                $file = 'model.php';
                $class_prefix = 'Model';
                break;
            case 'controller':
                $folder = 'controllers';
                $file = 'controller.php';
                $class_prefix = 'Controller';
                break;
            case 'view':
                $folder = 'views';
                $file = 'view.php';
                $class_prefix = 'View';
                break;
        }

        require_once( $file );

        if( file_exists( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR . $class . '.php' ) )
            require_once( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR . $class . '.php' );
        elseif( file_exists( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR . $class . '.php' ) )
            require_once( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR . $class . '.php' );
        else
            return false;

        $object = 'Herc' . $class_prefix . '_' . $this->UpperCamelCaseIt( $class );

        if( $new )
            return new $object;

        if( empty( $this->$object ) )
            $this->$object = new $object;

        return $this->$object;
    }

    /**
     * This function goes through all the objects in the framework and the main plugin it is used in and creates an instance of each one.
     *
     * This function ensures any code that needs to be run at initiation gets run.  So you can do an add_action or
     * add_filter in the constructor of any object.
     */
    function InitiateAll()
    {
        $object_types = array(
            'helper',
            'model',
            'controller',
            'view'
        );

        foreach( $object_types as $key=>$val )
        {
            $directories = array(
                dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . $val . 's',
                dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . $val . 's'
            );

            foreach( $directories as $key2=>$val2 )
            {
                if( is_dir( $val2 ) )
                {
                    $files = scandir( $val2 );

                    foreach( $files as $key3=>$val3 )
                    {
                        if( strpos( $val3, '.' ) === false )
                            $this->InitiateClass( $val, $val3 );
                    }
                }
            }
        }
    }
}