<?php
namespace App\Core;

class Controller
{
    /**
     * View Controller
     * @return Pageview
     * @return Array $data
     */
    public function view(String $view, $data = null){
        require VPATH.$view.'.php';

    }
    
    /**
     * Model Controller
     * @return Model
     */
    public function model(String $model){
        require MPATH.$model.'.php';
        return new $model;
    }
}
