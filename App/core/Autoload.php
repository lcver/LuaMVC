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
        $this->_modelDirectoryPath = MPATH;
        $this->_viewDirectoryPath = VPATH;
        $this->_controllerDirectoryPath = CPATH;
        $this->_libraryDirectoryPath = LPATH;
        $this->_coreDirectoryPath = CORE_PATH;

        // $this = explode('\\',$core);
        // $core = end($core);

        spl_autoload_register(array($this,'load_core'));
        spl_autoload_register(array($this,'load_controller'));
        spl_autoload_register(array($this,'load_model'));
        spl_autoload_register(array($this,'load_library'));
        

        // log_message('debug','Loader Class Initialized');
        // echo "Loader Class Initalized";
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
    public function load($library, $param = null)
    {
        if(is_string($library))
        {
            return $this->initialize_class($library);
        }
        
        if(is_array($param))
        {
            foreach ($library as $d) {
                return $this->initialize_class($library);
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
    public function initialize_class($library=null)
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
                throw new Exception("Class name must be string");
            }

            if(is_null($library))
            {
                throw new Exception("You must enter the name of the class");
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
            $controller = explode('\\',$controller);
            $controller = end($controller);

            if(file_exists($this->_controllerDirectoryPath.$controller.'.php'))
                include $this->_controllerDirectoryPath.$controller.'.php';
            // echo "controller";
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
            $model = explode('\\',$model);
            $model = end($model);

            if(file_exists($this->_modelDirectoryPath.$model.'.php'))
                include $this->_modelDirectoryPath.$model.'.php';
            // echo "model";
        }
    }

    /**
     * Autoload Library Class
     * 
     * @param String $class
     * @return Object
     * 
     */
    public function load_library($library)
    {
        if($library){
            $library = explode('\\',$library);
            $library = end($library);

            if(file_exists($this->_libraryDirectoryPath.$library.'.php'))
                include $this->_libraryDirectoryPath.$library.'.php';
        }
    }

    public function load_core($core)
    {
        if($core)
        {
            $based = str_replace(APPPATH,'',CORE_PATH);
            $based = preg_replace('/core/i','Core',$based);

            $core = str_replace("\\","/",$core);
            $core = str_replace($based,'',$core);
            
            if(file_exists($this->_coreDirectoryPath.$core.'.php'))
                include $this->_coreDirectoryPath.$core.'.php';
        }
    }
    
}
