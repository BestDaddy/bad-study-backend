@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
        {{ Breadcrumbs::render('courses.show', $course) }}
    <h2>Главы курса : {{ $course->name }}</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> Добавить курс</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="chapter_table" width="100%">
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
                    <form name="Form" class="form-horizontal">
                        <input type="hidden" name="chapter_id" id="chapter_id">
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
                                      id="description"
                                      name="description"
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
            $('#chapter_id').val('');
            $('#name').val('');
            $('#order').val('');
            $('#description').val('');
            $('#post-modal').modal('show');
        }

        function deleteChapter() {
            var id = $('#chapter_id').val();
            let _url = `/chapters/${id}`;

            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: function(response) {
                    $('#chapter_table').DataTable().ajax.reload();
                    $('#post-modal').modal('hide');
                }
            });
        }

        function editChapter (event) {
            $('#collapseExample').show();
            $('#form-errors').html("");
            $('#staticBackdropLabel').text("Редактировать курс");

            var id  = $(event).data("id");
            let _url = `/chapters/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $("#chapter_id").val(response.id);
                        $("#name").val(response.name);
                        $("#description").val(response.description);
                        $("#order").val(response.order);
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function save() {
            var name = $('#name').val();
            var description = $('#description').val();
            var id = $('#chapter_id').val();
            var order = $('#order').val();
            var course_id = '{{$course->id}}';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            // console.log();
            $.ajax({
                url: "{{ route('chapters.store') }}",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    description: description,
                    order: order,
                    course_id: course_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#name').val('');
                        $('#description').val('');
                        $('#chapter_table').DataTable().ajax.reload();
                        $('#post-modal').modal('hide');
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

            $('#chapter_table').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('courses.show', $course->id) }}",
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
