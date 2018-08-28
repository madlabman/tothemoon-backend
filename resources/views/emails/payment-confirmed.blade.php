<table>
    <tr>
        <td colspan="4">Отчет о подтверждении пополнения</td>
    </tr>
    <tr>
        <th>Пользователь</th>
        <th>Номер телефона</th>
        <th>Сумма, BTC</th>
        <th>Дата</th>
    </tr>
    <tr>
        <td>{{ $payment->user->name }}</td>
        <td>{{ $payment->user->phone }}</td>
        <td>{{ $payment->amount }}</td>
        <td>{{ $payment->updated_at }}</td>
    </tr>
</table>