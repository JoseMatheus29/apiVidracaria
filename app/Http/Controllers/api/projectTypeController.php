<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\projectType;
use App\Models\project;

class projectTypeController extends Controller
{
    public function listProjectTypeAll(){
        try{
            $projectType = projectType::all();
            return $projectType;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function registerCategory(Request $request){
        try{
            $category = new projectType();
            $category->name = $request->name;
            
            $category->save();
            return ['status' => 'ok'];
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
