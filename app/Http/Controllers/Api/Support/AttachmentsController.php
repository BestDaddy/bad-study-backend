<?php


namespace App\Http\Controllers\Api\Support;


use App\Http\Controllers\ApiBaseController;

use App\Http\Requests\Api\Support\AttachmentStoreApiRequest;
use App\Models\ExerciseResult;
use App\Services\Supports\AttachmentService;
use Illuminate\Http\Request;

class AttachmentsController extends ApiBaseController
{
    private $attachmentService;
    const COMMON_EXTENSIONS = 'odt,ods,png,jpg,jpeg,rar,zip,doc,docx,DOCX,xls,xlsx,pdf,ppt,pptx,gif,txt,mp4';
    private $attachments = [
        'result' => [
            'model' => ExerciseResult::class,
            'rights' => [],
            'disk' => 'public',
        ],
    ];
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function store(AttachmentStoreApiRequest $request)
    {
        $model_id = $request->input('model_id', 0);
        $model_type = $request->input('model_type');
        $model_uuid = $request->input('uuid', null);
        $folder = $request->input('folder', null);

        if (!isset($this->attachments[$model_type])) {
            abort(404);
        }

        $model_params = $this->attachments[$model_type];
        $model_name = $model_params['model'];
        $disk = $model_params['disk'];
        // расширения
//        $extensions = isset($model_params['extensions'])
//            ? implode(",", $model_params['extensions'])
//            : self::COMMON_EXTENSIONS;
//
//        $request->validate([
//            'file' => 'mimes:' . $extensions,
//        ]);

        $data = $this->attachmentService->save(
            $model_id,
            $model_name,
            $request->file('file'),
            $model_uuid,
            $folder,
            $disk
        );

        $response = [
            'success' => true,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function storeMultiple(AttachmentStoreApiRequest $request){
        $model_id = $request->input('model_id', 0);
        $model_type = $request->input('model_type');
        $model_uuid = $request->input('uuid', null);
        $folder = $request->input('folder', null);
        if (!isset($this->attachments[$model_type])) {
            abort(404);
        }
        $model_params = $this->attachments[$model_type];
        $model_name = $model_params['model'];
        $disk = $model_params['disk'];
        $files = $request->file('file');
        $data =[];
        foreach ($files as $file){
            array_push ($data , $this->attachmentService->save(
                $model_id,
                $model_name,
                $file,
                $model_uuid,
                $folder,
                $disk
            ));
        }
        $response = [
            'success' => true,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function download($id)
    {
        return $this->attachmentService->download($id);
    }
}
