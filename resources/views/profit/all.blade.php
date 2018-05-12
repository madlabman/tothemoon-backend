@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>Доход фонда</div>
                        <div class="ml-auto">
                            <a href="{{ url('/profit/new') }}" class="btn btn-outline-dark">Создать</a>
                        </div>
                    </div>

                    <div class="card-body d-flex">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($profits as $profit)
                                <div class="p-4 mb-2 col-md-6">
                                    <p class="badge badge-dark">{{ $profit->created_at->formatLocalized('%d / %m / %Y') }}</p>
                                    <p style="font-family: var(--font-family-monospace);">{{ $profit->amount }} BTC</p>
                                    <div>
                                        <a href="{{ url('/profit/edit/' . $profit->id) }}" class="btn btn-primary">Редактировать</a>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
