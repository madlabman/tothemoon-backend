@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Фонд {{ $fund->name }}
        </h2>
    </div>
    <div class="uk-container uk-padding">

        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <p>Баланс фонда, USD: <em>{{ round($fund->balance_usd, 3) }}</em></p>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Количество токенов</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" value="@if (!empty($fund)){{ $fund->token_count }}@endif" name="token_count" class="uk-input">
                    </div>
                    @if ($errors->has('token_count'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('token_count') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Цена токена, USD</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" value="@if (!empty($fund)){{ $fund->token_price }}@endif" name="token_price" class="uk-input">
                    </div>
                    @if ($errors->has('token_price'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('token_price') }}</strong>
                        </div>
                    @endif
                </div>

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

                <div class="uk-margin">
                    <label class="uk-form-label">Дополнительные BTC</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" value="@if (!empty($fund)){{ $fund->manual_balance_btc }}@endif"
                               name="manual_balance_btc" id="amount" class="uk-input">
                    </div>
                    @if ($errors->has('manual_balance_btc'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('manual_balance_btc') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Дополнительные ETH</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" value="@if (!empty($fund)){{ $fund->manual_balance_eth }}@endif"
                               name="manual_balance_eth" id="amount" class="uk-input">
                    </div>
                    @if ($errors->has('manual_balance_eth'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('manual_balance_eth') }}</strong>
                        </div>
                    @endif
                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">Обновить</button>

        </form>
    </div>
@endsection