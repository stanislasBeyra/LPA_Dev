@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">
    <!-- Section: User Statistics -->
    <section>
    <div class="row">
        <!-- Total Users -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-users text-info fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{$totalUsers}}</h3>
                            <p class="mb-0">Utilisateurs Totaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with Phone -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-phone-alt text-warning fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{count($usersWithPhone)}}</h3>
                            <p class="mb-0">users avec un Téléphone</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{ count($activeUsers) }}</h3>
                            <p class="mb-0">Utilisateurs Actifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Users -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-times-circle text-danger fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{count($inactiveUsers)}}</h3>
                            <p class="mb-0">Utilisateurs Inactifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banned Users -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-ban text-danger fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{count($bannedUsers)}}</h3>
                            <p class="mb-0">Utilisateurs Bannís</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with Balance Above 10000 -->
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign text-success fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{count($usersWithBalanceAbove10000)}}</h3>
                            <p class="mb-0">Solde Supérieur à 10 000</p>
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
                        <i class="fas fa-signal text-warning fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{$countOrange}}</h3>
                            <p class="mb-0">Users Orange</p>
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
                        <i class="fas fa-signal text-primary fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{$countmtn}}</h3>
                            <p class="mb-0">Users MTN</p>
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
                        <i class="fas fa-signal text-info fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{$countmoov}}</h3>
                            <p class="mb-0">Users Moov</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


    <!-- Section: Liste des utilisateurs -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Liste des Utilisateurs</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead class="bg-warning">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nom d'utilisateur</th>
                                <th scope="col">Numéro de téléphone</th>
                                <th scope="col">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['username'] }}</td>
                                <td>{{ $user['mobile'] }}</td>
                                <td>
                                    @if($user['status'] == 'Active')
                                    <span class="text-success">{{ $user['status'] }}</span>
                                    @elseif($user['status'] == 'Inactive')
                                    <span class="text-warning">{{ $user['status'] }}</span>
                                    @else
                                    <span class="text-danger">{{ $user['status'] }}</span>
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