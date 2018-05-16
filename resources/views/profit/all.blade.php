@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Доход фонда
        </h2>
        <div class="uk-text-center">
            <a href="{{ url('/profit/new') }}" class="uk-button uk-button-primary">Добавить новое значение</a>
        </div>
    </div>

    <div class="uk-container uk-padding">
        <div uk-grid>
            @foreach($profits as $profit)
                <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-3@m">
                    <h3 class="uk-card-title uk-text-small">
                        {{ $profit->created_at->formatLocalized('%d / %m / %Y') }}
                    </h3>
                    <p>
                        <span class="uk-text-large">{{ $profit->amount }} BTC</span>
                    </p>
                    <div>
                        <a href="{{ url('/profit/edit/' . $profit->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
