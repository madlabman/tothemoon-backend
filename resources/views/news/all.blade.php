@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">Новости</h2>
        <div class="uk-text-center">
            <a href="{{ url('/news/new') }}" class="uk-button uk-button-primary">Добавить новость</a>
        </div>

        <div uk-grid>
            @foreach($news as $post)

                <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-2@m">
                    <p><em>{{ $post->created_at->timezone(config('app.TZ')) }}</em></p>
                    <h3 class="uk-card-title">{{ $post->title }}</h3>
                    <a href="{{ url('/news/edit/' . $post->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                    <a href="{{ url('/news/delete/' . $post->id) }}" class="uk-button uk-button-danger uk-button-small">Удалить</a>
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
