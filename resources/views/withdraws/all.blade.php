@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Выплаты
        </h2>
    </div>

    <div class="uk-container uk-padding">
        <div uk-grid>
            @foreach($withdraws as $withdraw)

            <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-2@m">
                <h3 class="uk-card-title">{{ $withdraw->amount }} BTC</h3>
                <p>
                    На адрес: <em>{{ $withdraw->wallet }}</em><br>
                    Пользователь: <em><small>{{ $withdraw->user->phone }}</small></em>
                </p>
                <div>
                    @if($withdraw->is_confirmed == false)
                        <a href="{{ url('/withdraws/confirm/' . $withdraw->id) }}" class="uk-button uk-button-primary uk-button-small">Подтвердить перевод</a>
                    @endif
                    <a href="{{ url('/withdraws/delete/' . $withdraw->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
                </div>
            </div>

            @endforeach
        </div>
    </div>
@endsection
