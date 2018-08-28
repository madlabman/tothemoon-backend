<table>
    <tr>
        <td colspan="4">Отчет о подтверждении выплаты</td>
    </tr>
    <tr>
        <th>Пользователь</th>
        <th>Номер телефона</th>
        <th>Сумма, BTC</th>
        <th>Кошелек</th>
        <th>Дата</th>
    </tr>
    <tr>
        <td>{{ $withdraw->user->name }}</td>
        <td>{{ $withdraw->user->phone }}</td>
        <td>{{ $withdraw->amount }}</td>
        <td>{{ $withdraw->wallet }}</td>
        <td>{{ $withdraw->updated_at }}</td>
    </tr>
</table>