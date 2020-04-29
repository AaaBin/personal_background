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
        // 將上傳檔案儲存成變數
        $file = $request->file('file');
        // 存進s3
        $path = Storage::disk("s3")->put('personal_backend', $file);
        // 取得檔案名稱
        $file_name = explode('/',$path)[1];
        // 取得檔案網址
        $file_url = Storage::disk('s3')->url($path);

        // 建立新資料
        $file_data = new File;
        $file_data->title = $request_data['title'];
        $file_data->file_name = $file_name;
        $file_data->description = $request_data['description'];
        $file_data->file_url = $file_url;
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
        // 檔案下載
        // 將要下載的檔案資料存成變數
        $file_data = File::find($id);
        // 抓出儲存時的檔案名稱
        $file_name = $file_data->file_name;
        // 判斷是否存在這一檔案
        $exists = Storage::disk('s3')->exists("personal_backend/$file_name");
        if ($exists == true) {
            // 下載檔案
            return Storage::disk("s3")->download("personal_backend/$file_name",$file_name);
        }
        // 若找不到檔案，導回file/index頁並夾帶錯誤訊息通知
        return redirect('/file')->with("error","this file is not exist");
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
        $request_data = $request->all();
        $file_data = File::find($request_data['id']);
        $file_name = $file_data->file_name;
        $file_content = $request_data["json"];
        $file_url = $file_data->file_url;

        // error 不能透過HTTP寫入檔案
        // 用FTP?
        $file = fopen("$file_url","r+");
        fwrite($file,$file_content);

        dd(file_get_contents($file_url));

        $file_data->save();

        return redirect('/file');

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
