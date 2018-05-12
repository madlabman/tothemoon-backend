@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            Сигнал
                            @if (!empty($signal))
                                &nbsp;#{{ $signal->id }}
                            @endif
                        </div>
                        @if (!empty($signal))
                        <div class="ml-auto">
                            <a href="{{ url('/signals/delete/' . $signal->id) }}" class="btn btn-danger">Удалить</a>
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
                                    <label for="level" class="col-sm-2 col-form-label text-md-right">{{ __('Уровень') }}</label>

                                    <div class="col-md-3">
                                        <select name="level" id="level" class="form-control{{ $errors->has('level') ? ' is-invalid' : '' }}" required>
                                            <option value="1" {{ empty($signal) ? '' : $signal->level == 1 ? 'selected' : '' }}>Красный</option>
                                            <option value="2" {{ empty($signal) ? '' : $signal->level == 2 ? 'selected' : '' }}>Желтый</option>
                                            <option value="4" {{ empty($signal) ? '' : $signal->level == 4 ? 'selected' : '' }}>Зеленый</option>
                                        </select>

                                        @if ($errors->has('level'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('level') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="info" class="col-sm-2 col-form-label text-md-right">{{ __('Описание') }}</label>

                                    <div class="col-md-8">
                                        <textarea class="form-control{{ $errors->has('level') ? ' is-invalid' : '' }}" id="info" name="info" rows="10">@if (!empty($signal)){{ $signal->info }}@endif</textarea>

                                        @if ($errors->has('info'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('info') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary ml-auto">
                                        @if(!empty($signal))
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
