@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

   <!-- Section contenant le bouton aligné à droite -->
   <div class="d-flex justify-content-between mb-3">
      <div class="d-flex animate__animated animate__zoomIn">
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
      <button type="button" class="btn btn-primary fs-7 animate__animated animate__zoomIn" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
         ADD VENDORS
      </button>
   </div>

   <!-- Modal  for add vendors-->
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
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="text" name="firstname" id="firstname" class="form-control" />
                           <label class="form-label" for="firstname">First Name</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline">
                           <input type="text" name="lastname" id="lastname" class="form-control" />
                           <label class="form-label" for="lastname">Last Name</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6 mb-3">
                        <select name="role" class="form-select">
                           <option value="">Select role</option>
                           @foreach($roles as $role)
                           <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline">
                           <input type="email" name="email" id="email" class="form-control" />
                           <label class="form-label" for="email">Email</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="text" name="vendorname" id="vendorName" class="form-control" />
                           <label class="form-label" for="vendorName">Vendor Name</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline">
                           <input type="text" name="contactpersonname" id="contactPerson" class="form-control" />
                           <label class="form-label" for="contactPerson">Contact Person Name</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="email" name="businessemail" id="businessEmail" class="form-control" />
                           <label class="form-label" for="businessEmail">Business Email</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline">
                           <input type="tel" name="mobile" id="phone" class="form-control" />
                           <label class="form-label" for="phone">Phone Number</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 2: Business Details -->
                  <h5 class="mb-4">Business Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="text" name="businessregno" id="businessRegNo" class="form-control" />
                           <label class="form-label" for="businessRegNo">Business Registration Number</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline ">
                           <input type="text" name="taxidnumber" id="tin" class="form-control" />
                           <label class="form-label" for="tin">Tax Identification Number (TIN)</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline ">
                           <input type="text" name="businesscategory" id="businessCategory" class="form-control" />
                           <label class="form-label" for="businessCategory">Business Category</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 3: Address Details -->
                  <h5 class="mb-4">Address Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-12">
                        <div data-mdb-input-init class="form-outline ">
                           <textarea name="businessaddress" id="businessAddress" class="form-control" rows="3"></textarea>
                           <label class="form-label" for="businessAddress">Business Address</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 4: Banking Details -->
                  <h5 class="mb-4">Banking Details</h5>
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="text" name="bank_name_1" id="bankName1" class="form-control" />
                           <label class="form-label" for="bankName1">Bank Name 1</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline ">
                           <input type="text" name="bankaccount1" id="bankAccount1" class="form-control" />
                           <label class="form-label" for="bankAccount1">Bank Account Number 1</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline mb-3">
                           <input type="text" name="bankname2" id="bankName2" class="form-control" />
                           <label class="form-label" for="bankName2">Bank Name 2</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline ">
                           <input type="text" name="bankaccount2" id="bankAccount2" class="form-control" />
                           <label class="form-label" for="bankAccount2">Bank Account Number 2</label>
                        </div>
                     </div>
                  </div>

                  <div class="row mb-4">
                     <div class="col-md-6">
                        <div data-mdb-input-init class="form-outline">
                           <input type="text" name="accountholdername" id="accountHolder" class="form-control" />
                           <label class="form-label" for="accountHolder">Account Holder Name</label>
                        </div>
                     </div>
                  </div>

                  <!-- Section 5: Verification Documents -->
                  <h5 class="mb-4">Verification Documents</h5>
                  <div class="row mb-4">
                     <div class="col-md-4 mb-3">
                        <label class="form-label" for="businessCertificate">Business Registration Certificate</label>
                        <input type="file" name="businesscertificate[]" id="businessCertificate" class="form-control" multiple accept=".pdf,.jpg,.png" />
                     </div>
                     <div class="col-md-4 mb-3">
                        <label class="form-label" for="taxCertificate">Tax Clearance Certificate</label>
                        <input type="file" name="taxcertificate[]" id="taxCertificate" class="form-control" multiple accept=".pdf,.jpg,.png" />
                     </div>
                     <div class="col-md-4 mb-3">
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
      <div class="card animate__animated animate__zoomIn">
         <div class="card-header text-center py-3">

            <div class="d-flex justify-content-between align-items-center">

               <h5 class="mb-0 text-center">
                  <strong>Vendors List</strong>
               </h5>

               <div class="input-group " style="width: 30%;">
                  <div class="form-outline" data-mdb-input-init>
                     <input type="search" id="form1" class="form-control" placeholder="Search Vendos" />
                     <label class="form-label" for="form1">Search</label>
                  </div>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#ID</th> <!-- Index de la ligne -->
                        <th scope="col">Creation Date</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Phone number</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>

                        @if(auth()->user()->role != 5)
                        <th scope="col">Action</th>
                        @endif

                     </tr>
                  </thead>
                  <tbody>
                     @foreach($vendors as $key => $user)

                     <tr>
                        <!-- Affichage de l'index de la ligne -->
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $user->created_at->format('m/d/Y, h:i:s A') }}</td>
                        <td>{{ $user->firstname??'NA' }} {{ $user->lastname??'NA' }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{$user->mobile}}</td>
                        <td>{{ $user->email }}</td>
                        <td>

                           <p style="cursor: pointer;" data-mdb-toggle="modal"
                              data-mdb-target="#staticBackdrop5"
                              class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                              {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                           </p>

                        </td>

                        @if(auth()->user()->role != 5)
                        <td>
                           <a href="{{ url('/vendors-detail/' . $user->id) }}"
                              class="btn btn-info btn-sm">
                              <i class="fas fa-eye"></i>
                           </a>

                           <button data-mdb-button-init data-mdb-ripple-init
                              class="btn btn-outline-primary btn-sm"
                              data-mdb-modal-init data-mdb-target="#staticBackdrop1"
                              data-user='@json($user)'
                              onclick="handleButtonClick(this)">

                              <i class="fas fa-edit"></i>
                           </button>
                           <button type="button" class="btn btn-danger btn-sm"
                              data-mdb-modal-init data-mdb-target="#exampleModal1"
                              onclick="setVendors('{{ $user->id }}')">
                              <i class="fas fa-trash-alt"></i>
                           </button>
                        </td>
                        @endif


                     </tr>



                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </section>

</div>

<!-- edit modal -->
<div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content ">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Edit Vendor</h5>
         </div>
         <div class="modal-body p-4">
            <form id="editVendorForm" method="POST" action="{{ route('Vendor.Update') }}">

               @csrf
               <input type="hidden" id="editUserId" name="user_id" />

               <!-- Section 1: Basic Information -->
               <h5 class="mb-4">Basic Information</h5>
               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="firstname" id="editFirstname" class="form-control" required />
                        <label class="form-label" for="editFirstname">First Name</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="lastname" id="editLastname" class="form-control" required />
                        <label class="form-label" for="editLastname">Last Name</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <select name="role" id="editUserRole" class="form-select" required>
                        <option value="">Select role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="email" name="email" id="editEmail" class="form-control" required />
                        <label class="form-label" for="editEmail">Email</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="vendorname" id="editUsername" class="form-control" required />
                        <label class="form-label" for="editUsername">Vendor Name</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="contactpersonname" id="editContactPerson" class="form-control" required />
                        <label class="form-label" for="editContactPerson">Contact Person Name</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="email" name="businessemail" id="editBusinessEmail" class="form-control" required />
                        <label class="form-label" for="editBusinessEmail">Business Email</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="tel" name="mobile" id="editPhone" class="form-control" required />
                        <label class="form-label" for="editPhone">Phone Number</label>
                     </div>
                  </div>
               </div>

               <!-- Section 2: Business Details -->
               <h5 class="mb-4">Business Details</h5>
               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="businessregno" id="editBusinessRegNo" class="form-control" required />
                        <label class="form-label" for="editBusinessRegNo">Business Registration Number</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="taxidnumber" id="editTaxidnumber" class="form-control" required />
                        <label class="form-label" for="editTaxidnumber">Tax Identification Number (TIN)</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="businesscategory" id="editBusinessCategory" class="form-control" required />
                        <label class="form-label" for="editBusinessCategory">Business Category</label>
                     </div>
                  </div>
               </div>

               <!-- Section 3: Address Details -->
               <h5 class="mb-4">Address Details</h5>
               <div class="row mb-4">
                  <div class="col-md-12">
                     <div class="form-outline">
                        <textarea name="businessaddress" id="editBusinessAddress" class="form-control" rows="3" required></textarea>
                        <label class="form-label" for="editBusinessAddress">Business Address</label>
                     </div>
                  </div>
               </div>

               <!-- Section 4: Banking Details -->
               <h5 class="mb-4">Banking Details</h5>
               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="bank_name_1" id="editBankName1" class="form-control" required />
                        <label class="form-label" for="editBankName1">Bank Name 1</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="bankaccount1" id="editBankAccount1" class="form-control" required />
                        <label class="form-label" for="editBankAccount1">Bank Account Number 1</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="bankname2" id="editBankName2" class="form-control" />
                        <label class="form-label" for="editBankName2">Bank Name 2</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="bankaccount2" id="editBankAccount2" class="form-control" />
                        <label class="form-label" for="editBankAccount2">Bank Account Number 2</label>
                     </div>
                  </div>
               </div>

               <div class="row mb-4">
                  <div class="col-md-6">
                     <div class="form-outline">
                        <input type="text" name="accountholdername" id="editAccountHolder" class="form-control" required />
                        <label class="form-label" for="editAccountHolder">Account Holder Name</label>
                     </div>
                  </div>
               </div>

               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="editButton">
                     <span id="editButtonText">Update Vendor</span>
                     <div id="editSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </button>
               </div>
            </form>


         </div>
      </div>
   </div>
</div>




<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header bg-danger d-flex justify-content-center w-100">
            <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
         </div>

         <form id="deleteForm" action="{{ route('delete.Vendor') }}" method="POST">
            @csrf
            <input type="hidden" id="userid" name="userid">
            <div class="modal-body text-center">
               <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
               <p>Are you sure you want to delete this category? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-danger" id="deleteButton">
                  <span id="deleteButtonText">Delete</span>
                  <div id="deleteSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                     <span class="visually-hidden">Loading...</span>
                  </div>
               </button>
            </div>

         </form>
      </div>
   </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
   function setVendors(userid) {
      document.getElementById('userid').value = userid;
      console.log('userid::::::teste', userid);
   };

   const submitButton = document.getElementById('submitButton');
   const spinner = document.getElementById('spinner');
   const buttonText = document.getElementById('buttonText');

   submitButton.addEventListener('click', function(event) {
      buttonText.style.display = 'none';
      spinner.style.display = 'inline-block';


   });


   const deleteButton = document.getElementById('deleteButton'); // Bouton de suppression
   const spinnerdelete = document.getElementById('deleteSpinner'); // Spinner pour le bouton
   const buttonTextdelete = document.getElementById('deleteButtonText'); // Texte du bouton

   // Ajout de l'événement sur le bouton
   deleteButton.addEventListener('click', function(event) {
      // Affiche le spinner et masque le texte
      buttonTextdelete.style.display = 'none'; // Masque le texte du bouton
      spinnerdelete.style.display = 'inline-block'; // Affiche le spinner
   });



   function handleButtonClick(button) {
      // Récupérer les données JSON à partir de l'attribut data-user
      const user = JSON.parse(button.getAttribute('data-user'));
      console.log(user);

      // Remplir les champs du formulaire avec les informations de base de l'utilisateur
      document.getElementById('editUserId').value = user.id;
      document.getElementById('editUserRole').value = user.role.id;
      document.getElementById('editFirstname').value = user.firstname;
      document.getElementById('editLastname').value = user.lastname;
      document.getElementById('editEmail').value = user.email;
      document.getElementById('editPhone').value = user.mobile;

      // Remplir les champs vendor en utilisant une condition ternaire
      document.getElementById('editUsername').value = user.vendor ? user.vendor.vendorname : user.username;
      document.getElementById('editContactPerson').value = user.vendor ? user.vendor.contactpersonname : '';
      document.getElementById('editBusinessEmail').value = user.vendor ? user.vendor.businessemail : ''; // Using user email as business email

      // Business Details
      document.getElementById('editBusinessRegNo').value = user.vendor ? user.vendor.businessregno : '';
      document.getElementById('editTaxidnumber').value = user.vendor ? user.vendor.taxidnumber : '';
      document.getElementById('editBusinessCategory').value = user.vendor ? user.vendor.businesscategory : '';

      // Address Details
      document.getElementById('editBusinessAddress').value = user.vendor ? user.vendor.businessaddress : '';

      // Banking Details
      document.getElementById('editBankName1').value = user.vendor ? user.vendor.bankname1 : '';
      document.getElementById('editBankAccount1').value = user.vendor ? user.vendor.bankaccount1 : '';
      document.getElementById('editBankName2').value = user.vendor ? user.vendor.bankname2 : '';
      document.getElementById('editBankAccount2').value = user.vendor ? user.vendor.bankaccount2 : '';
      document.getElementById('editAccountHolder').value = user.vendor ? user.vendor.accountholdername : '';

      // Réinitialiser les classes form-outline de MDBootstrap
      document.querySelectorAll('.form-outline').forEach((formOutline) => {
         new mdb.Input(formOutline).init();
      });
   }
</script>


<script>
   $(document).ready(function() {
      $('#form1').on('keyup', function() {
         let searchQuery = $(this).val();
         let keyIncremented = 0;

         // Effectuer une requête AJAX
         $.ajax({
            url: "{{ route('search.vendor') }}",
            type: "GET",
            data: {
               search: searchQuery
            },
            success: function(response) {
               // Vider le tableau
               $('tbody').empty();

               if (response.vendors.length > 0) {
                  response.vendors.forEach((vendor, index) => {
                     keyIncremented++;

                     let formattedDate = new Date(vendor.created_at).toLocaleString('en-US', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                     });
                     let vendorJson = JSON.stringify(vendor).replace(/"/g, '&quot;');

                     let statusClass = vendor.status == 1 ? 'bg-success' : 'bg-danger';
                     let statusText = vendor.status == 1 ? 'Active' : 'Inactive';

                     // Formater l'URL correctement avec Blade
                     let vendorDetailUrl = `/vendors-detail/${vendor.id}`;





                     $('tbody').append(`
                            <tr>
                            <td>${keyIncremented}</td>
                                <td>${formattedDate}</td>
                                <td>${vendor.firstname??''} ${vendor.lastname??''}</td>
                                <td>${vendor.username}</td>
                                <td>${vendor.mobile}</td>
                                <td>${vendor.email}</td>
                                <td>
                                <p style="cursor: pointer;" data-mdb-toggle="modal"
                                   data-mdb-target="#staticBackdrop5"
                                   class="badge ${statusClass}">
                                   ${statusText}
                                </p>
                                </td>
                                <td class="text-center">
                                <a href="${vendorDetailUrl}" class="btn btn-info btn-sm">
                     <i class="fas fa-eye"></i>
                  </a>
                                    <button type="button"
                                        data-mdb-button-init 
                                        data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm edit-role"
                                        data-mdb-modal-init 
                                        data-mdb-target="#staticBackdrop1"
                                       data-user="${vendorJson}"
                                        onclick="handleButtonClick(this)"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                     <button type="button" class="btn btn-danger btn-sm"
                              data-mdb-modal-init data-mdb-target="#exampleModal1"
                              onclick="setVendors(${vendor.id})">
                              <i class="fas fa-trash-alt"></i>
                           </button>
                                </td>
                            </tr>
                        `);
                  });

               } else {
                  $('tbody').append('<tr><td colspan="4" class="text-center">No results found</td></tr>');
               }
            },
            error: function(xhr) {
               console.error(xhr.responseText);
            }
         });
      });


   });
</script>
@endsection