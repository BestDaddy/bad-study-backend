@extends('layouts.admin')
@section('header')
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Unity WebGL Player | Gubsky_ZVA</title>
    <link rel="shortcut icon" href="{{ URL::asset('TemplateData/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ URL::asset('TemplateData/style.css') }}" />
{{--    <link rel="shortcut icon" href="TemplateData/favicon.ico">--}}
{{--    <link rel="stylesheet" href="TemplateData/style.css">--}}
    <script src="{{asset('TemplateData/UnityProgress.js')}}"></script>
    <script src="{{asset('Build/UnityLoader.js')}}"></script>
{{--    <script src="TemplateData/UnityProgress.js"></script>--}}
{{--    <script src="Build/UnityLoader.js"></script>--}}
    <script>
        {{--var gameInstance = UnityLoader.instantiate("gameContainer", "{{asset('Build/ZVA_WEB.json')}}", {onProgress: UnityProgress});--}}
        var gameInstance = UnityLoader.instantiate("gameContainer", "{{asset($exercise->content)}}", {onProgress: UnityProgress});

    </script>

@endsection
@section('content')
    <div id="gameContainer" style="width: 960px; height: 600px"></div>
    <div class="footer">
        <div class="webgl-logo"></div>
        <div class="fullscreen" onclick="gameInstance.SetFullscreen(1)"></div>
        <div class="title">Gubsky_ZVA</div>
    </div>


@endsection

