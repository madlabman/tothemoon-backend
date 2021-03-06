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
                <p>
                    Баланс фонда, USD: <em>{{ round($fund->balance_usd, 3) }}</em><br>
                    <small>Последнее обновление <em>{{ $fund->updated_at }}</em></small>
                </p>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">Количество токенов</label>
                <div class="uk-form-controls">
                    <input type="number" step="any" value="@if (!empty($fund)){{ $fund->token_count }}@endif"
                           name="token_count" class="uk-input">
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
                    <input type="number" step="any" value="@if (!empty($fund)){{ $fund->token_price }}@endif"
                           name="token_price" class="uk-input">
                </div>
                @if ($errors->has('token_price'))
                <div class="uk-alert-danger" uk-alert>
                    <strong>{{ $errors->first('token_price') }}</strong>
                </div>
                @endif
            </div>

            <hr class="uk-divider-icon">

            <div class="uk-margin">
                <label class="uk-form-label">Резерв USD</label>
                <div class="uk-form-controls">
                    <input type="text" disabled="" value="@if (!empty($fund)){{ $fund->reserve_usd }}@endif"
                           name="reserve_usd" id="reserve_usd" class="uk-input">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">Капитал бирж USD</label>
                <div class="uk-form-controls">
                    <input type="text" disabled="" value="@if (!empty($fund)){{ $fund->capital_market }}@endif"
                           id="reserve_usd" class="uk-input">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">Капитал Etherscan USD</label>
                <div class="uk-form-controls">
                    <input type="text" disabled="" value="@if (!empty($fund)){{ $fund->capital_etherscan }}@endif"
                           id="reserve_usd" class="uk-input">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">Капитал Blockchain USD</label>
                <div class="uk-form-controls">
                    <input type="text" disabled="" value="@if (!empty($fund)){{ $fund->capital_blockchain }}@endif"
                           id="reserve_usd" class="uk-input">
                </div>
            </div>

            <hr class="uk-divider-icon">

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
                @foreach($fund->coins->sortByDesc('amount') as $coin)
                <div class="uk-margin" id="coin-{{ $coin->symbol }}">
                    <label class="uk-form-label">Дополнительные {{ strtoupper($coin->symbol) }}</label>
                    <div class="uk-form-controls">
                        <div uk-grid>
                            <div class="uk-width-2-3">
                                <input type="number" step="any" value="{{ $coin->amount }}"
                                       name="coin[{{ $coin->symbol  }}]" class="uk-input">
                            </div>
                            <div class="uk-width-1-3">
                                <button class="uk-button uk-button-danger" style="padding: 0; width: 100%;"
                                        data-coin="{{ $coin->symbol }}">Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            <div class="uk-margin">
                <label class="uk-form-label">Добавить монеты</label>
                <div class="uk-form-controls">
                    <select id="coin-select" class="uk-select">
                        @foreach(\App\CryptoCurrency::all()->sortBy('name') as $coin)
                        <option value="{{ $coin->symbol }}">{{ $coin->name }}</option>
                        @endforeach
                    </select>
                    <style>
                        #coin-select option {
                            padding: 8px;
                        }
                    </style>
                    <script src="{{ asset('js/jquery.select-filter.js') }}"></script>
                    <script>
                        $(document).ready(function () {
                            const $select = $('#coin-select');
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
                            });
                            // Filter
                            $select.selectFilter({
                                'filterClass': 'uk-input',
                                'inputLocation': 'above',
                                'minimumSelectElementSize': 5,
                                'width': -1,
                                'inputPlaceholder': 'Введите название монеты...',
                            });

                            // Delete coin
                            $('button[data-coin]').click(function (e) {
                                e.preventDefault();
                                let $symbol = $(this).attr('data-coin');
                                let self = $(this);
                                // Make request
                                $.ajax('/fund/{{ $fund->id }}/delete-coin?symbol=' + $symbol, {
                                    method: 'GET',
                                    beforeSend: function () {
                                        self.html('...');
                                    },
                                    success: function () {
                                        $('#coin-' + $symbol).remove();
                                    },
                                    error: function () {
                                        self.html('Ошибка!');
                                    }
                                })
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