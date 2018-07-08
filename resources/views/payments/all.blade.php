@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">Пополнения баланса</h2>
        <div class="uk-text-center">
            <a href="{{ url('/payments/new') }}" class="uk-button uk-button-primary">Ручное пополнение</a>
        </div>

        <div uk-grid>
            @foreach($payments as $payment)

                <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-2@m">
                    <h3 class="uk-card-title">{{ $payment->amount }} BTC</h3>
                    <p>
                        Создан: <em class="uk-text-small">{{ $payment->created_at }}</em><br>
                        Кошелек: <em>{{ $payment->wallet }}</em>
                    </p>
                    <div>
                        @if($payment->is_confirmed == false)
                            <a href="{{ url('/payments/confirm/' . $payment->id) }}" class="uk-button uk-button-primary uk-button-small">Подтвердить</a>
                        @endif
                        <a href="{{ url('/payments/delete/' . $payment->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
                    </div>
                </div>

            @endforeach
        </div>

        @if(!empty($pages))
            <ul class="uk-pagination uk-flex-center uk-padding">
                @foreach($pages as $page)
                    <li class="@if($page['active']){{ 'uk-active' }}@endif"><a href="{{ $page['link'] }}">{{ $page['text'] }}</a></li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection
