@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            @if (!empty($user))
                &nbsp;{{ $user->name }}
            @endif
        </h2>
        <div class="uk-text-center">
            @if (!empty($user))
                <div class="ml-auto">
                    <a href="{{ url('/users/delete/' . $user->id) }}" class="uk-button uk-button-danger">Удалить</a>
                </div>
            @endif
        </div>
    </div>
    <div class="uk-container uk-padding">
        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">Имя</label>
                    <div class="uk-form-controls">
                        <input type="text" name="name" class="uk-input" value="@if (!empty($user)){{ $user->name }}@endif">
                    </div>
                    @if ($errors->has('name'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Логин</label>
                    <div class="uk-form-controls">
                        <input type="text" name="login" class="uk-input" value="@if (!empty($user)){{ $user->login }}@endif">
                    </div>
                    @if ($errors->has('login'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('login') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Телефон</label>
                    <div class="uk-form-controls">
                        <input type="text" name="phone" class="uk-input" value="@if (!empty($user)){{ $user->phone }}@endif">
                    </div>
                    @if ($errors->has('phone'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('phone') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Уровень</label>
                    <div class="uk-form-controls">
                        <input type="number" min="1" max="5" name="invest_level" class="uk-input" value="@if (!empty($user)){{ $user->invest_level }}@endif">
                    </div>
                    @if ($errors->has('invest_level'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('invest_level') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Доступ к сигналам</label>
                    <div class="uk-form-controls">
                        <select name="signal_access" id="level" class="uk-select" required>
                            <option value="1" {{ $user->signal_access == 1 ? 'selected' : '' }}>Красный</option>
                            <option value="2" {{ $user->signal_access == 2 ? 'selected' : '' }}>Желтый</option>
                            <option value="4" {{ $user->signal_access == 4 ? 'selected' : '' }}>Зеленый</option>
                        </select>
                    </div>
                    @if ($errors->has('signal_access'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('signal_access') }}</strong>
                        </div>
                    @endif
                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">
                @if(!empty($user))
                    Обновить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>
@endsection
