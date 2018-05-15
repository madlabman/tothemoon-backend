@extends('layouts.app')

@section('content')
<div class="uk-container uk-container-center">
    <h1 class="uk-text-center">{{ __('Аутентификация') }}</h1>
    <form method="POST" action="{{ route('login') }}" class="uk-width-medium uk-align-center">
        @csrf
        <fieldset class="uk-fieldset">

            <div class="uk-margin">
                <div class="uk-inline uk-display-block">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input" type="text" placeholder="{{ __('Логин') }}" name="login" value="{{ old('login') }}" required autofocus>
                </div>
                @if ($errors->has('login'))
                    <div class="uk-alert-danger" uk-alert>
                        <strong>{{ $errors->first('login') }}</strong>
                    </div>
                @endif
            </div>

            <div class="uk-margin">
                <div class="uk-inline uk-display-block">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input class="uk-input" type="password" placeholder="{{ __('Пароль') }}" name="password" value="{{ old('password') }}" required>
                </div>
                @if ($errors->has('password'))
                    <span class="uk-alert-danger" uk-alert>
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="uk-margin">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="uk-checkbox"> {{ __('Запомнить меня') }}
                </label>
            </div>

        </fieldset>

        <button class="uk-button uk-button-default">Войти</button>
    </form>
</div>
@endsection
