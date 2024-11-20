@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

    <!-- Section contenant le bouton aligné à droite -->
    <div class="text-end mb-3">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary fs-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
            ADD EMPLOYEE
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a New Employee</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form>
                        <!-- Section 1: Basic Information -->
                        <h5 class="mb-4">Basic Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="vendorName" class="form-control" />
                                    <label class="form-label" for="vendorName">Vendor Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="contactPerson" class="form-control" />
                                    <label class="form-label" for="contactPerson">Contact Person Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="email" id="email" class="form-control" />
                                    <label class="form-label" for="email">Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="tel" id="phone" class="form-control" />
                                    <label class="form-label" for="phone">Phone Number</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password" class="form-control" />
                                    <label class="form-label" for="password">Password</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Business Details -->
                        <h5 class="mb-4">Business Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="businessRegNo" class="form-control" />
                                    <label class="form-label" for="businessRegNo">Business Registration Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="tin" class="form-control" />
                                    <label class="form-label" for="tin">Tax Identification Number (TIN)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="businessCategory" class="form-control" />
                                    <label class="form-label" for="businessCategory">Business Category</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Address Details -->
                        <h5 class="mb-4">Address Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-outline">
                                    <textarea id="businessAddress" class="form-control" rows="3"></textarea>
                                    <label class="form-label" for="businessAddress">Business Address</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Banking Details -->
                        <h5 class="mb-4">Banking Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="bankName1" class="form-control" />
                                    <label class="form-label" for="bankName1">Bank Name 1</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="bankAccount1" class="form-control" />
                                    <label class="form-label" for="bankAccount1">Bank Account Number 1</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="bankName2" class="form-control" />
                                    <label class="form-label" for="bankName2">Bank Name 2</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="bankAccount2" class="form-control" />
                                    <label class="form-label" for="bankAccount2">Bank Account Number 2</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="accountHolder" class="form-control" />
                                    <label class="form-label" for="accountHolder">Account Holder Name</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Verification Documents -->
                        <h5 class="mb-4">Verification Documents</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label" for="businessCertificate">Business Registration Certificate</label>
                                <input type="file" id="businessCertificate" class="form-control" accept=".pdf,.jpg,.png" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="taxCertificate">Tax Clearance Certificate</label>
                                <input type="file" id="taxCertificate" class="form-control" accept=".pdf,.jpg,.png" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="passportId">Passport or Government-Issued ID</label>
                                <input type="file" id="passportId" class="form-control" accept=".pdf,.jpg,.png" />
                            </div>
                        </div>

                        <!-- Submit button -->
                        <!-- <button type="submit" class="btn btn-primary btn-block">Submit</button> -->
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init>Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Sales Performance KPIs</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Product Detail Views</th>
                                <th scope="col">Unique Purchases</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Product Revenue</th>
                                <th scope="col">Avg. Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Value</th>
                                <td>18,492</td>
                                <td>228</td>
                                <td>350</td>
                                <td>$4,787.64</td>
                                <td>$13.68</td>
                            </tr>
                            <tr>
                                <th scope="row">Percentage change</th>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>-48.8%</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>14.0%</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>46.4%</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>29.6%</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>-11.5%</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Absolute change</th>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>-17,654</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>28</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>111</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>$1,092.72</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>$-1.78</span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection