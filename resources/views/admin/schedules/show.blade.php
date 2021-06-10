@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
        {{ Breadcrumbs::render('schedules.show', $group, $course, $schedule) }}
    <h2>{{__('lang.chapter')}} : {{$schedule->chapter->name}}</h2>
    <h4>{{__('lang.schedule_on')}} : {{$schedule->starts_at}}</h4>
    <hr>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="attendance_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="65%">{{__('lang.student')}}</th>
                <th width="15%">{{__('lang.attendant')}}</th>
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
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
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
