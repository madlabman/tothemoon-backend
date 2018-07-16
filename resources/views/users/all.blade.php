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

                <table class="uk-table uk-table-middle">
                    <thead>
                        <tr>
                            <th>Пользователь</th>
                            <th>Тело</th>
                            <th>Бонус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <form action="{{ url()->to('/users/quick-update') }}" method="post">
                                <td>{{ $user->name }} [<em><small>{{ $user->phone }}</small></em>]</td>
                                {{ csrf_field() }}
                                <input type="hidden" value="{{ $user->id }}" name="user">
                                <td><input type="number" value="{{ $user->balance->body }}" name="body" class="uk-input"></td>
                                <td><input type="number" value="{{ $user->balance->bonus }}" name="bonus" class="uk-input"></td>
                                <td>
                                    <button type="submit" class="uk-button uk-button-primary uk-button-small">Обновить</button>
                                    <a href="{{ url('/users/edit/' . $user->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                                    <a href="{{ url('/users/delete/' . $user->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

        @if(!empty($pages))
            <ul class="uk-pagination uk-flex-center uk-padding">
                @foreach($pages as $page)
                    <li class="@if($page['active']){{ 'uk-active' }}@endif"><a href="{{ $page['link'] }}">{{ $page['text'] }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
