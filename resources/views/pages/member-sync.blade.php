@extends('layouts.default')
@section('title')
Dashboard
@stop
@section('content')
    <form method="post" action="">
    {{ csrf_field() }}
        <button type="submit" class="btn btn-primary">Sync</button>
    </form>
@stop
