@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Фонд {{ $fund->name }}
        </h2>
    </div>
    <div class="uk-container uk-padding">
        <p>Количество токенов: <em>{{ $fund->token_count }}</em></p>
        <p>Цена токена, USD: <em>{{ round($fund->token_price, 4) }}</em></p>
        <p>Баланс фонда, USD: <em>{{ round($fund->balance_usd, 3) }}</em></p>
        <form action="{{ url()->to('fund/' . $fund->id . '/manual-usd') }}" method="post"
              class="uk-form-horizontal uk-width-1-2">
            @csrf
            <div class="uk-margin">
                <label class="uk-form-label">Дополнительные USD</label>
                <div class="uk-form-controls">
                    <input type="number" step="any" value="@if (!empty($fund)){{ $fund->manual_balance_usd }}@endif"
                           name="manual_balance_usd" id="amount" class="uk-input">
                </div>
                @if ($errors->has('manual_balance_usd'))
                    <div class="uk-alert-danger" uk-alert>
                        <strong>{{ $errors->first('manual_balance_usd') }}</strong>
                    </div>
                @endif
            </div>
            <button type="submit" class="uk-button uk-button-primary">Обновить</button>
        </form>
    </div>
@endsection