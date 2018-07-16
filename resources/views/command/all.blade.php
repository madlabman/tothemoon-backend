@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Команды
        </h2>
        <div class="uk-text-center">
            <a href="{{ url('/commands/new') }}" class="uk-button uk-button-primary">Добавить команду</a>
        </div>
    </div>

    <div class="uk-container uk-padding">

        <table class="uk-table uk-table-middle">
            <thead>
            <tr>
                <th>Название</th>
                <th>Администратор</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($commands as $command)
                <tr>
                    <td>{{ $command->name }}</td>
                    <td>{{ $command->admin->name }}</td>
                    <td>
                        <a href="{{ url('/commands/edit/' . $command->id) }}"
                           class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                        <a href="{{ url('/commands/delete/' . $command->id) }}"
                           class="uk-button uk-button-danger uk-button-small">Удалить</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if(!empty($pages))
            <ul class="uk-pagination uk-flex-center uk-padding">
                @foreach($pages as $page)
                    <li class="@if($page['active']){{ 'uk-active' }}@endif">
                        <a href="{{ $page['link'] }}">{{ $page['text'] }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
