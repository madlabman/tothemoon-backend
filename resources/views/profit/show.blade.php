@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Доход
            @if (!empty($profit))
                от {{ $profit->created_at->formatLocalized('%d / %m / %Y') }}
            @endif
        </h2>
        <div class="uk-text-center">
            @if (!empty($profit))
                <div class="ml-auto">
                    <a href="{{ url('/profit/delete/' . $profit->id) }}" class="uk-button uk-button-danger">Удалить</a>
                </div>
            @endif
        </div>
    </div>
    <div class="uk-container uk-padding">
        <form action="{{ $_SERVER['PHP_SELF'] }}" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">Сумма</label>
                    <div class="uk-form-controls">
                        <input type="number" step="any" value="@if (!empty($profit)){{ $profit->amount }}@endif" name="amount" id="amount" class="uk-input">
                    </div>
                    @if ($errors->has('amount'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('amount') }}</strong>
                        </div>
                    @endif
                </div>

            </fieldset>
            <button type="submit" class="uk-button uk-button-primary">
                @if(!empty($profit))
                    Обновить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>
@endsection
