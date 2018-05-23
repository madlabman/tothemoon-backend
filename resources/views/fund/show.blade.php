@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Фонд {{ $fund->name }}
        </h2>
    </div>
    <div class="uk-container uk-padding">
        <p>Количество токенов: <em>{{ $fund->token_count }}</em></p>
        <p>Цена токена: <em>{{ round($fund->token_price, 4) }}</em></p>
        <p>Баланс фонда в USD: <em>{{ round($fund->balance_usd, 3) }}</em></p>
    </div>
@endsection