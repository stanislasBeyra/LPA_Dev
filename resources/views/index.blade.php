@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

  <!--Section: Minimal statistics cards-->
  <section>
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <div class="d-flex justify-content-between px-md-1">
              <div class="align-self-center">
                <i class="far fa-user text-tertiary fa-3x"></i>
              </div>
              <div class="text-end">
                <h3>{{ $totalVendor }}</h3>
                <p class="mb-0">Total Vendor</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <div class="d-flex justify-content-between px-md-1">
              <div class="align-self-center">
                <i class="far fa-user text-info fa-3x"></i>
              </div>
              <div class="text-end">
                <!-- Affichage du total des employés -->
                <h3>{{ $totalEmployees }}</h3>
                <p class="mb-0">Total Employee</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <div class="d-flex justify-content-between px-md-1">
              <div class="align-self-center">
                <i class="fas fa-spinner fa-spin text-warning fa-3x"></i>
              </div>
              <div class="text-end">
                <h3>{{ $totalpendingorder }}</h3>
                <p class="mb-0">Order pending</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <div class="d-flex justify-content-between px-md-1">
              <div class="align-self-center">
                <i class="fas fa-times-circle text-danger fa-3x"></i>
              </div>
              <div class="text-end">
                <h3>{{$totalvalidateorder}}</h3>
                <p class="mb-0">Order Refuse</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <div class="d-flex justify-content-between px-md-1">
              <div class="align-self-center">
                <i class="fas fa-check text-success fa-3x"></i>
              </div>
              <div class="text-end">
                <h3>{{$totalrefuseorder}}</h3>
                <p class="mb-0">Order Validate</p>
              </div>
            </div>
          </div>
        </div>
      </div>

  </section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        <canvas id="myBarChart" class="w-100"></canvas>
      </div>
      <div class="col-12 col-md-4">
        <canvas id="myPieChart" class="w-100"></canvas>
      </div>
    </div>
  </div>


</div>

<!-- <script>
    var ctx = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar', // Type de graphique (barres)
        data: {
            labels: ['Vendors', 'Employees', 'Pending Orders', 'Validated Orders', 'Refused Orders'], // Étiquettes
            datasets: [{
                label: 'Total',
                data: ["{{ $totalVendor }}", "{{ $totalEmployees }}", "{{ $totalpendingorder }}", "{{ $totalrefuseorder }}", "{{ $totalvalidateorder }}"], // Données
                backgroundColor: [
                    'rgba(0, 123, 255, 0.2)', // Bleu (primary)
                    'rgba(40, 167, 69, 0.2)', // Vert (success)
                    'rgba(255, 193, 7, 0.2)', // Jaune (warning)
                    'rgba(220, 53, 69, 0.2)', // Rouge (danger)
                    'rgba(23, 162, 184, 0.2)' // Teal (info)
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)', // Bleu (primary)
                    'rgba(40, 167, 69, 1)', // Vert (success)
                    'rgba(255, 193, 7, 1)', // Jaune (warning)
                    'rgba(220, 53, 69, 1)', // Rouge (danger)
                    'rgba(23, 162, 184, 1)' // Teal (info)
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Commence à zéro sur l'axe Y
                }
            }
        }
    });
</script> -->

<script>
  var ctxPie = document.getElementById('myPieChart').getContext('2d');
  var myPieChart = new Chart(ctxPie, {
    type: 'pie', // Type de graphique (circulaire)
    data: {
      labels: ['Validated Orders', 'Pending Orders', 'Refused Orders'], // Étiquettes des sections
      datasets: [{
        data: ["{{ $totalpendingorder }}", "{{ $totalrefuseorder }}", "{{ $totalvalidateorder }}"], // Données pour les sections
        backgroundColor: [
          'rgba(40, 167, 69, 0.7)', // Vert (validé)
          'rgba(255, 193, 7, 0.7)', // Jaune (en attente)
          'rgba(220, 53, 69, 0.7)' // Rouge (refusé)
        ],
        borderColor: [
          'rgba(40, 167, 69, 1)', // Vert
          'rgba(255, 193, 7, 1)', // Jaune
          'rgba(220, 53, 69, 1)' // Rouge
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.label + ': ' + tooltipItem.raw + ' orders';
            }
          }
        }
      }
    }
  });
</script>


<script>
  var ctx = document.getElementById('myBarChart').getContext('2d');
  var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Vendors', 'Employees', 'Pending Orders', 'Validated Orders', 'Refused Orders'],
      datasets: [{
        label: 'Total',
        data: ["{{ $totalVendor }}", "{{ $totalEmployees }}", "{{ $totalpendingorder }}", "{{ $totalrefuseorder }}", "{{ $totalvalidateorder }}"], 
        backgroundColor: [
          'rgba(0, 123, 255, 0.7)',
          'rgba(40, 167, 69, 0.7)', 
          'rgba(255, 193, 7, 0.7)', 
          'rgba(220, 53, 69, 0.7)', 
          'rgba(23, 162, 184, 0.7)'
        ],
        borderColor: [
          'rgba(0, 123, 255, 1)',
          'rgba(40, 167, 69, 1)', 
          'rgba(255, 193, 7, 1)', 
          'rgba(220, 53, 69, 1)', 
          'rgba(23, 162, 184, 1)' 
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        
        chart3d: {
          enabled: true,
          
          rotation: {
            x: 10,
            y: 10
          },
          scale: 0.5
        }
      },
      scales: {
        y: {
          beginAtZero: true 
        }
      }
    }
  });
</script>

@endsection