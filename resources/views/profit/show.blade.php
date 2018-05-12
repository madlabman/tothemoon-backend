@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            Доход
                            @if (!empty($profit))
                                от {{ $profit->created_at->formatLocalized('%d / %m / %Y') }}
                            @endif
                        </div>
                        @if (!empty($profit))
                        <div class="ml-auto">
                            <a href="{{ url('/profit/delete/' . $profit->id) }}" class="btn btn-danger">Удалить</a>
                        </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <form action="{{ $_SERVER['PHP_SELF'] }}" method="post">
                                @csrf

                                <div class="form-group row">
                                    <label for="amount" class="col-sm-2 col-form-label text-md-right">{{ __(' Сумма') }}</label>

                                    <div class="col-md-8">
                                        <input type="number" step="any" value="@if (!empty($profit)){{ $profit->amount }}@endif" name="amount" id="amount" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}">

                                        @if ($errors->has('amount'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('amount') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary ml-auto">
                                        @if(!empty($profit))
                                            Обновить
                                        @else
                                            Создать
                                        @endif
                                    </button>
                                </div>

                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
