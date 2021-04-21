<?php


namespace App\Services\Supports;


interface AttachmentService
{
//    public function setDir($dir_name);

    /**
     * @param $model_id
     * @param $model_type
     * @param $file
     * @param null $uuid
     * @param null $slug
     */
    public function save($model_id, $model_type, $file, $uuid = null, $slug = null, $folder=null);

    /**
     * @param $uuid
     * @param $new_model_id
     */
    public function move($uuid, $new_model_id);

    public function storeFile($file, $folder);

    public function download($id);

    public function deleteFile($model_type, $model_id);
}
