@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Произвести начисление
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
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <script src="{{ asset('js/jquery.select-filter.js') }}"></script>
                <script>
                    $(document).ready(function () {
                        // Filter
                        $('#user-select').selectFilter({
                            'filterClass': 'uk-input',
                            'inputLocation': 'above',
                            'minimumSelectElementSize': 5,
                            'width': -1,
                            'inputPlaceholder': 'Выберите пользователя...',
                        });
                    });
                </script>

                <!-- Получить доступный для вывода баланса -->

                <div class="uk-margin" id="amount-input">

                    <p>Стоимость одного токена: {{ $token_price }} $</p>

                    <label class="uk-form-label">Введите сумму в токенах</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" min="0" name="amount" class="uk-input">
                    </div>

                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">Отправить</button>
        </form>
    </div>
@endsection
