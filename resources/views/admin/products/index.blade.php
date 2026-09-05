@extends('layouts.admin')

@section('title', 'Produtos')

@push('styles')
    @vite([
        'resources/css/productManagement.css'
    ])
@endpush

@section('content')
    @include('products.manage._content')
@endsection

@push('scripts')
    @vite([
        'resources/js/productManagement.js'
    ])
@endpush
