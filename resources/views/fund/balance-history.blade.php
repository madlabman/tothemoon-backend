@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            История изменения баланса
        </h2>
    </div>
    <div class="uk-container uk-padding">

        <table class="uk-table uk-table-small uk-table-middle uk-table-divider">
            <caption></caption>
            <thead>
            <tr>
                <th>Дата</th>
                <th>Монеты</th>
                <th width="200px">Количество</th>
            </tr>
            </thead>
            <tbody>
            @foreach($history as $entry)
                <tr>
                    <td>{{ $entry->created_at }}</td>
                    <td>
                        <select class="coin-select uk-select" data-target="amount-{{ $entry->id }}">
                            @foreach($entry->toArray() as $symbol => $value)
                                <option value="{{ $value }}">{{ $symbol }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td id="amount-{{ $entry->id }}">
                        Выберите монету
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <style>
            .coin-select option {
                padding: 8px;
            }
        </style>
        <script src="{{ asset('js/jquery.select-filter.js') }}"></script>
        <script>
            $(document).ready(function () {
                $('.coin-select').each(function () {
                    // Assign symbols with existent values
                    $(this).on('change', function () {
                        let $symbol = $(this).find('option:selected').val();
                        if ($symbol.length === 0) return;
                        $('#' + $(this).attr('data-target')).html($symbol);
                    });
                });


                // Filter
                $('.coin-select').selectFilter({
                    'filterClass': 'uk-input',
                    'inputLocation': 'above',
                    'minimumSelectElementSize': 2,
                    'width': -1,
                    'inputPlaceholder': 'Введите название монеты...',
                });
            });
        </script>

    </div>
@endsection