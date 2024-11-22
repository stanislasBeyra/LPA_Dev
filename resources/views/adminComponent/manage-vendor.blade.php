@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

   <!-- Section contenant le bouton aligné à droite -->
   <div class="d-flex justify-content-between mb-3">
      <div class="d-flex">
         @if(session('error'))
         <div class="alert alert-danger mb-0 me-3" id="error-message">
            {{ session('error') }}
         </div>
         @endif

         @if(session('success'))
         <div class="alert alert-success mb-0 me-3" id="success-message">
            {{ session('success') }}
         </div>
         @endif

         @if($errors->any())
         <div class="alert alert-danger mb-0 me-3" id="error-list">
            <ul class="mb-0">
               @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
      </div>
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary fs-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
         ADD VENDORS
      </button>
   </div>

   <!-- Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Add a New vendor</h5>
               <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vendor.register') }}" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="modal-body">



                  <!-- Section 1: Basic Information -->
                  <h5 class="mb-4">Basic Information</h5>
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="firstname" id="firstname" class="form-control" />
                           <label class="form-label" for="firstname">First Name</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="lastname" id="lastname" class="form-control" />
                           <label class="form-label" for="lastname">Last Name</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <select name="role" class="form-control">
                           <option value="">Select role</option>
                           @foreach($roles as $role)
                           <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="email" name="email" id="email" class="form-control" />
                           <label class="form-label" for="email">Email</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="vendorname" id="vendorName" class="form-control" />
                           <label class="form-label" for="vendorName">Vendor Name</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="contactpersonname" id="contactPerson" class="form-control" />
                           <label class="form-label" for="contactPerson">Contact Person Name</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="email" name="businessEmail" id="businessEmail" class="form-control" />
                           <label class="form-label" for="businessEmail">Business Email</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="tel" name="mobile" id="phone" class="form-control" />
                           <label class="form-label" for="phone">Phone Number</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 2: Business Details -->
                  <h5 class="mb-4">Business Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="businessregno" id="businessRegNo" class="form-control" />
                           <label class="form-label" for="businessRegNo">Business Registration Number</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="taxidnumber" id="tin" class="form-control" />
                           <label class="form-label" for="tin">Tax Identification Number (TIN)</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="businesscategory" id="businessCategory" class="form-control" />
                           <label class="form-label" for="businessCategory">Business Category</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 3: Address Details -->
                  <h5 class="mb-4">Address Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-12">
                        <div class="form-outline">
                           <textarea name="businessaddress" id="businessAddress" class="form-control" rows="3"></textarea>
                           <label class="form-label" for="businessAddress">Business Address</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 4: Banking Details -->
                  <h5 class="mb-4">Banking Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="bank_name_1" id="bankName1" class="form-control" />
                           <label class="form-label" for="bankName1">Bank Name 1</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="bankaccount1" id="bankAccount1" class="form-control" />
                           <label class="form-label" for="bankAccount1">Bank Account Number 1</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="bankname2" id="bankName2" class="form-control" />
                           <label class="form-label" for="bankName2">Bank Name 2</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="bankaccount2" id="bankAccount2" class="form-control" />
                           <label class="form-label" for="bankAccount2">Bank Account Number 2</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div class="form-outline">
                           <input type="text" name="accountholdername" id="accountHolder" class="form-control" />
                           <label class="form-label" for="accountHolder">Account Holder Name</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 5: Verification Documents -->
                  <h5 class="mb-4">Verification Documents</h5>
                  <div class="row mb-4">
                     <div class="col-md-4">
                        <label class="form-label" for="businessCertificate">Business Registration Certificate</label>
                        <input type="file" name="businesscertificate[]" id="businessCertificate" class="form-control" multiple accept=".pdf,.jpg,.png" />
                     </div>
                     <div class="col-md-4">
                        <label class="form-label" for="taxCertificate">Tax Clearance Certificate</label>
                        <input type="file" name="taxcertificate[]" id="taxCertificate" class="form-control" multiple accept=".pdf,.jpg,.png" />
                     </div>
                     <div class="col-md-4">
                        <label class="form-label" for="passportId">Passport or Government-Issued ID</label>
                        <input type="file" name="passportorID[]" id="passportId" class="form-control" multiple accept=".pdf,.jpg,.png" />
                     </div>
                  </div>

                  <!-- Submit button -->





               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submitButton">
                     <span id="buttonText">Submit</span>
                     <div id="spinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>

   <!-- Section avec tableau -->
   <section class="mb-4">
      <div class="card">
         <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
               <strong>Vendors and Users</strong>
            </h5>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#ID</th> <!-- Index de la ligne -->
                        <th scope="col">Creation Date</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Lastname</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($vendors as $key => $user)

                     <tr>
                        <!-- Affichage de l'index de la ligne -->
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $user->created_at->format('m/d/Y, h:i:s A') }}</td>
                        <td>{{ $user->firstname }}</td>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        
                        <td>
                           <button class="btn btn-sm btn-outline-primary">
                              <i class="fas fa-edit"></i> Edit
                           </button>
                           <button type="button" class="btn btn-danger btn-sm">
                              <i class="fas fa-trash-alt"></i> Delete
                           </button>
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

<script>
   const submitButton = document.getElementById('submitButton');
   const spinner = document.getElementById('spinner');
   const buttonText = document.getElementById('buttonText');

   submitButton.addEventListener('click', function(event) {
      buttonText.style.display = 'none';
      spinner.style.display = 'inline-block';


   });
</script>
@endsection