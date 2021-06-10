@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
        {{ Breadcrumbs::render('schedules.user.results', $schedule, $user) }}
    <h2>{{__('lang.answers_of')}} : {{$user->fullName()}}</h2>
    <h4>{{__('lang.chapter')}} : {{$schedule->chapter->name}}</h4>
    <hr>
    <br>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="result_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="50%">{{__('lang.exercise_1')}}</th>
                <th width="15%">{{__('lang.order')}}</th>
                <th width="15%">{{__('lang.score')}}</th>
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
                    <h5 class="modal-title" id="student_name">{{__('lang.student')}}: </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="Form" class="form-horizontal">
                        <input type="hidden" name="result_id" id="result_id">
                        <div class="form-group">
                            <label for="exercise_content" id="exercise_name"></label>
                            <textarea class="form-control" id="exercise_content" name="exercise_content" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="value">{{__('lang.student')}}</label>
                            <textarea class="form-control" id="value" name="value" readonly></textarea>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="comment">{{__('lang.comment')}}</label>
                                <textarea class="form-control" id="comment" rows="1" name="comment"></textarea>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="score">{{__('lang.score')}}</label>
                                    <input class="form-control" type="number" max="100" min="0" name="score" id="score">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="form-errors">
                            <div class="alert alert-danger">
                                <ul>

                                </ul>
                            </div>
                        </div>
                        <div class="form-group" id="form-files">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="save()">{{__('lang.save')}}</button>
                    <button class="btn btn-secondary" data-dismiss="modal">{{__('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function editAnswer (event) {
            $('#collapseExample').show();
            $('#form-errors').html("");
            $('#form-files').html("");
            var id  = $(event).data("id");
            let _url = `/results/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        console.log(response);
                        $('#student_name').text('{{__('lang.student')}} : ' + response.user.first_name +' '+ response.user.last_name);
                        $('#exercise_name').text('{{__('lang.exercise_1')}}: ' + response.exercise.name);
                        $("#result_id").val(response.id);
                        $("#value").val(response.value);
                        if(response.attachments.length > 0){
                            var files = response.attachments;
                            fileHtml = '<label>{{__('lang.files')}}</label>';
                            fileHtml += '<ul>';
                            $.each( files, function(i) {
                                fileHtml += '<li><a href="/download/'+ files[i].id + '">'+ files[i].name + '</a></li>';
                            });
                            fileHtml += '</ul>';

                            $( '#form-files' ).html( fileHtml );
                        }
                        $("#score").val(response.score);
                        $("#comment").val(response.comment);
                        $("#exercise_content").val(response.exercise.content);
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function save() {
            var result_id = $('#result_id').val();
            var comment = $('#comment').val();
            var score = $('#score').val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `/results/${result_id}`,
                type: "PUT",
                data: {
                    // id: result_id,
                    schedule_id: {{$schedule->id}},
                    comment:comment,
                    score:score,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#name').val('');
                        $('#description').val('');
                        $('#result_table').DataTable().ajax.reload();
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

            $('#result_table').DataTable({
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('userResults', [$schedule->id, $user->id]) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'exercise.name',
                        name: 'exercise.name'
                    },
                    {
                        data: 'exercise.order',
                        name: 'exercise.order',
                    },
                    {
                        data: 'score',
                        name: 'score'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false
                    },

                ]
            });
        });
    </script>
@endsection
