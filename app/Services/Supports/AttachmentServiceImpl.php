<?php


namespace App\Services\Supports;


use App\Models\Attachment;
use App\Services\BaseServiceImpl;
use App\Services\Supports\AttachmentService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentServiceImpl extends BaseServiceImpl implements AttachmentService
{
//    private $main_dir = 'attachments';

    public function __construct(Attachment $model)
    {
        parent::__construct($model);
    }

//    public function setDir($dir_name)
//    {
//        $this->main_dir = $dir_name;
//    }

    public function save($model_id, $model_type, $file, $uuid = null, $slug = null, $folder = null)
    {

        $fullpath = $this->storeFile($file, $folder);

        $data['path'] = $fullpath;
        $data['name'] = $file->getClientOriginalName();
        $data['model_id'] = $model_id;
        $data['user_id'] = Auth::user()->id;
        $data['model_type'] = $model_type;
        $data['uuid'] = $uuid;
        $attachment = Attachment::create($data);
        return $attachment;
    }

    public function move($uuid, $new_model_id)
    {
        $models = $this->getWhere([
            'model_id' => 0,
            'uuid' => $uuid
        ]);

        foreach ($models as $model) {
            $model->model_id = $new_model_id;
            $model->uuid = null;
            $model->save();
        }
    }

    public function storeFile($file, $folder)
    {
        $filename =  $folder . '/' .$file->getClientOriginalName();
        Storage::disk('public_build')->put($filename,  File::get($file));
        return 'Build/'. $filename;


    }

    public function download($id){
        $attachment = Attachment::findOrFail($id);
        $path = $attachment->path;
//        $path = str_replace('/storage/','', $path);
        return response()->download(public_path("{$path}"));
    }

    public function deleteFile($model_type, $model_id){
        $files = Attachment::where('model_type', $model_type)->where('model_id', $model_id)->get();
        foreach ($files as $file){
            Storage::disk('public_build')->delete($file->name);
        }
        Attachment::where('model_type', $model_type)->where('model_id', $model_id)->delete();
    }
}
