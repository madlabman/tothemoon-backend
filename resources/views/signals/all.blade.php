@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>Сигналы</div>
                        <div class="ml-auto">
                            <a href="{{ url('/signals/new') }}" class="btn btn-outline-dark">Создать</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($signals as $signal)
                                <div class="border p-4 mb-2">
                                    <p style="color: #888">
                                        @switch($signal->level)
                                            @case(1)
                                                @php($badge_class = 'danger')
                                                @break
                                            @case(2)
                                                @php($badge_class = 'warning')
                                                @break
                                            @case(4)
                                                @php($badge_class = 'success')
                                                @break
                                            @default
                                                @php($badge_class = 'light')
                                        @endswitch
                                        <span class="badge badge-{{ $badge_class }}">&nbsp;</span>
                                        {{ $signal->created_at->diffForHumans() }}
                                    </p>
                                    <p>{{ $signal->info }}</p>
                                    <div>
                                        <a href="{{ url('/signals/edit/' . $signal->id) }}" class="btn btn-primary">Редактировать</a>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
