@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">Новый пользователь</h2>
    </div>
    <div class="uk-container uk-padding">
        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">Имя</label>
                    <div class="uk-form-controls">
                        <input type="text" name="name" class="uk-input" value="{{ old('name') }}">
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
                        <input type="text" name="login" class="uk-input" value="{{ old('login') }}">
                    </div>
                    @if ($errors->has('login'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('login') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Пароль</label>
                    <div class="uk-form-controls">
                        <input type="password" name="password" class="uk-input" value="{{ old('password') }}">
                    </div>
                    @if ($errors->has('password'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Телефон</label>
                    <div class="uk-form-controls">
                        <input type="text" name="phone" class="uk-input phone-input" value="{{ old('phone') }}">
                    </div>
                    @if ($errors->has('phone'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('phone') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Email</label>
                    <div class="uk-form-controls">
                        <input type="text" name="email" class="uk-input" value="{{ old('email') }}">
                    </div>
                    @if ($errors->has('email'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('email') }}</strong>
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

    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.phone-input').mask('+7 (999) 999-99-99')
        });
    </script>

@endsection
