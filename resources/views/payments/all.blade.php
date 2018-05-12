@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>Пополнения баланса</div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($payments as $payment)
                            <div class="border p-4 mb-2">
                                <div>
                                    <p>Сумма: <span style="font-family: var(--font-family-monospace);">{{ $payment->amount }} BTC</span></p>
                                    <p>Отправитель: <span style="font-family: var(--font-family-monospace);">{{ $payment->wallet }}</span></p>
                                </div>
                                <div>
                                    @if($payment->is_confirmed == false)
                                        <a href="{{ url('/payments/confirm/' . $payment->id) }}" class="btn btn-primary">Подтвердить</a>
                                    @endif
                                    <a href="{{ url('/payments/delete/' . $payment->id) }}" class="btn btn-danger">Удалить</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
