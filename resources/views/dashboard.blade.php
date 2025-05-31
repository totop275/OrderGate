@extends('layouts.cms')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome to your order management dashboard')

@push('styles')
<link rel="stylesheet" href="{{ Vite::asset('resources/css/dashboard.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="value">{{ number_format($totalOrders, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>Total Sales</h3>
            <div class="value">${{ number_format($totalSales, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>Active Users</h3>
            <div class="value">{{ number_format($activeUsers, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>Inactive Users</h3>
            <div class="value">{{ number_format($inactiveUsers, 0) }}</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="chart-container">
            <h3>Order Status Breakdown</h3>
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h3>Monthly Sales</h3>
            <canvas id="monthlySalesChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order Status Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($orderBreakdowns)),
            datasets: [{
                data: @json(array_values($orderBreakdowns)),
                backgroundColor: ['#00c8ed', '#008757', '#e63a48']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Sales Chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(monthlySalesCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($monthlySales, 'month')),
            datasets: [{
                label: 'Sales ($)',
                data: @json(array_column($monthlySales, 'total_sales')),
                backgroundColor: '#00c8ed'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
