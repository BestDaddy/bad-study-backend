@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    {{--    {{ Breadcrumbs::render('courses') }}--}}
    <h2>Урок на : {{$schedule->starts_at}}</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> Добавить группу</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="attendance_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="65%">Студент</th>
                <th width="15%">Присутствовал</th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
@endsection


@section('scripts')
    <script>
        function changeAttendance (event) {
            var id  = $(event).data("id");
            var value  = $(event).data("value");
            let _url = `{{route('changeAttendance')}}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: "POST",
                data: {
                    id: id,
                    value:value,
                    _token: _token
                },
                success: function(response) {
                    if(response) {
                        $('#attendance_table').DataTable().ajax.reload();
                    }
                }
            });
        }
        $(document).ready(function() {

            $('#attendance_table').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('groups.courses.schedules.show', [$group, $course, $schedule->id]) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    // {
                    //     data: 'value',
                    //     name: 'value'
                    // },
                    {
                        data: 'change',
                        name: 'change',
                        orderable: false
                    },
                    {
                        data: 'results',
                        name: 'results',
                        orderable: false
                    },
                ]
            });
        });
    </script>
@endsection
