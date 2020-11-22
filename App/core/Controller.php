<?php
namespace App\Core;

class Controller
{
    /**
     * View Controller
     * @return Pageview
     * 
     * @param View View Page
     * @param Array $data
     */
    public function view(String $view, $data = null){

        $section['header'] = $this->section('home/header');
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

    /**
     * Section Controller
     * @return Content
     */
    public function section(String $view)
    {
        /** Validation */
        if ( !file_exists(VPATH.$view.".php") )
        {
            return "File No Existing";
        }


        /** Geting Content */
        return file_get_contents(VPATH.$view.".php");
    }
}
