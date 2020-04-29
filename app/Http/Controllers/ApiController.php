<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function index()
    {
        return File::all();
    }
    public function show($id)
    {
        $file_data = File::find($id);
        $file_name = $file_data->file_name;
        $content = Storage::disk('s3')->get("personal_backend/$file_name");

        return response("$content");
    }
}
