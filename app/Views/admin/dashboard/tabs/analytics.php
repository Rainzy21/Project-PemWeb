<?php
?>
<div class="row g-4">
    <!-- Monthly Revenue Chart -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Monthly Revenue</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Booking Trends -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Booking Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="bookingTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Room Popularity -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Room Type Popularity</h5>
            </div>
            <div class="card-body">
                <canvas id="roomPopularityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($monthlyRevenue ?? [], 'month')) ?>,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: <?= json_encode(array_column($monthlyRevenue ?? [], 'revenue')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });
    }

    // Room Popularity Chart
    const popularityCtx = document.getElementById('roomPopularityChart');
    if (popularityCtx) {
        new Chart(popularityCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($roomPopularity ?? [], 'room_type')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($roomPopularity ?? [], 'count')) ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
        });
    }
});
</script>