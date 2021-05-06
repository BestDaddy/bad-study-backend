@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
    <h2>Все пользователи</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" onclick="add()"><i class="fas fa-plus-square"></i> Добавить пользователя</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class=" table table-bordered table-striped" id="user_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Имя</th>
                <th width="15%">Фамилия</th>
                <th width="25%">Email</th>
                <th width="10%">Роль</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Новый пользователь</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="Form" class="form-horizontal">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputName">Имя</label>
                                    <input type="text"
                                           class="form-control"
                                           id="first_name"
                                           name="first_name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhone">Фамилия</label>
                                    <input type="text"
                                           class="form-control"
                                           id="last_name"
                                           name="last_name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="button" onclick="showPassword()">Показать</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhone">Почта</label>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email">
                                </div>

                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhone">Роль</label>
                                    <select class="form-control" id="role_id" name="role_id">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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

                    {{--                    <div class="col-lg-9">--}}
                    {{--                        <div  class="collapse" id="collapseExample">--}}
                    {{--                            <button type="button" class="btn btn-danger" onclick="deleteUser()">Бан</button>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <button type="button" class="btn btn-primary" onclick="save()">Сохранить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
   <script>

        function add() {
            $('#form-errors').html("");
            $('#user_id').val('');
            $('#first_name').val('');
            $('#last_name').val('');
            $('#phone').val('');
            $('#email').val('');
            $('#role_id').val(2);
            $('#password').val('');
            $('#collapseExample').hide();
            $('#post-modal').modal('show');

        }
        function showPassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        // function deleteUser() {
        //     var id = $('#user_id').val();
        //     let _url = `/users/${id}`;
        //
        //     let _token   = $('meta[name="csrf-token"]').attr('content');
        //
        //     $.ajax({
        //         url: _url,
        //         type: 'DELETE',
        //         data: {
        //             _token: _token
        //         },
        //         success: function(response) {
        //             $('#user_table').DataTable().ajax.reload();
        //             $('#post-modal').modal('hide');
        //         }
        //     });
        // }


        function editUser (event) {
            $('#collapseExample').show();
            $('#staticBackdropLabel').text("Редактировать пользователя");
            $('#form-errors').html("");
            var id  = $(event).data("id");
            let _url = `users/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $('#user_id').val(response.id);
                        $('#first_name').val(response.first_name);
                        $('#last_name').val(response.last_name);
                        $('#phone').val(response.phone);
                        $('#email').val(response.email);
                        $('#role_id').val(response.role_id);
                        // $('#password').val(response.password);
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function save() {
            var id = $('#user_id').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var role_id = $('#role_id').val();
            var password = $('#password').val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('users.store') }}",
                type: "POST",
                data: {
                    id: id,
                    first_name: first_name,
                    last_name: last_name,
                    phone: phone,
                    email: email,
                    password: password,
                    role_id: role_id,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#user_id').val('');
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#phone').val('');
                        $('#email').val('');
                        $('#role_id').val(2);
                        $('#password').val('');
                        $('#user_table').DataTable().ajax.reload();
                        $('#post-modal').modal('hide');
                    }
                    else{
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
                    $('#nameError').text(response.responseJSON.errors.name);
                }
            });
        }
        $(document).ready(function() {

            $('#user_table').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    dataType: "JSON"
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
                        data: 'role.name',
                        name: 'role.name'
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
