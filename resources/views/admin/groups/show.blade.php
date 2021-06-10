@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    {{ Breadcrumbs::render('groups.show', $group) }}
    <h2>{{__('lang.group')}} : {{ $group->name }}</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add2()"><i class="fas fa-plus-square"></i> {{__('lang.new_course')}}</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="course_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">{{__('lang.table_name')}}</th>
                <th width="30%">{{__('lang.teacher')}}</th>
                <th width="15%"></th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add3()"><i class="fas fa-plus-square"></i>{{__('lang.new_student')}}</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="student_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">{{__('lang.first_name')}}</th>
                <th width="25%">{{__('lang.last_name')}}</th>
                <th width="30%">{{__('lang.email')}}</th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
    <div class="modal fade bd-example-modal-lg " id="post-modal-2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('lang.available_courses')}} : </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="inputPhone">Преподаватель</label>
                            <select class="form-control" id="teacher_id" name="teacher_id">
                                <option value="" selected disabled> {{__('lang.select_teacher')}}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->id}}">{{$teacher->first_name. ' ' . $teacher->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="new_course_table" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('lang.table_name')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="form-group" id="form-errors">
                            <div class="alert alert-danger">
                                <ul>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg " id="post-modal-3" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('lang.available_students')}} : </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="new_student_table" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('lang.first_name')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>

        function add2() {
            $('#form-errors').html("");
            $('#post-modal-2').modal('show');
        }

        function add3() {
            $('#post-modal-3').modal('show');
        }



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

        function removeCourse(event) {
            var course_id =  $(event).data("course_id");
            var group_id = '{{$group->id}}';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{route('removeCourse')}}",
                type: "POST",
                data: {
                    group_id: group_id,
                    course_id: course_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#student_table').DataTable().ajax.reload();
                        $('#course_table').DataTable().ajax.reload();
                        $('#new_course_table').DataTable().ajax.reload();
                        $('#new_student_table').DataTable().ajax.reload();
                    } else {
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></div>';

                        $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                    }
                },
                error: function(response) {
                }
            });
        }

        function addCourse(event) {
            var course_id =  $(event).data("id");
            var group_id = '{{$group->id}}';
            var teacher_id = $('#teacher_id').val();
            console.log(course_id);
            console.log(group_id);
            console.log(teacher_id);
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{route('addCourse')}}",
                type: "POST",
                data: {
                    group_id: group_id,
                    teacher_id: teacher_id,
                    course_id: course_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#new_student_table').DataTable().ajax.reload();
                        $('#student_table').DataTable().ajax.reload();
                        $('#course_table').DataTable().ajax.reload();
                        $('#new_course_table').DataTable().ajax.reload();
                    }else{
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></div>';

                        $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form

                    }
                },
                error: function(response) {
                }
            });
        }

        function removeUser(event) {
            var user_id =  $(event).data("id");
            var group_id = '{{$group->id}}';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{route('removeUser')}}",
                type: "POST",
                data: {
                    group_id: group_id,
                    user_id: user_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#student_table').DataTable().ajax.reload();
                        $('#new_student_table').DataTable().ajax.reload();
                    } else {
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></div>';

                        $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                    }
                },
                error: function(response) {
                }
            });
        }

        function addUser(event) {
            var user_id =  $(event).data("id");
            var group_id = '{{$group->id}}';
            console.log(user_id);
            console.log(group_id);
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{route('addUser')}}",
                type: "POST",
                data: {
                    group_id: group_id,
                    user_id: user_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#student_table').DataTable().ajax.reload();
                        $('#new_student_table').DataTable().ajax.reload();
                    }
                },
                error: function(response) {
                }
            });
        }

        $(document).ready(function() {
            $('#new_student_table').DataTable({
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                iDisplayLength: 25,
                ajax: {
                    url:  `{{route('getNewStudents', $group->id)}}`
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width:'10%',
                    },
                    {
                        data: 'first_name',
                        name: 'first_name',
                        width:'65%',
                    },
                    {
                        data: 'add',
                        name: 'add',
                        width:'25%',
                        orderable: false
                    },
                ]
            });
        });

        $(document).ready(function() {

            $('#student_table').DataTable({
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getStudents', $group->id) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'delete',
                        name: 'delete',
                        orderable: false
                    },
                ]
            });
        });

        $(document).ready(function() {
            $('#new_course_table').DataTable({
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                iDisplayLength: 25,
                ajax: {
                    url:  `{{route('getNewCourses', $group->id)}}`
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width:'10%',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        width:'65%',
                    },
                    {
                        data: 'add',
                        name: 'add',
                        width:'25%',
                        orderable: false
                    },
                ]
            });
        });

        $(document).ready(function() {

            $('#course_table').DataTable({
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('groups.show', $group->id) }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'course.name',
                        name: 'course.name'
                    },
                    {
                        data: 'teacher.first_name',
                        name: 'teacher.first_name'
                    },
                    {
                        data: 'delete',
                        name: 'delete',
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
