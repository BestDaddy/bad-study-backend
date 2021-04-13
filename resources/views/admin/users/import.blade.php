@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    <div class="row">
        <div class="col-10">
            <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <label for="chats">Файл EXCEL</label>
                <input type="file"  name="file" id="file" class="form-control-file">
                <label for="groups" class="mt-2">Группы</label>
                <select class="groups form-control" name="group_id" id="group_id">
                    @foreach( $groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                </select>
                <div class="form-group">
                    @if(count($errors) >0)
                        <label>Неправильные номера, пожалуйста исправьте и повторите процедуру</label>
                        <div class="alert alert-danger">
                            <ul>
                                @foreach( $errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary mt-2 float-right">Отправить</button>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
{{--    <script>--}}

{{--        function add() {--}}
{{--            $('#form-errors').html("");--}}
{{--            $('#user_id').val('');--}}
{{--            $('#first_name').val('');--}}
{{--            $('#last_name').val('');--}}
{{--            $('#phone').val('');--}}
{{--            $('#email').val('');--}}
{{--            $('#role_id').val(2);--}}
{{--            $('#password').val('');--}}
{{--            $('#collapseExample').hide();--}}
{{--            $('#post-modal').modal('show');--}}

{{--        }--}}
{{--        function showPassword() {--}}
{{--            var x = document.getElementById("password");--}}
{{--            if (x.type === "password") {--}}
{{--                x.type = "text";--}}
{{--            } else {--}}
{{--                x.type = "password";--}}
{{--            }--}}
{{--        }--}}
{{--        // function deleteUser() {--}}
{{--        //     var id = $('#user_id').val();--}}
{{--        //     let _url = `/users/${id}`;--}}
{{--        //--}}
{{--        //     let _token   = $('meta[name="csrf-token"]').attr('content');--}}
{{--        //--}}
{{--        //     $.ajax({--}}
{{--        //         url: _url,--}}
{{--        //         type: 'DELETE',--}}
{{--        //         data: {--}}
{{--        //             _token: _token--}}
{{--        //         },--}}
{{--        //         success: function(response) {--}}
{{--        //             $('#user_table').DataTable().ajax.reload();--}}
{{--        //             $('#post-modal').modal('hide');--}}
{{--        //         }--}}
{{--        //     });--}}
{{--        // }--}}


{{--        function editUser (event) {--}}
{{--            $('#collapseExample').show();--}}
{{--            $('#staticBackdropLabel').text("Редактировать пользователя");--}}
{{--            $('#form-errors').html("");--}}
{{--            var id  = $(event).data("id");--}}
{{--            let _url = `users/${id}/edit`;--}}
{{--            $.ajax({--}}
{{--                url: _url,--}}
{{--                type: "GET",--}}
{{--                success: function(response) {--}}
{{--                    if(response) {--}}
{{--                        $('#user_id').val(response.id);--}}
{{--                        $('#first_name').val(response.first_name);--}}
{{--                        $('#last_name').val(response.last_name);--}}
{{--                        $('#phone').val(response.phone);--}}
{{--                        $('#email').val(response.email);--}}
{{--                        $('#role_id').val(response.role_id);--}}
{{--                        // $('#password').val(response.password);--}}
{{--                        $('#post-modal').modal('show');--}}
{{--                    }--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}
{{--        function save() {--}}
{{--            var id = $('#user_id').val();--}}
{{--            var first_name = $('#first_name').val();--}}
{{--            var last_name = $('#last_name').val();--}}
{{--            var phone = $('#phone').val();--}}
{{--            var email = $('#email').val();--}}
{{--            var role_id = $('#role_id').val();--}}
{{--            var password = $('#password').val();--}}
{{--            let _token   = $('meta[name="csrf-token"]').attr('content');--}}

{{--            $.ajax({--}}
{{--                url: "{{ route('users.store') }}",--}}
{{--                type: "POST",--}}
{{--                data: {--}}
{{--                    id: id,--}}
{{--                    first_name: first_name,--}}
{{--                    last_name: last_name,--}}
{{--                    phone: phone,--}}
{{--                    email: email,--}}
{{--                    password: password,--}}
{{--                    role_id: role_id,--}}
{{--                    _token: _token--}}
{{--                },--}}
{{--                success: function(response) {--}}
{{--                    if(response.code == 200) {--}}
{{--                        $('#user_id').val('');--}}
{{--                        $('#first_name').val('');--}}
{{--                        $('#last_name').val('');--}}
{{--                        $('#phone').val('');--}}
{{--                        $('#email').val('');--}}
{{--                        $('#role_id').val(2);--}}
{{--                        $('#password').val('');--}}
{{--                        $('#user_table').DataTable().ajax.reload();--}}
{{--                        $('#post-modal').modal('hide');--}}
{{--                    }--}}
{{--                    else{--}}
{{--                        var errors = response.errors;--}}
{{--                        errorsHtml = '<div class="alert alert-danger"><ul>';--}}

{{--                        $.each( errors, function( key, value ) {--}}
{{--                            errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.--}}
{{--                        });--}}
{{--                        errorsHtml += '</ul></div>';--}}

{{--                        $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form--}}

{{--                    }--}}
{{--                },--}}
{{--                error: function(response) {--}}
{{--                    $('#nameError').text(response.responseJSON.errors.name);--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}
{{--        $(document).ready(function() {--}}

{{--            $('#user_table').DataTable({--}}
{{--                language: {--}}
{{--                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"--}}
{{--                },--}}
{{--                processing: true,--}}
{{--                serverSide: true,--}}
{{--                ajax: {--}}
{{--                    url: "{{ route('users.index') }}",--}}
{{--                },--}}
{{--                columns: [--}}
{{--                    {--}}
{{--                        data: 'id',--}}
{{--                        name: 'id'--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'first_name',--}}
{{--                        name: 'first_name'--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'last_name',--}}
{{--                        name: 'last_name'--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'email',--}}
{{--                        name: 'email'--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'role.name',--}}
{{--                        name: 'role.name'--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'edit',--}}
{{--                        name: 'edit',--}}
{{--                        orderable: false--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'more',--}}
{{--                        name: 'more',--}}
{{--                        orderable: false--}}
{{--                    },--}}
{{--                ]--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
@endsection
