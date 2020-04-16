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
        return redirect("storage/$json");
    }
}
