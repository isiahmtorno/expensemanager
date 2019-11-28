<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $params;

    private $css_files;
    private $js_files;
        
    public function __construct(){
        
        $this->params = array();
            
        $this->css_files = array(
        	'bootstrap.min.css',
        	'icons/fontawesome/all.min.css',
            'icons/font-awesome/font-awesome.css',
        	'icons/themify-icons/themify-icons.css',
        	'extras.min.css',
        	'style.min.css',
        	'app.css'
        );
        
        $this->js_files = array(
        	'jquery.min.js',
        	'bootstrap.min.js',
            'bootbox.all.min.js',
            'extras.min.js',
        	'style.js',
        	'app.js'
        );


    }

    protected function load_header_files(){
        $include_css = '';
        $include_js = '';
        $include_plugin_css = '';
        $include_plugin_js = '';

        foreach($this->css_files as $path){
            $include_css .= sprintf('    <link href="%s" media="screen" rel="stylesheet" type="text/css" />%s',
                asset('css/'.$path), "\n");
        }

        foreach($this->js_files as $path){
            $include_js .= sprintf('    <script type="text/javascript" src="%s"></script>%s',
                asset('js/'.$path), "\n");
        }

        $this->params['css_files'] = $include_css;
        $this->params['js_files'] = $include_js;
    }

    
    protected function add_styles($values){
        $this->css_files = array_merge($this->css_files, (array)$values);
    }

    protected function add_scripts($values){
        $this->js_files = array_merge($this->js_files, (array)$values);
    }
}
