@extends('layouts.admin')

@section('title')
    BAD - STUDY
@endsection

@section('content')
        {{ Breadcrumbs::render('courses') }}
    <h2>{{ __('lang.all_courses')}} :</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal"  onclick="add()"><i class="fas fa-plus-square"></i> {{__('lang.add') .' '. __('lang.course') }}</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="course_table" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="50%">{{ __('lang.table_name')}}</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('lang.new_course')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="Form" class="form-horizontal">
                        <input type="hidden" name="course_id" id="course_id">
                        <div class="form-group">
                            <label for="inputName">{{ __('lang.table_name')}}</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   placeholder="{{ __('lang.table_name')}}..."
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">{{ __('lang.description')}}</label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      placeholder="{{ __('lang.description')}}..."
                                      rows="4">
                            </textarea>
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

                    <div class="col">
                        <div  class="collapse" id="collapseExample">
                            <button class="btn btn-danger" onclick="deleteCourse()"><i class="fas fa-trash"></i> {{ __('lang.delete')}}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="save()">{{ __('lang.save')}}</button>
                    <button class="btn btn-secondary" data-dismiss="modal">{{ __('lang.close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function add() {
            $('#collapseExample').hide();
            $('#staticBackdropLabel').text('{{ __('lang.new_course')}}');
            $('#form-errors').html("");
            $('#course_id').val('');
            $('#name').val('');
            $('#description').val('');
            $('#post-modal').modal('show');
        }

        function deleteCourse() {
            var id = $('#course_id').val();
            let _url = `courses/${id}`;

            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: function(response) {
                    $('#course_table').DataTable().ajax.reload();
                    $('#post-modal').modal('hide');
                }
            });
        }

        function editCourse (event) {
            $('#collapseExample').show();
            $('#form-errors').html("");
            $('#staticBackdropLabel').text("{{__('lang.edit') .' '. __('lang.course')}}");

            var id  = $(event).data("id");
            let _url = `courses/${id}/edit`;
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $("#course_id").val(response.id);
                        $("#name").val(response.name);
                        $("#description").val(response.description);
                        $('#post-modal').modal('show');
                    }
                }
            });
        }
        function save() {
            var name = $('#name').val();
            var description = $('#description').val();
            var id = $('#course_id').val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('courses.store') }}",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    description: description,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#name').val('');
                        $('#description').val('');
                        $('#course_table').DataTable().ajax.reload();
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

            $('#course_table').DataTable({
                @php $locale = session()->get('locale'); @endphp
                @if($locale != 'en')
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                @endif
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('courses.index') }}",
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
