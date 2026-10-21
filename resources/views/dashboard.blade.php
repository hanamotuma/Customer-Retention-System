<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Retention System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #eef1f6; color: #333; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

        /* --- APP CONTAINER --- */
        .app-container {
            display: flex;
            width: 1400px;
            height: 900px;
            background: #ffffff;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 10px solid #e2e8f0;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 90px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            border-right: 1px solid #f1f5f9;
        }
        .sidebar-logo { font-size: 1.5rem; color: #8b5cf6; margin-bottom: 50px; font-weight: 700; }
        .nav-icon { 
            width: 45px; height: 45px; border-radius: 15px; display: flex; justify-content: center; align-items: center;
            margin-bottom: 20px; color: #94a3b8; cursor: pointer; transition: all 0.3s;
        }
        .nav-icon:hover, .nav-icon.active { background: #8b5cf6; color: white; box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); }

        /* --- MAIN DASHBOARD --- */
        .main-dashboard {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            background: #f8fafc;
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 1.8rem; color: #1e293b; font-weight: 600; }
        
        /* --- GRID LAYOUT --- */
        .grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: auto auto auto;
            gap: 25px;
        }

        /* --- CARDS --- */
        .card { border-radius: 30px; padding: 30px; color: white; position: relative; overflow: hidden; }
        
        /* Large Purple Card */
        .card-primary { 
            grid-column: 1 / 3; 
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); 
            display: flex; flex-direction: column; justify-content: space-between; min-height: 250px;
        }
        .card-primary h2 { font-size: 1.2rem; font-weight: 400; opacity: 0.9; margin-bottom: 10px; }
        .card-primary .big-number { font-size: 3rem; font-weight: 700; }
        
        /* Pink Card */
        .card-secondary { 
            background: linear-gradient(135deg, #f472b6, #db2777); 
            display: flex; flex-direction: column; justify-content: center; align-items: center;
        }
        
        /* White Cards (KPIs) */
        .card-white {
            background: #ffffff;
            color: #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .card-white .icon-box {
            width: 50px; height: 50px; border-radius: 15px; background: #f3e8ff; color: #8b5cf6;
            display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 20px;
        }
        .kpi-title { font-size: 0.9rem; color: #64748b; margin-bottom: 5px; }
        .kpi-value { font-size: 1.8rem; font-weight: 700; color: #1e293b; }
        .progress-bar { height: 6px; background: #e2e8f0; border-radius: 3px; margin-top: 15px; overflow: hidden;}
        .progress-fill { height: 100%; background: #10b981; width: 70%; border-radius: 3px; }

        /* --- RIGHT PANEL --- */
        .right-panel {
            width: 320px;
            background: #ffffff;
            padding: 40px 30px;
            border-left: 1px solid #f1f5f9;
        }
        .right-panel h2 { font-size: 1.1rem; color: #1e293b; margin-bottom: 20px; display: flex; justify-content: space-between;}
        
        /* Chart Container */
        .chart-container { position: relative; width: 100%; height: 200px; margin-top: 20px;}

        /* Table styling for the bottom */
        .table-container { background: white; border-radius: 20px; padding: 20px; margin-top: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 15px; font-size: 0.8rem; color: #64748b; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .badge { padding: 5px 10px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-high { background: #fee2e2; color: #ef4444; }
        .badge-mod { background: #fef3c7; color: #f59e0b; }
        .badge-low { background: #d1fae5; color: #10b981; }

    </style>
</head>
<body>

<div class="app-container">
    
    <!-- 1. SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-chart-pie"></i></div>
        <div class="nav-icon active"><i class="fa-solid fa-house"></i></div>
        <div class="nav-icon"><i class="fa-solid fa-users"></i></div>
        <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="nav-icon"><i class="fa-solid fa-gear"></i></div>
    </div>

    <!-- 2. MAIN DASHBOARD -->
    <div class="main-dashboard">
        <div class="header">
            <div>
                <p style="color: #64748b; font-size: 0.9rem;">Dashboard</p>
                <h1>Customer Retention System</h1>
            </div>
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="background: white; padding: 10px 20px; border-radius: 20px; color: #94a3b8; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
                    <i class="fa-solid fa-search"></i> Search
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=8b5cf6&color=fff" style="border-radius: 50%; width: 45px;">
            </div>
        </div>

        <div class="grid-layout">
            <!-- Large Purple Card -->
            <div class="card card-primary">
                <div style="display: flex; justify-content: space-between;">
                    <h2>Total Managed Customers</h2>
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem;">Live Data</span>
                </div>
                <div>
                    <div class="big-number">{{ count($customers) }}</div>
                    <p style="opacity: 0.8; font-size: 0.9rem;">AI processed successfully</p>
                </div>
            </div>

            <!-- Pink Card (AI Engine) -->
            <div class="card card-secondary">
                <i class="fa-solid fa-microchip" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                <h2 style="font-size: 1.5rem; margin-bottom: 5px;">R Engine</h2>
                <p style="opacity: 0.9; font-size: 0.9rem;">Machine Learning Active</p>
            </div>

            <!-- White KPI 1 -->
            <div class="card card-white">
                <div>
                    <div class="icon-box"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="kpi-title">High Risk Customers</div>
                    <div class="kpi-value" style="color: #ef4444;">
                        {{ count(array_filter($customers, fn($c) => $c['risk_label'] === 'High Risk')) }}
                    </div>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width: 45%; background: #ef4444;"></div></div>
            </div>

            <!-- White KPI 2 -->
            <div class="card card-white">
                <div>
                    <div class="icon-box" style="background: #dcfce7; color: #10b981;"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="kpi-title">Safe Customers</div>
                    <div class="kpi-value" style="color: #10b981;">
                        {{ count(array_filter($customers, fn($c) => $c['risk_label'] === 'Low Risk')) }}
                    </div>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width: 75%;"></div></div>
            </div>

            <!-- White KPI 3 -->
            <div class="card card-white">
                <div>
                    <div class="icon-box" style="background: #fef3c7; color: #f59e0b;"><i class="fa-solid fa-chart-pie"></i></div>
                    <div class="kpi-title">Avg. Churn Prob.</div>
                    <div class="kpi-value">
                        @if(count($customers) > 0)
                            {{ round(array_sum(array_column($customers, 'churn_probability')) / count($customers), 1) }}%
                        @else
                            0%
                        @endif
                    </div>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width: 60%; background: #f59e0b;"></div></div>
            </div>
        </div>

        <!-- Data Table Below Dashboard -->
        <div class="table-container">
            <h2 style="font-size: 1.1rem; margin-bottom: 15px; color: #1e293b;">Recent Customer Risk Profiles</h2>
            <div style="max-height: 300px; overflow-y: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Recency</th>
                            <th>Frequency</th>
                            <th>Monetary</th>
                            <th>Risk Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($customers, 0, 50) as $customer)
                        <tr>
                            <td><strong>{{ $customer['name'] }}</strong></td>
                            <td>{{ $customer['recency'] }} days</td>
                            <td>{{ $customer['frequency'] }} orders</td>
                            <td>${{ number_format($customer['monetary'], 2) }}</td>
                            <td>
                                @if($customer['risk_label'] === 'High Risk')
                                    <span class="badge badge-high">High Risk</span>
                                @elseif($customer['risk_label'] === 'Moderate Risk')
                                    <span class="badge badge-mod">Moderate</span>
                                @else
                                    <span class="badge badge-low">Low Risk</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. RIGHT PANEL (Analytics) -->
    <div class="right-panel">
        <h2>Risk Distribution <span style="color: #8b5cf6; font-size: 0.8rem; cursor: pointer;">View All</span></h2>
        
        <div class="chart-container">
            <canvas id="riskChart"></canvas>
        </div>

        <div style="margin-top: 40px;">
            <h2>R Engine Status</h2>
            <div style="background: #f8fafc; padding: 15px; border-radius: 15px; margin-top: 15px; display: flex; align-items: center; gap: 15px;">
                <div style="width: 40px; height: 40px; background: #d1fae5; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #10b981;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.9rem; color: #1e293b;">Model Trained</h4>
                    <p style="font-size: 0.75rem; color: #64748b;">RFM Algorithm Active</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customers = @json($customers);
        let high = 0, moderate = 0, low = 0;
        
        customers.forEach(c => {
            if (c.risk_label === 'High Risk') high++;
            else if (c.risk_label === 'Moderate Risk') moderate++;
            else low++;
        });

        const ctx = document.getElementById('riskChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['High', 'Moderate', 'Low'],
                datasets: [{
                    data: [high, moderate, low],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: {family: 'Poppins', size: 11} } }
                }
            }
        });
    });
</script>

</body>
</html>