@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>Выплаты</div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($withdraws as $withdraw)
                            <div class="border p-4 mb-2">
                                <div>
                                    <p>Сумма: <span style="font-family: var(--font-family-monospace);">{{ $withdraw->amount }} BTC</span></p>
                                    <p>На адрес: <span style="font-family: var(--font-family-monospace);">{{ $withdraw->wallet }}</span></p>
                                    <p>
                                        Пользователь:
                                        <span style="font-family: var(--font-family-monospace);">
                                            {{ $withdraw->user->phone }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    @if($withdraw->is_confirmed == false)
                                        <a href="{{ url('/withdraws/confirm/' . $withdraw->id) }}" class="btn btn-primary">Подтвердить перевод</a>
                                    @endif
                                    <a href="{{ url('/withdraws/delete/' . $withdraw->id) }}" class="btn btn-danger">Удалить</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
