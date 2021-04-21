@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    {{ Breadcrumbs::render('chapters.show', $chapter) }}
    <h2>Глава : {{ $chapter->name }}</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> Добавить задание</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="exercise_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="40%">Название</th>
                <th width="10%">Порядок</th>
                <th width="15%"></th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
    <div class="modal fade" id="post-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Новое задание</h5>
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
                            <label for="inputName">Название</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   placeholder="Введите название"
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">Описание</label>
                            <textarea class="form-control"
                                      id="exercise_content"
                                      name="exercise_content"
                                      placeholder="Введите описание"
                                      rows="4">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">Порядок</label>
                            <input type="number" class="form-control"
                                   id="order"
                                   name="order"
                                   placeholder="Введите Порядок">
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

                    <div class="col-lg-9">
                        <div  class="collapse" id="collapseExample">
                            <button class="btn btn-danger" onclick="deleteChapter()"><i class="fas fa-trash"></i> Удалить</button>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="addFile()">Upload</button>
                    <button class="btn btn-primary" onclick="finalSave()">Сохранить</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function add() {
            $('#collapseExample').hide();
            $('#staticBackdropLabel').text("Новый курс");
            $('#form-errors').html("");
            $('#exercise_id').val('');
            $( '#form-files' ).html( '' );
            $('#name').val('');
            $('#order').val('');
            $('#exercise_content').val('');
            $('#post-modal').modal('show');
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
            $('#staticBackdropLabel').text("Редактировать курс");
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
        function save(callback) {
            var name = $('#name').val();
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

            $('#exercise_table').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
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
