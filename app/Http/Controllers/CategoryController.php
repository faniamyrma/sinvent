<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class CategoryController extends Controller
{
    public function index(){
       //mengakses record table kategori semua record
       $rsetCategory = kategori::all();
       return $rsetCategory[0]->deskripsi; //-> mengammbil nama property
        
    }
    
}
