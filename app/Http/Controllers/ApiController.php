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
        return File::find($id);
    }
}
