@extends('layouts.admin')

@section('title', 'Vendas')

@push('styles')
	@vite([
		'resources/css/vendas.css'
	])
@endpush

@section('content')
	<main class="sales-admin-main">
		@include('vendas._content')
	</main>
@endsection
