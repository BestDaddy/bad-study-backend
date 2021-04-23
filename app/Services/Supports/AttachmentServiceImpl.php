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
    public function __construct(Attachment $model)
    {
        parent::__construct($model);
    }

    public function save($model_id, $model_type, $file, $uuid = null, $folder = null, $disk = 'public')
    {
        if($disk == 'public_build')
            $folder = 'Build/'. $folder;
        $fullpath = $this->storeFile($file, $folder, $disk);

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

    public function storeFile($file, $folder, $disk)
    {
        $filename =  $folder . '/' .$file->getClientOriginalName();
        Storage::disk($disk)->put($filename,  File::get($file));
        return $filename;
    }

    public function download($id){
        $attachment = Attachment::findOrFail($id);
        $path = $attachment->path;
        if(Storage::disk('public')->exists($path))
            return response()->download(storage_path('app/public/' . "{$path}"));
        elseif(Storage::disk('public_build')->exists($path))
            return response()->download(public_path("{$path}"));
        else
            return response('File not found');
    }

    public function deleteFile($model_type, $model_id){
        $files = Attachment::where('model_type', $model_type)->where('model_id', $model_id)->get();
        foreach ($files as $file){
            Storage::disk('public_build')->delete($file->name);
        }
        Attachment::where('model_type', $model_type)->where('model_id', $model_id)->delete();
    }
}
