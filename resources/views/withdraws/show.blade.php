@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Произвести выплату
        </h2>
    </div>
    <div class="uk-container uk-padding">
        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <!-- Выбрать пользователя -->

                <style>
                    #user-select option {
                        padding: 8px;
                    }
                </style>

                <div class="uk-margin">
                    <select id="user-select" class="uk-select" name="user">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-balance="{{ $user->balance }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <script src="{{ asset('js/jquery.select-filter.js') }}"></script>
                <script>
                    $(document).ready(function () {
                        const $select = $('#user-select');
                        // Assign symbols with existent values
                        $select.on('change', function () {
                            let $option = $(this).find('option:selected');
                            let balance = +$option.attr('data-balance');
                            $('input[name="amount"]').attr('max', balance);
                            $('#max-amount-value').html(balance);
                            $('#amount-input').show();
                        });
                        // Filter
                        $select.selectFilter({
                            'filterClass': 'uk-input',
                            'inputLocation': 'above',
                            'minimumSelectElementSize': 5,
                            'width': -1,
                            'inputPlaceholder': 'Выберите пользователя...',
                        });
                    });
                </script>

                <!-- Получить доступный для вывода баланса -->

                <div class="uk-margin" id="amount-input" style="display: none;">

                    <p>Доступная сумма для снятия: <span id="max-amount-value">0</span> $</p>

                    <label class="uk-form-label">Введите сумму в долларах</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" min="0" name="amount" class="uk-input">
                    </div>
                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">Отправить</button>
        </form>
    </div>
@endsection
