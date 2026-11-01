<!DOCTYPE html>
<html>
<head>
    <title>Churn Risk Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; }
        th { background-color: #f1f5f9; }
        .high-risk { color: red; }
        .low-risk { color: green; }
    </style>
</head>
<body>
    <h1>Customer Retention Risk Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Recency</th>
                <th>Frequency</th>
                <th>Monetary</th>
                <th>Churn Prob</th>
                <th>Risk Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['recency'] }} days</td>
                <td>{{ $customer['frequency'] }}</td>
                <td>${{ number_format($customer['monetary'], 2) }}</td>
                <td>{{ round($customer['churn_probability'], 1) }}%</td>
                <td class="{{ $customer['risk_label'] === 'High Risk' ? 'high-risk' : 'low-risk' }}">
                    {{ $customer['risk_label'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>