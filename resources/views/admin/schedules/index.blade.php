@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    {{--    {{ Breadcrumbs::render('courses') }}--}}
    <h2>Все группы :</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> Добавить группу</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="schedule_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="50%">Название</th>
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
                        <input type="hidden" name="schedule_id" id="schedule_id">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputName">Начало урока</label>
                                    <input type="datetime-local"
                                           class="form-control"
                                           id="starts_at"
                                           name="starts_at" value="2020-09-22T13:25"
                                           min="2020-09-22T13:25" max="2021-06-14T00:00">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputName">Глава</label>
                                    <select class="form-control" name="chapter_id" id="chapter_id">
                                        @foreach($chapters as $chapter)
                                            <option value="{{$chapter->id}}">{{$chapter->order}}:  {{$chapter->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Ссылка</label>
                            <input type="text"
                                   class="form-control"
                                   id="live_url"
                                   placeholder="Введите ссылку на чат"
                                   name="live_url">
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
                            <button class="btn btn-danger" onclick="deleteCourse()"><i class="fas fa-trash"></i> Удалить</button>
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
            $('#group_id').val('');
            $('#name').val('');
            $('#chat').val('');
            $('#description').val('');
            $('#post-modal').modal('show');
        }

        {{--function deleteCourse() {--}}
        {{--    var id = $('#group_id').val();--}}
        {{--    let _url = `courses/${id}`;--}}

        {{--    let _token   = $('meta[name="csrf-token"]').attr('content');--}}

        {{--    $.ajax({--}}
        {{--        url: _url,--}}
        {{--        type: 'DELETE',--}}
        {{--        data: {--}}
        {{--            _token: _token--}}
        {{--        },--}}
        {{--        success: function(response) {--}}
        {{--            $('#course_table').DataTable().ajax.reload();--}}
        {{--            $('#post-modal').modal('hide');--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

        {{--function editCourse (event) {--}}
        {{--    $('#collapseExample').show();--}}
        {{--    $('#form-errors').html("");--}}
        {{--    $('#staticBackdropLabel').text("Редактировать курс");--}}

        {{--    var id  = $(event).data("id");--}}
        {{--    let _url = `courses/${id}/edit`;--}}
        {{--    $.ajax({--}}
        {{--        url: _url,--}}
        {{--        type: "GET",--}}
        {{--        success: function(response) {--}}
        {{--            if(response) {--}}
        {{--                $("#group_id").val(response.id);--}}
        {{--                $("#name").val(response.name);--}}
        {{--                $("#description").val(response.description);--}}
        {{--                $('#post-modal').modal('show');--}}
        {{--            }--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}
        function save() {
            var starts_at = $('#starts_at').val();
            var live_url = $('#live_url').val();
            var id = $('#schedule_id').val();
            var chapter_id = $('#chapter_id').val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('groups.courses.schedules.store', [$group, $course]) }}",
                type: "POST",
                data: {
                    id: id,
                    group_id:'{{$group->id}}',
                    chapter_id:chapter_id,
                    starts_at: starts_at,
                    live_url: live_url,
                    chapter_id: chapter_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#name').val('');
                        $('#description').val('');
                        $('#schedule_table').DataTable().ajax.reload();
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

            $('#schedule_table').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('groups.courses.schedules.index', [$group, $course]) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'starts_at',
                        name: 'starts_at'
                    },
                    {
                        data: 'chapter.name',
                        name: 'chapter.name'
                    },
                    // {
                    //     data: 'edit',
                    //     name: 'edit',
                    //     orderable: false
                    // },
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