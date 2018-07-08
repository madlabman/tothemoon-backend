@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Пользователи
        </h2>
        <div class="uk-text-center">
            <a href="{{ url('/users/new') }}" class="uk-button uk-button-primary">Добавить пользователя</a>
        </div>
    </div>

    <div class="uk-container uk-padding">
        <div uk-grid>
            @foreach($users as $user)

                <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-3@m">
                    <h3 class="uk-card-title uk-text-small">
                        {{ $user->name }}
                    </h3>
                    <p>{{ $user->phone }}</p>
                    <p>
                        @if(!empty($user->balance))
                            Баланс: <em>{{ round($user->balance->body, 2) }} $</em><br>
                            Бонус: <em>{{ round($user->balance->bonus, 2) }} $</em>
                        @endif
                    </p>
                    <div>
                        <a href="{{ url('/users/edit/' . $user->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                        <a href="{{ url('/users/delete/' . $user->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
                    </div>
                </div>

            @endforeach
        </div>

        @if(!empty($pages))
            <ul class="uk-pagination uk-flex-center uk-padding">
                @foreach($pages as $page)
                    <li class="@if($page['active']){{ 'uk-active' }}@endif"><a href="{{ $page['link'] }}">{{ $page['text'] }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
