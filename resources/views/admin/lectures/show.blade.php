@extends('layouts.admin')

@section('content')
    {{ Breadcrumbs::render('lectures.show', $lecture) }}
    <h2>{{__('lang.lecture')}} : {{ $lecture->title }}</h2>

    <p>{!! $lecture->content !!}</p>
@endsection

@section('scripts')
    {{--    <script src="https://cdn.tiny.cloud/1/pco2wt4rtoxpfk9fjc9lriev5c9grvck1s5ndetbt31yxs5x/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
    <script
        src="{{ asset('/js/tinymce/tinymce.min.js') }}"
        {{--                src="https://cdn.tiny.cloud/1/x8f8juwu00mu7w86d0hxhyyskcun9mspo3g7f9cx2fn1ccmy/tinymce/5/tinymce.min.js" --}}
        referrerpolicy="origin">

    </script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
            toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
        });
    </script>
@endsection
