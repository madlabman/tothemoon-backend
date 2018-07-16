@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Команда
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
                    <p>Выберите администратора команды</p>
                    <select id="user-select" class="uk-select" name="user">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                @if(!empty($command) && $command->admin->id === $user->id){{ 'selected' }}@endif>
                                {{ $user->name }}
                            </option>
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

                    <label class="uk-form-label">Название команды</label>
                    <div class="uk-form-controls">
                        <input type="text"
                               name="name"
                               class="uk-input"
                               value="@if(!empty($command)){{ $command->name }}@endif">
                    </div>

                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">
                @if(!empty($command))
                    Обновить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>
@endsection
