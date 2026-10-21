<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Churn Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .High-Risk { background-color: #ffcccc; }
        .Moderate-Risk { background-color: #ffeeba; }
        .Low-Risk { background-color: #d4edda; }
    </style>
</head>
<body>
    <div class="card">
        <h1>📊 Customer Churn Predictor</h1>
        <p>This data was processed by the R Machine Learning Engine.</p>
        
        @if($r_error)
            <div style="color: red; background: #fee; padding: 10px;">
                <strong>R Script Output/Error:</strong><br>
                <pre>{{ $r_error }}</pre>
            </div>
        @endif
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Recency</th>
                    <th>Frequency</th>
                    <th>Monetary</th>
                    <th>Risk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr class="{{ str_replace(' ', '-', $customer['risk_label'] ?? '') }}">
                    <td>{{ $customer['id'] ?? '' }}</td>
                    <td>{{ $customer['name'] ?? '' }}</td>
                    <td>{{ $customer['recency'] ?? '' }}</td>
                    <td>{{ $customer['frequency'] ?? '' }}</td>
                    <td>${{ number_format($customer['monetary'] ?? 0, 2) }}</td>
                    <td><strong>{{ $customer['risk_label'] ?? '' }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>