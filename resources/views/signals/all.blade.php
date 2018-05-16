@extends('layouts.app')

@section('content')
    <div class="uk-container uk-padding">
        <h2 class="uk-text-center">
            Сигналы
        </h2>
        <div class="uk-text-center">
            <a href="{{ url('/signals/new') }}" class="uk-button uk-button-primary">Добавить новый сигнал</a>
        </div>
    </div>

    <div class="uk-container uk-padding">
        <div uk-grid>
            @foreach($signals as $signal)

                <div class="uk-card uk-card-small uk-card-hover uk-light uk-card-secondary uk-card-body uk-width-1-2@m">
                    <h3 class="uk-card-title uk-text-small">
                        @php($style = 'background-color:')
                        @switch($signal->level)
                            @case(1)
                            @php($style .= 'red;')
                            @break
                            @case(2)
                            @php($style .= 'yellow;')
                            @break
                            @case(4)
                            @php($style .= 'green;')
                            @break
                            @default
                            @php($style .= '#adadad;')
                        @endswitch
                        <span style="display: inline-block; border-radius: 50%; width: 10px; height: 10px; margin-right: 4px; {{ $style }}"></span>
                        {{ $signal->created_at->diffForHumans() }}
                    </h3>
                    <p>{{ $signal->info }}</p>
                    <div>
                        <a href="{{ url('/signals/edit/' . $signal->id) }}" class="uk-button uk-button-primary uk-button-small">Редактировать</a>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
@endsection
