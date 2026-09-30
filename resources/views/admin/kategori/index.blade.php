@extends('layouts.admin')

@section('title', 'Master Kategori')
@section('pretitle', 'Master Data')

@section('content')
    @include('admin.partials.simple-master-crud', [
        'routePrefix' => 'admin.kategori',
        'label' => 'Kategori',
        'slug' => 'kategori',
    ])
@endsection
