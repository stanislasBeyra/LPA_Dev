@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

<section>
        <div class="row">
          <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
              <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                  <div class="align-self-center">
                  <i class="fas fa-money-bill-alt  fa-3x" style="color: #f6cb1b;"></i>
                  </div>
                  <div class="text-end">
                    <h3>299</h3>
                    <p class="mb-0">Transfert MTN</p>
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
                  <i class="fas fa-money-bill-alt fa-3x" style="color: #1097e8;" ></i>
                  </div>
                  <div class="text-end">
                    <h3>156</h3>
                    <p class="mb-0">Transfert Wave</p>
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
                  <i class="fas fa-money-bill-alt  fa-3x" style="color: #f98626;"></i>
                  </div>
                  <div class="text-end">
                    <h3>64.89 %</h3>
                    <p class="mb-0">Transfert OM</p>
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
                  <i class="fas fa-money-bill-alt  fa-3x" style="color: #0861b4;"></i>
                  </div>
                  <div class="text-end">
                    <h3>423</h3>
                    <p class="mb-0">Transfert Moov</p>
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
                  <div>
                    <h3 class="text-danger">10000F</h3>
                    <p class="mb-0">Montant T MTN</p>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-rocket text-danger fa-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
              <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                  <div>
                    <h3 class="text-success">15673F</h3>
                    <p class="mb-0">Montant T Wave</p>
                  </div>
                  <div class="align-self-center">
                    <i class="far fa-user text-success fa-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
              <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                  <div>
                    <h3 class="text-danger">10000F</h3>
                    <p class="mb-0">Montant T OM</p>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-rocket text-danger fa-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
              <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                  <div>
                    <h3 class="text-info">423633F</h3>
                    <p class="mb-0">Montant T Moov</p>
                  </div>
                  <div class="align-self-center">
                    <i class="far fa-life-ring text-info fa-3x"></i>
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
                  <div>
                    <h3 class="text-info">27833F</h3>
                    <p class="mb-0">somme total</p>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-book-open text-info fa-3x"></i>
                  </div>
                </div>
                <div class="px-md-1">
                  <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
              <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                  <div>
                    <h3 class="text-warning">156</h3>
                    <p class="mb-0">Total Transfert</p>
                  </div>
                  <div class="align-self-center">
                    <i class="far fa-comments text-warning fa-3x"></i>
                  </div>
                </div>
                <div class="px-md-1">
                  <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
         
        </div>
      </section>

    <!-- Section: Historique des transactions -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Historique des Mobile Monney</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead class="bg-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Envoyeur</th>
                                <th scope="col">Receveur</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Date</th>
                                <th scope="col">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction['id'] }}</td>
                                    <td>{{ $transaction['sender'] }}</td>
                                    <td>{{ $transaction['receiver'] }}</td>
                                    <td>{{ $transaction['amount'] }} €</td>
                                    <td>{{ $transaction['date'] }}</td>
                                    <td>
                                        @if($transaction['status'] == 'Completed')
                                            <span class="text-success">{{ $transaction['status'] }}</span>
                                        @elseif($transaction['status'] == 'Pending')
                                            <span class="text-warning">{{ $transaction['status'] }}</span>
                                        @else
                                            <span class="text-danger">{{ $transaction['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
