<?php
namespace App\Core;

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->set_url();
        // var_dump($url);

        /**
         * Controller
         * Check file exist.
         * 
         * @return Controller
         */
        $this->controller = $this->controller."Controller";

        if(isset($url[0])){
            if(file_exists(CPATH.$url[0].'.php'))
            {
                $this->controller = $url[0];
                unset($url[0]);
            }
        }
        // var_dump($this->controller);

        $this->controller = new $this->controller;

        /**
         * Methods
         * Check method exist
         * 
         * @return Method
         */
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
                $this->method = $url[1];
                unset($url[1]);
        }
        // var_dump($this->method);

        /**
         * Params
         * Check params exist
         * 
         * @return Params
         */
        if(!empty($url))
            $this->params = array_values($url);
        
        // var_dump(array_values($url));
        
        call_user_func([$this->controller,$this->method], $this->params);
    }

    public function set_url()
    {
        if(isset($_GET['url']))
        {
            /**
             * Get URL filter.
             * @return array
             */
            $url = $_GET['url'];
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            /**
             * Set captialization index 0
             * 
             */
            $url[0] = ucwords($url[0]);

            return $url;
        }
    }
}
