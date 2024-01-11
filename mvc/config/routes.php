<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    spl_autoload_register(function($className) {
        if ( strpos($className, 'CI_') !== 0 ) {
            $file = APPPATH . 'libraries/' . $className . '.php';
            if ( file_exists($file) && is_file($file) ) {
                @include_once( $file );
            }
        }
    });
    $route['book/(:num)'] = "Book/index/$1";
    $route['invoice/(:num)'] = "Invoice/index/$1";
    $route['invoice/(:num)/(:any)'] = "Invoice/index/$1/$2";
    $route['other_charges/(:num)'] = "Other_charges/index/$1";
    $route['other_charges/(:num)/(:any)'] = "Other_charges/index/$1/$2";
    $route['version']            = 'app/version';
    $route['default_controller'] = 'signin/index';