<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $file_data = File::all();

        return view('file/index', compact('file_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all();
        $file_path = $request->file("file")->store('file', 'public'); //將檔案進行儲存，並抓到路徑
        $request_data['file'] = $file_path;
        $type = explode('.',$file_path)[1];
        $file_data = new File;
        $file_data->title = $request_data->title;
        $file_data->description = $request_data->description;
        $file_data->type = $type;
        $file_data->file = $file_path;
        $file_data->save();
        return redirect('/file');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file_data = File::find($id);
        $file = public_path("storage\\$file_data->file");
        $header = $file_data->type;
        return response()->download($file, $file_data->title,$header);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file_data = File::find($id);
        $file_path = $file_data->file;
        Storage::disk('public')->delete("$file_path");
        $file_data->delete();
        return $file_data;
    }
}
