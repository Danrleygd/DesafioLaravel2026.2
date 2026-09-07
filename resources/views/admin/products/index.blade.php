@extends('layouts.admin')

@section('title', 'Produtos')

@push('styles')
    @vite([
        'resources/css/productManagement.css'
    ])
@endpush

@section('content')

    {{-- =========================================================
        RF007 - GERENCIAMENTO DE PRODUTOS
    ========================================================== --}}

    @include('products.manage._content')


    {{-- =========================================================
        RF013 - GRÁFICO DE PRODUTOS CADASTRADOS
    ========================================================== --}}

    @include('admin.products._chart')

@endsection

@push('scripts')
    @vite([
        'resources/js/productManagement.js'
    ])
@endpush