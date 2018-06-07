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

                <div id="coins">
                    @foreach($fund->coins as $coin)
                        <div class="uk-margin" id="coin-{{ strtolower($coin->sym) }}">
                            <label class="uk-form-label">Дополнительные {{ strtoupper($coin->sym) }}</label>
                            <div class="uk-form-controls">
                                <input type="number" step="any" value="{{ $coin->amount }}"
                                       name="coin[{{ $coin->sym  }}]" class="uk-input">
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="uk-margin">
                    <label class="uk-form-label">Добавить монеты</label>
                    <div class="uk-form-controls">
                        <select id="coin-select" class="uk-select">
                            <option id="coin-select-loading">Загрузка...</option>
                        </select>
                        <script>
                            $(document).ready(function () {
                                const $select = $('#coin-select');
                                // return;
                                // Getting coins symbols from API
                                $.ajax({
                                    type: 'GET',
                                    url: 'https://api.coinmarketcap.com/v2/listings/',
                                    dataType: 'json',
                                    success: function (response) {
                                        $('#coin-select-loading').remove();
                                        $('<option>', {
                                            value: '',
                                            text: 'Выберите монету',
                                        }).appendTo($select);
                                        $.each(response.data, function (i, item) {
                                            // Insert symbols to select
                                            $('<option>', {
                                                value: item.symbol.toLowerCase(),
                                                text: item.name,
                                            }).appendTo($select);
                                            // console.log(item.symbol.toLowerCase());
                                            // console.log(item.name);
                                        })
                                    }
                                });
                                // Assign symbols with existent values
                                $select.on('change', function () {
                                    let $symbol = $(this).find('option:selected').val();
                                    if ($symbol.length === 0) return;
                                    let $symbol_wrapper_id = 'coin-' + $symbol;
                                    let $symbol_wrapper = $('#' + $symbol_wrapper_id);
                                    if ($symbol_wrapper.length) {
                                        $symbol_wrapper.find('input').focus();
                                    } else {
                                        // Add new element on page
                                        $symbol_wrapper = $('<div>', {
                                            class: 'uk-margin',
                                            id: $symbol_wrapper_id
                                        }).appendTo('#coins');

                                        $('<label>', {
                                            class: 'uk-form-label',
                                            text: 'Дополнительные ' + $symbol.toUpperCase()
                                        }).appendTo($symbol_wrapper);

                                        const $form_control = $('<div>', {
                                            class: 'uk-form-controls'
                                        }).appendTo($symbol_wrapper);

                                        $('<input>', {
                                            value: 0,
                                            name: 'coin[' + $symbol + ']',
                                            class: 'uk-input',
                                            type: 'number',
                                            step: 'any',
                                        }).appendTo($form_control);
                                    }
                                })
                            });
                        </script>
                    </div>
                </div>

                <div class="uk-margin">
                    <h3>Уровни инвестирования</h3>
                    @foreach(App\LevelCondition::orderBy('min_usd_amount')->orderBy('max_duration')->get() as $level)
                        <div class="uk-text-small">
                            <p><b>{{ $level->title }}:</b>
                                <em>{{ $level->min_duration }} - {{ $level->max_duration }}</em> месяцев &
                                <em>{{ $level->min_usd_amount }} - {{ $level->max_usd_amount }}</em> долларов
                            </p>
                        </div>
                    @endforeach
                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">Обновить</button>

        </form>
    </div>
@endsection