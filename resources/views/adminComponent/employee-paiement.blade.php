@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Vendor Payments Table</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Month 1</th>
                                <th scope="col">Month 2</th>
                                <th scope="col">Month 3</th>
                                <th scope="col">Month 4</th>
                                <th scope="col">Month 5</th>
                                <th scope="col">Month 6</th>
                                <th scope="col">User Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ligne 1 -->
                            <tr>
                                <td>1</td>
                                <td>11/11/2024, 10:35:10 PM</td>
                                <td>4.50</td>
                                <td>0.75</td>
                                <td>0.75</td>
                                <td>0.75</td>
                                <td>0.75</td>
                                <td>0.75</td>
                                <td>0.75</td>
                                <td>Jorolyn Davis</td>
                            </tr>
                            <!-- Ligne 2 -->
                            <tr>
                                <td>2</td>
                                <td>11/11/2024, 10:31:27 PM</td>
                                <td>347566.00</td>
                                <td>57927.67</td>
                                <td>57927.67</td>
                                <td>57927.67</td>
                                <td>57927.67</td>
                                <td>57927.67</td>
                                <td>57927.67</td>
                                <td>Jorolyn Davis</td>
                            </tr>
                            <!-- Ligne 3 -->
                            <tr>
                                <td>3</td>
                                <td>11/11/2024, 10:28:05 PM</td>
                                <td>278.50</td>
                                <td>46.42</td>
                                <td>46.42</td>
                                <td>46.42</td>
                                <td>46.42</td>
                                <td>46.42</td>
                                <td>46.42</td>
                                <td>Jorolyn Davis</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
