@extends('layouts.admin')

@section('title', 'Master Komisi')
@section('pretitle', 'Master Data')

@section('content')
    @include('admin.partials.simple-master-crud', [
        'routePrefix' => 'admin.komisi',
        'label' => 'Komisi',
        'slug' => 'komisi',
    ])
@endsection
