<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cus_lib\Lib_common;

class BackToolController extends Controller
{
    
    public function ajax_get_category( Request $request  ){
        
        if( isset($request['cat_id']) )
        {
        	$request['cat_id'] = intval($request['cat_id']);

        	$datas  = Lib_common::GetSpecificCategorys($request['cat_id']);
            
            echo json_encode($datas);

        }
    
    }    
}
