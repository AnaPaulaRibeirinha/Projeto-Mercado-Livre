@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Produtos</h1>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $product['thumbnail'] }}" class="card-img-top" alt="{{ $product['title'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product['title'] }}</h5>
                        <p class="card-text">Preço: R$ {{ number_format($product['price'], 2, ',', '.') }}</p>
                        <a href="{{ $product['permalink'] }}" class="btn btn-primary" target="_blank">Ver Produto</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
