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
