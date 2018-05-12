@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Управление фондом</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('signals') }}">Сигналы</a>
                        <a class="nav-link" href="{{ route('payments') }}">Пополнения</a>
                        <a class="nav-link" href="{{ route('withdraws') }}">Выплаты</a>
                        <a class="nav-link" href="{{ route('profit') }}">Суточный доход</a>
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
