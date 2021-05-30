@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    {{ Breadcrumbs::render('chapters.show', $chapter) }}
    <h2>{{__('lang.chapter')}} : {{ $chapter->name }}</h2>
    <h3>{{__('lang.labs')}} :</h3>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> {{__('lang.add') .' '. __('lang.exercise')}}</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="exercise_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="40%">{{__('lang.table_name')}}</th>
                <th width="10%">{{__('lang.order')}}</th>
                <th width="15%"></th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
    <br>
    <h3>{{__('lang.lectures')}} :</h3>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="addLecture()"><i class="fas fa-plus-square"></i>
                {{__('lang.new_lecture')}}</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="lecture_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="40%">{{__('lang.table_name')}}</th>
                <th width="10%">{{__('lang.order')}}</th>
                <th width="15%"></th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <div class="modal fade" id="post-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('lang.add') .' '. __('lang.exercise')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="upload_form" name="Form" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="exercise_id" id="exercise_id">
{{--                        <input type="hidden" name="model_type" id="model_type" value="exercise">--}}
                        <div class="form-group">
                            <label for="inputName">{{__('lang.table_name')}}</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   placeholder="{{__('lang.table_name')}}..."
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">{{__('lang.description')}}</label>
                            <textarea class="form-control"
                                      id="exercise_content"
                                      name="exercise_content"
                                      placeholder="{{__('lang.description')}}..."
                                      rows="4">
                            </textarea>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputName">{{__('lang.folder')}}</label>
                                    <input type="text"
                                           class="form-control"
                                           id="path"
                                           placeholder="{{__('lang.folder')}}..."
                                           name="path">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhone">{{__('lang.order')}}</label>
                                    <input type="number" class="form-control"
                                           id="order"
                                           name="order"
                                           placeholder="{{__('lang.folder')}}...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="file"
                                   id="file"
                                   name="file">
{{--                            <input  type="submit" name="upload" id="upload" class="btn btn-primary" value="Upload">--}}
                        </div>
                        <div class="form-group" id="form-files">

                        </div>
                        <div class="form-group" id="form-errors">
                            <div class="alert alert-danger">
                                <ul>

                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <div class="col">
                        <div  class="collapse" id="collapseExample">
                            <button class="btn btn-danger" onclick="deleteChapter()"><i class="fas fa-trash"></i> {{ __('lang.delete')}}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="addFile()">{{ __('lang.upload')}}</button>
                    <button class="btn btn-primary" onclick="finalSave()">{{ __('lang.save')}}</button>
                    <button class="btn btn-secondary" data-dismiss="modal">{{ __('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="post-modal-2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('lang.new_lecture')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="upload_form" name="Form" class="form-horizontal">
                        <input type="hidden" name="lecture_id" id="lecture_id">
                        {{--                        <input type="hidden" name="model_type" id="model_type" value="exercise">--}}
                        <div class="form-group">
                            <label for="inputName">{{__('lang.table_name')}}</label>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   placeholder="{{__('lang.table_name')}}..."
                                   name="title">
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">{{__('lang.description')}}</label>
                            <textarea class="form-control lecture"
                                      id="lecture_content"
                                      name="lecture_content"
                                      placeholder="{{__('lang.description')}}..."
                                      rows="4">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">{{__('lang.order')}}</label>
                            <input type="number" class="form-control"
                                   id="lecture_order"
                                   name="lecture_order"
                                   placeholder="{{__('lang.order')}}...">
                        </div>
                        <div class="form-group" id="form-errors-lecture">
                            <div class="alert alert-danger">
                                <ul>

                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <div class="col">
                        <div  class="collapse" id="collapseExample-2">
                            <button class="btn btn-danger" onclick="deleteLecture()"><i class="fas fa-trash"></i> {{__('lang.delete')}}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="saveLecture()">{{__('lang.save')}}</button>
                    <button class="btn btn-secondary" data-dismiss="modal">{{__('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            // selector: 'textarea',
            mode : "specific_textareas",
            editor_selector : "lecture",
            plugins: '     autolink lists  media     table   ',
            // plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
            toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            height : "280"
        });
    </script>
    <script>
        function add() {
            $('#collapseExample').hide();
            $('#staticBackdropLabel').text("{{__('lang.add') .' '. __('lang.exercise')}}");
            $('#form-errors').html("");
            $('#form-files').html("");
            $('#exercise_id').val('');
            $("#path").prop('disabled', false);
            $('#name').val('');
            $('#path').val('');
            $('#order').val('');
            $('#exercise_content').val('');
            $('#post-modal').modal('show');
        }
        function addLecture() {
            $('#collapseExample').hide();
            $('#staticBackdropLabel').text("{{__('lang.new_lecture')}}");
            $('#form-errors-lecture').html("");
            $('#lecture_id').val('');
            $('#title').val('');
            $('#lecture_order').val('');
            tinymce.get("lecture_content").setContent('');
            $('#post-modal-2').modal('show');
        }

        function finalSave(){
            save(() => $('#post-modal').modal('hide'));
        }

        function addFile(){
            save(() => uploadFile(() => editExercise(event)));
        }

        function uploadFile(callback){
            // $('#upload_form').on('submit', function(event){
                let myForm = document.getElementById('upload_form');
            //     // event.preventDefault();
                let formData = new FormData(myForm);
                formData.append('folder', $('#path').val());
                formData.append('model_type', 'exercise');
                formData.append('model_id', $('#exercise_id').val());
                $.ajax({
                    url:"{{route('attachments.store')}}",
                    method:"POST",
                    data: formData,
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        $('#file').val('')
                        callback();
                    }
                })
            // });
        }

        function deleteChapter() {
            var id = $('#exercise_id').val();
            let _url = `/exercises/${id}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: function(response) {
                    $('#exercise_table').DataTable().ajax.reload();
                    $('#post-modal').modal('hide');
                }
            });
        }

        function editExercise (event) {
            console.log('3')
            $('#collapseExample').show();
            $('#form-errors').html("");
            $('#form-files').html("");
            // $('#path').val(response.path);
            $("#path").prop('disabled', true);
            $('#staticBackdropLabel').text("{{__('lang.edit_exercise')}}");
            var id  = $(event).data("id");
            if(!id){
                console.log(id);
                id = $("#exercise_id").val();
            }

            let _url = `/exercises/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $("#exercise_id").val(response.id);
                        $("#name").val(response.name);
                        $('#path').val(response.path);
                        $("#exercise_content").val(response.content);
                        $("#order").val(response.order);
                        if(response.attachments.length > 0){
                            var files = response.attachments;
                                fileHtml = '<label>Вложенные файлы</label>';
                            fileHtml += '<ul>';
                            $.each( files, function(i) {
                                fileHtml += '<li><a href="/download/'+ files[i].id + '">'+ files[i].name + '</a></li>';
                            });
                            fileHtml += '</ul>';

                            $( '#form-files' ).html( fileHtml );
                        }
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function deleteLecture() {
            var id = $('#lecture_id').val();
            let _url = `/lectures/${id}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: function(response) {
                    $('#lecture_table').DataTable().ajax.reload();
                    $('#post-modal-2').modal('hide');
                }
            });
        }

        function editLecture (event) {
            $('#collapseExample-2').show();
            $('#form-errors-lecture').html("");
            $('#staticBackdropLabel').text("{{__('lang.edit_lecture')}}");

            var id  = $(event).data("id");
            let _url = `/lectures/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $("#lecture_id").val(response.id);
                        $("#title").val(response.title);
                        // $('#exercise_content').val(response.content);
                        tinymce.get("lecture_content").setContent(response.content);
                        $("#lecture_order").val(response.order);
                        $('#post-modal-2').modal('show');
                    }
                }
            });
        }
        function saveLecture() {
            var title = $('#title').val();
            var content =  tinymce.get('lecture_content').getContent();$('#lecture_content').val();
            // var content = $('#lecture_content').val();
            var id = $('#lecture_id').val();
            var order = $('#lecture_order').val();
            var chapter_id = '{{$chapter->id}}';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            // console.log();
            $.ajax({
                url: "{{ route('lectures.store') }}",
                type: "POST",
                data: {
                    id: id,
                    title: title,
                    content: content,
                    order: order,
                    chapter_id: chapter_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#lecture_table').DataTable().ajax.reload();
                        $('#post-modal-2').modal('hide');
                    }
                    else{
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>';
                        });
                        errorsHtml += '</ul></div>';

                        $( '#form-errors-lecture' ).html( errorsHtml );

                    }
                },
                error: function(response) {
                    console.log(response.responseJSON.errors);
                }
            });
        }

        function save(callback) {
            var name = $('#name').val();
            var path = $('#path').val();
            var content = $('#exercise_content').val();
            var id = $('#exercise_id').val();
            var order = $('#order').val();
            var chapter_id = '{{$chapter->id}}';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            // console.log();
            $.ajax({
                url: "{{ route('exercises.store') }}",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    path: path,
                    content: content,
                    order: order,
                    chapter_id: chapter_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#exercise_id').val(response.data.id);
                        console.log('id ' + response.data.id);
                        $('#exercise_table').DataTable().ajax.reload();
                        callback();
                        // $('#post-modal').modal('hide');
                    }
                    else{
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>';
                        });
                        errorsHtml += '</ul></div>';

                        $( '#form-errors' ).html( errorsHtml );

                    }
                },
                error: function(response) {
                    console.log(response.responseJSON.errors);
                }
            });
        }
        $(document).ready(function() {

            $('#lecture_table').DataTable({
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                order: [[ 2, "asc" ]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('chapters.lectures', $chapter->id) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false
                    },
                    {
                        data: 'more',
                        name: 'more',
                        orderable: false
                    },
                ]
            });
        });
        $(document).ready(function() {

            $('#exercise_table').DataTable({
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                order: [[ 2, "asc" ]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('chapters.show', $chapter->id) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false
                    },
                    {
                        data: 'more',
                        name: 'more',
                        orderable: false
                    },
                ]
            });
        });
    </script>
@endsection
