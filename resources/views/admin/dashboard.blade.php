@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Welcome, {{ auth()->user()->name }}</h1>
    <p style="color: var(--text-secondary);">Use the "Content Management" section in the sidebar to update your store
        rates and information.</p>
</div>

<!-- Analytics Charts Section -->
<div class="analytics-grid">
    <!-- Customer Status Pie Chart -->
    <div class="luxury-card" style="padding: 1.5rem;">
        <h4
            style="color: var(--heading-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-users-pie" style="color: var(--accent-color);"></i> Customer Status Analysis
        </h4>
        <div style="height: 300px; position: relative;">
            <canvas id="customerStatusChart"></canvas>
        </div>
    </div>

    <!-- Customer Growth Bar Chart -->
    <div class="luxury-card" style="padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h4 style="color: var(--heading-color); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chart-line" style="color: var(--accent-color);"></i> Customer Growth
            </h4>
            <select id="growthFilter"
                style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-primary); outline: none; cursor: pointer;">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly" selected>Monthly</option>
            </select>
        </div>
        <div style="height: 300px; position: relative;">
            <canvas id="customerGrowthChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shared Chart Options
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#8892b0',
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(10, 25, 47, 0.9)',
                    titleColor: '#64ffda',
                    bodyColor: '#e6f1ff',
                    borderColor: '#64ffda',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
                            let label = context.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== undefined) {
                                label += context.parsed.y;
                            } else {
                                label += context.parsed;
                            }

                            // Add percentage for pie chart
                            if (context.chart.config.type === 'pie') {
                                const dataset = context.chart.data.datasets[0];
                                const total = dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const value = dataset.data[context.dataIndex];
                                const percentage = ((value / total) * 100).toFixed(1) + '%';
                                label += ' (' + percentage + ')';
                            }
                            return label;
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        };

        // Customer Status Chart
        const statusCtx = document.getElementById('customerStatusChart').getContext('2d');
        let statusChart;

        async function initStatusChart() {
            try {
                const response = await fetch('{{ route("admin.analytics.status") }}');
                const data = await response.json();

                const labels = data.map(item => item.status);
                const counts = data.map(item => item.count);

                // Modern luxury colors
                const colors = {
                    'Approved': '#64ffda', // Teal/Cyan
                    'Pending': '#f7d08a',  // Gold/Yellow
                    'Rejected': '#ff4d4d'   // Red
                };

                const backgroundColors = labels.map(label => colors[label] || '#8892b0');

                statusChart = new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: backgroundColors,
                            borderColor: 'transparent',
                            hoverOffset: 15
                        }]
                    },
                    options: chartOptions
                });
            } catch (error) {
                console.error('Error fetching status data:', error);
            }
        }

        // Customer Growth Chart
        const growthCtx = document.getElementById('customerGrowthChart').getContext('2d');
        let growthChart;

        async function updateGrowthChart(filter) {
            try {
                const response = await fetch(`{{ route("admin.analytics.growth") }}?filter=${filter}`);
                const data = await response.json();

                const labels = data.map(item => {
                    if (filter === 'daily') return item.date;
                    if (filter === 'weekly') return 'Week of ' + item.date;
                    return item.month || item.date;
                });
                const counts = data.map(item => item.count);

                if (growthChart) {
                    growthChart.data.labels = labels;
                    growthChart.data.datasets[0].data = counts;
                    growthChart.update();
                } else {
                    growthChart = new Chart(growthCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'New Customers',
                                data: counts,
                                backgroundColor: 'rgba(100, 255, 218, 0.2)',
                                borderColor: '#64ffda',
                                borderWidth: 2,
                                borderRadius: 5,
                                hoverBackgroundColor: 'rgba(100, 255, 218, 0.4)'
                            }]
                        },
                        options: {
                            ...chartOptions,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    ticks: { color: '#8892b0', stepSize: 1 }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#8892b0' }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error fetching growth data:', error);
            }
        }

        // Initialize charts
        initStatusChart();
        updateGrowthChart('monthly');

        // Filter event listener
        document.getElementById('growthFilter').addEventListener('change', function (e) {
            updateGrowthChart(e.target.value);
        });
    });
</script>

<div class="stats-grid">
    <a href="{{ route('admin.prices.index') }}" class="luxury-card"
        style="text-decoration: none; transition: transform 0.3s ease; display: block;">
        <div style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 1rem;"><i class="fas fa-coins"></i>
        </div>
        <h4 style="color: var(--heading-color); margin-bottom: 0.5rem;">Update Prices</h4>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Update live market prices for Gold and Silver
            displayed on the homepage.</p>
    </a>

    <a href="{{ route('admin.terms.index') }}" class="luxury-card"
        style="text-decoration: none; transition: transform 0.3s ease; display: block;">
        <div style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 1rem;"><i
                class="fas fa-file-contract"></i></div>
        <h4 style="color: var(--heading-color); margin-bottom: 0.5rem;">Manage Terms</h4>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Manage the terms and conditions displayed in the
            homepage footer section.</p>
    </a>
</div>
@endsection