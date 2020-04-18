<?php
namespace App\Core;

class Autoload 
{
    /**
     * Model Directory
     * @var array
     * 
     */
    protected $_modelDirectoryPath = array();

    /**
     * View Directory
     * @var array
     * 
     */
    protected $_viewDirectoryPath = array();

    /**
     * Controller Directory
     * @var array
     * 
     */
    protected $_controllerDirectoryPath = array();

    /**
     * Library Directory
     * @var array
     * 
     */
    protected $_libraryDirectoryPath = array();

    /**
     * Constructor
     * Constanst contain full path to Model, View, Controller, Library
     * Directories.
     * 
     * @Constant MPATH, VPATH, CPATH, LPATH
     */

    public function __construct()
    {
        $this->modelDirectoryPath = MPATH;
        $this->viewDirectoryPath = VPATH;
        $this->controllerDirectoryPath = CPATH;
        $this->libraryDirectoryPath = LPATH;

        spl_autoload_register(array($this,'load_controller'));
        spl_autoload_register(array($this,'load_model'));
        spl_autoload_register(array($this,'load_library'));

        // log_message('debug','Loader Class Initialized');
        echo "Loader Class Initalized";

    }

    /**
     * -------------------------------------------------------
     * Load Library
     * -------------------------------------------------------
     * Method for library.
     * This method return class objec.
     * 
     * @library String
     * @param String
     */
    public function load_library($library, $param = null)
    {
        if(is_string($library))
        {
            return $this->initialize_class($library);
        }
        
        if(is_array($param))
        {
            foreach ($library as $d) {
                $this->initialize_class($library);
            }
        }
    }

    /**
     * -------------------------------------------------------
     * Initialize Class
     * -------------------------------------------------------
     * Method for initialize class
     * This method return new object
     * This method can initialize more class using array
     * 
     * $library String|Array
     * @param String
     */
    public function initialize_class($library)
    {
        try{
            if(is_array($library)){
                foreach ($library as $class) {
                    $arrayObject = new $class;
                }
                return $this;
            }
            if(is_string($library)){
                $stringObject = new $library;
            }else{
                throw new ISException("Class name must be string");
            }

            if(is_null($library))
            {
                throw new ISException("You must enter the name of the class");
            }

        }catch(Exception $e){
            echo $e;
        }
    }

    /**
     * Autoload Controller Class
     * 
     * @param String $class
     * @return Object
     */

    public function load_controller($controller)
    {
        if($controller)
        {
            set_include_path($this->controllerDirectoryPath);
            spl_autoload_extensions('.php');
            spl_autoload($class);
        }
    }

    /**
     * Autoload Model Class
     * 
     * @param String $class
     * @return Object
     */
    public function load_model($model){
        if($model)
        {
            set_include_path($this->modelDirectoryPath);
            spl_autoload_extensions('.php');
            spl_autoload($class);
        }
    }

    /**
     * Autoload Library Class
     * 
     * @param String $class
     * @return Object
     * 
     */
    // public function load_library($library)
    // {
    //     if($library){
    //         set_include_path($this->controllerDirectoryPath);
    //         spl_autoload_extensions('.php');
    //         spl_autoload($class);
    //     }
    // }
    
}
