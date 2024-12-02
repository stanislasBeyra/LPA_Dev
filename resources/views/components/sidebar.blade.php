<nav id="sidebarMenu" class="sidebar  position-fixed" style="height: 100vh; overflow-y: auto;">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <!-- Dashboard Section -->
            <a href="/" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>

            @if(auth()->user()->role == 1)
            <!-- principal admin -->
            <a href="{{ route('content.page', ['page' => 'manage-vendors']) }}" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users-cog fa-fw me-3"></i><span>Manage Vendors</span>
            </a>
            <a href="/manage-employees" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users fa-fw me-3"></i><span>Manage Employees</span>
            </a>
             <!-- Vendor Orders and Products -->
             <a href="/vendor-product" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-cogs fa-fw me-3"></i><span>Vendor Product</span>
            </a>
            <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i><span>Vendor Order</span>
            </a>
            <a href="/manage-agencies" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-building fa-fw me-3"></i><span>Manage Agencies</span>
            </a>
            <a href="/manage-categories" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage Categories</span>
            </a>

            <a href="/manage-admin" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage admins</span>
            </a>

            <a href="/manage-admin-role" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage roles</span>
            </a>
            <a href="/manage-banner" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage Banner</span>
            </a>
           
            <!-- Payments Section -->
            <!-- <a href="/employee-paiement" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-credit-card fa-fw me-3"></i><span>Payments</span>
            </a> -->

            @endif

            @if(auth()->user()->role == 3)
            <!-- vendors-->
            <!-- <a href="/add-products" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-plus fa-fw me-3"></i><span>Add Products</span>
            </a> -->
            <a href="/manage-vendor-product" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box fa-fw me-3"></i><span>manage Products</span>
            </a>
            <a href="/manage-vendor-orders" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box-open fa-fw me-3"></i><span>My Orders</span>
            </a>
            @endif


        </div>
    </div>
</nav>







<!-- <nav id="sidebarMenu" class="sidebar bg-white">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="/" class="list-group-item list-group-item-action py-2 active" data-mdb-ripple-init aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main dashboard</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2 " data-mdb-ripple-init>
                <i class="fas fa-chart-area fa-fw me-3"></i><span>Website traffic</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-lock fa-fw me-3"></i><span>Password</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-chart-line fa-fw me-3"></i><span>Analytics</span></a>
            <a href="/historiques" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-chart-pie fa-fw me-3"></i><span>SEO</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-chart-bar fa-fw me-3"></i><span>Orders</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-globe fa-fw me-3"></i><span>International</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-building fa-fw me-3"></i><span>Partners</span></a>
            <a href="/historique" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-calendar fa-fw me-3"></i><span>hsitorique</span></a>
            <a href="/historiquemobile" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-calendar fa-fw me-3"></i><span>Mobile Monney</span></a>
            <a href="/home" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-users fa-fw me-3"></i><span>Users</span></a>
            
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-money-bill fa-fw me-3"></i><span>Sales</span></a>
        </div>
    </div>
</nav>

 -->