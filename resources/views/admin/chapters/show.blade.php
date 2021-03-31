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
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> Добавить курс</a>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Новый курс</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="upload_form" name="Form" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="exercise_id" id="exercise_id">
                        <input type="hidden" name="model_type" id="model_type" value="exercise">
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
                            <label for="file">Файл</label>
                            <input type="file" class="form-control"
                                   id="file"
                                   name="file">
                            <input type="submit" name="upload" id="upload" class="btn btn-primary" value="Upload">
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
{{--                    <button class="btn btn-primary" onclick="uploadFile()">dadw</button>--}}
                    <button class="btn btn-primary" onclick="save()">Сохранить</button>
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
            $('#name').val('');
            $('#order').val('');
            $('#exercise_content').val('');
            $('#post-modal').modal('show');
        }

        // $(document).ready(function(){
        //     // $("#file").on('change',function(){
        //     //     save(() =>uploadFile(() =>editExercise(event)));
        //     // });
        //     const fileInput = document.getElementById('file_upload');
        //     fileInput.onchange = () => {
        //         const selectedFile = fileInput.files[0];
        //         console.log(selectedFile);
        //     }
        // });

        function saveFile(){

        }
        $(document).ready(function(){

            $('#upload_form').on('submit', function(event){
                event.preventDefault();
                $.ajax({
                    url:"{{route('attachments.store')}}",
                    method:"POST",
                    data:new FormData(this),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        $('#message').css('display', 'block');
                        $('#message').html(data.message);
                        $('#message').addClass(data.class_name);
                        $('#uploaded_image').html(data.uploaded_image);
                    }
                })
            });

        });

        function uploadFile(e){
            e.preventDefault();

            // callback();
            let formData = new FormData(this);
            console.log(formData);
            var id = $('#exercise_id').val();
            let _url = `{{route('attachments.store')}}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: "POST",
                data: {
                    file: fileInput.files[0],
                    model_id: id,
                    model_type: 'exercise',
                    _token: _token
                },
                success: function(response) {
                    console.log('2')
                    if(response.code == 200) {
                        console.log('file uploaded!');
                        // $('#post-modal').modal('hide');

                    }
                },
                error: function(response) {
                    $('#nameError').text(response.responseJSON.errors.name);
                }
            });
        }

        function deleteChapter() {
            var id = $('#exercise_id').val();
            let _url = `chapters/${id}`;

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
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function save(callback) {
            console.log('1')
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
