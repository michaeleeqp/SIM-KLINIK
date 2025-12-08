@extends('layout.app')

@section('content')
<div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Beranda</h3>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-8 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Kunjungan Perhari</p>
                          <h4 class="card-title">{{ number_format($kunjunganHariIni ?? 0) }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-8 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-regular fa-user-plus"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Pasien Baru Perhari</p>
                          <h4 class="card-title">{{ $pasienBaruHariIni }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-8 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="fas fa-solid fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total Pasien</p>
                          <h4 class="card-title">{{ $totalPasien }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Grafik Kunjungan Per Bulan</div>
                      <div class="card-tools">
                        <button class="btn btn-icon btn-link btn-primary btn-xs" id="refreshChart">
                          <i class="fa fa-refresh"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px">
                      <canvas id="monthlyVisitsChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Visits Chart
    const monthlyVisitsCtx = document.getElementById('monthlyVisitsChart').getContext('2d');
    const monthlyChart = new Chart(monthlyVisitsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Kunjungan',
                data: {!! json_encode($monthlyVisits) !!},
                borderColor: '#1abc9c',
                backgroundColor: 'rgba(26, 188, 156, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#1abc9c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#1abc9c',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah Kunjungan: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Refresh button functionality
    document.getElementById('refreshChart').addEventListener('click', function() {
        monthlyChart.update();
        this.classList.add('fa-spin');
        setTimeout(() => {
            this.classList.remove('fa-spin');
        }, 1000);
    });

    // Optional: Add click interaction on data points
    document.getElementById('monthlyVisitsChart').addEventListener('click', function(evt) {
        const points = monthlyChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        
        if (points.length) {
            const firstPoint = points[0];
            const label = monthlyChart.data.labels[firstPoint.index];
            const value = monthlyChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            
            // You can add custom action here, e.g., show detailed info
            console.log('Clicked:', label, 'Value:', value);
        }
    });
});
</script>

<style>
.chart-container {
    position: relative;
}

#refreshChart {
    transition: all 0.3s ease;
}

#refreshChart:hover {
    transform: scale(1.1);
}

.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection