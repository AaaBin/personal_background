<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return File::all();
    }
    public function show($id)
    {
        $file_data = File::find($id);
        $json = $file_data->file;
        $content = file_get_contents("storage/$json");
        $file = fopen("storage/$json","r");
        $r_file = fread($file,1000);
        // return redirect("storage/$json");
        return response("$r_file");
    }
}
