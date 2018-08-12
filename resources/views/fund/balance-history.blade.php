@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            История изменения баланса
        </h2>
    </div>

    <div class="uk-container uk-padding">
        <form method="get" class="uk-text-center">
            <input type="text" name="date_from" value="{{ $date_from }}" title="Date from" class="uk-input uk-form-width-medium" placeholder="Записи новее даты...">
            &nbsp;-&nbsp;
            <input type="text" name="date_to" value="{{ $date_to }}" title="Date to" class="uk-input uk-form-width-medium" placeholder="Записее старее даты...">
            &nbsp;
            <button class="uk-button uk-button-danger">Показать</button>

            <script>
                $('input[name=date_from], input[name=date_to]').flatpickr({
                    enableTime: true,
                    time_24hr: true,
                    locale: "ru"
                });
            </script>
        </form>
    </div>

    <div class="uk-container uk-padding">

        <table class="uk-table uk-table-small uk-table-middle uk-table-divider">
            <caption></caption>
            <thead>
            <tr>
                <th>Дата</th>
                <th>Состав баланса</th>
            </tr>
            </thead>
            <tbody>
            @foreach($history as $entry)
                <tr>
                    <td>{{ $entry->created_at->timezone(config('app.TZ')) }}</td>
                    <td>
                        <table class="uk-table uk-table-small uk-table-striped">
                            @foreach($entry->toArray() as $symbol => $value)
                                <tbody>
                                    <tr>
                                        <td>{{ $symbol }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection