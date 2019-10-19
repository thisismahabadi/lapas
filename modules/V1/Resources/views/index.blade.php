@extends('v1::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('v1.name') !!}
    </p>
@stop
