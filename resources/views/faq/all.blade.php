@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Часто задаваемые вопросы
        </h2>
        <div class="uk-text-center">
            <a href="{{ url('/faq/new') }}" class="uk-button uk-button-primary">Добавить FAQ</a>
        </div>
    </div>

    <div class="uk-container uk-padding">

                <table class="uk-table uk-table-middle">
                    <thead>
                        <tr>
                            <th>Вопрос</th>
                            <th>Ответ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->question }}</td>
                            <td>{!! $post->answer !!}</td>
                            <td>
                                <a href="{{ url('/faq/edit/' .  $post->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                                <a href="{{ url('/faq/delete/' .  $post->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
                            </td>
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
