@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            Сигнал
            @if (!empty($signal))
                &nbsp;#{{ $signal->id }}
            @endif
        </h2>
        <div class="uk-text-center">
            @if (!empty($signal))
                <div class="ml-auto">
                    <a href="{{ url('/signals/delete/' . $signal->id) }}" class="uk-button uk-button-danger">Удалить</a>
                </div>
            @endif
        </div>
    </div>
    <div class="uk-container uk-padding">
        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">Уровень</label>
                    <div class="uk-form-controls">
                        <select name="level" id="level" class="uk-select" required>
                            <option value="1" {{ empty($signal) ? '' : $signal->level == 1 ? 'selected' : '' }}>Красный</option>
                            <option value="2" {{ empty($signal) ? '' : $signal->level == 2 ? 'selected' : '' }}>Желтый</option>
                            <option value="4" {{ empty($signal) ? '' : $signal->level == 4 ? 'selected' : '' }}>Зеленый</option>
                        </select>
                    </div>
                    @if ($errors->has('level'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('level') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">Описание</label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" id="info" name="info" rows="10">@if (!empty($signal)){{ $signal->info }}@endif</textarea>
                    </div>
                    @if ($errors->has('info'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('info') }}</strong>
                        </div>
                    @endif
                </div>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">
                @if(!empty($signal))
                    Обновить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>
@endsection
