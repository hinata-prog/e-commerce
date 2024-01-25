<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('/front-assets/images/logo.png') }}" style="height: 100px; opacity: .8"/>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <div class="accordion" id="accordionExample">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                </ul>


                <!-- Ecommerce Section -->
                <div class="card bg-dark">
                    <div class="card-header" id="headingEcommerce">
                        <h2 class="mb-0">
                            <button class="btn btn-link {{ Route::is('orders.*') || Route::is('coupons.index') ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapseEcommerce" aria-expanded="{{ Route::is('orders.*') || Route::is('coupons.index') ? 'true' : 'false' }}" aria-controls="collapseEcommerce">
                                Ecommerce
                            </button>
                        </h2>
                    </div>

                    <div id="collapseEcommerce" class="collapse {{ Route::is('orders.*') || Route::is('coupons.index') ? 'show' : '' }}" aria-labelledby="headingEcommerce" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}" class="nav-link {{ Route::is('orders.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-shopping-bag"></i>
                                        <p>Orders</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('orders.pendingOrders') }}" class="nav-link {{ Route::is('orders.pendingOrders') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-clock"></i>
                                        <p>Pending Orders</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('orders.shippedOrders') }}" class="nav-link {{ Route::is('orders.shippedOrders') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-shipping-fast"></i>
                                        <p>Shipped Orders</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('orders.deliveredOrders') }}" class="nav-link {{ Route::is('orders.deliveredOrders') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-check-circle"></i>
                                        <p>Delivered Orders</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="card bg-dark">
                    <div class="card-header" id="headingProducts">
                        <h2 class="mb-0">
                            <button class="btn btn-link {{ Route::is('categories.*') || Route::is('sub-categories.*') || Route::is('products.*') ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapseProducts" aria-expanded="{{ Route::is('categories.*') || Route::is('sub-categories.*') || Route::is('products.*') ? 'true' : 'false' }}" aria-controls="collapseProducts">
                                Products
                            </button>
                        </h2>
                    </div>

                    <div id="collapseProducts" class="collapse {{ Route::is('categories.*') || Route::is('sub-categories.*') || Route::is('products.*') ? 'show' : '' }}" aria-labelledby="headingProducts" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link {{ Route::is('categories.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Category</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('sub-categories.index') }}" class="nav-link {{ Route::is('sub-categories.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Sub Category</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('products.index') }}" class="nav-link {{ (strpos(Route::currentRouteName(), 'products.') === 0 && Route::currentRouteName() !== 'products.productRatings') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tag"></i>
                                        <p>Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('products.productRatings') }}" class="nav-link {{ Route::is('products.productRatings') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-star"></i>
                                        <p>Ratings</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Configure Section -->
                <div class="card bg-dark">
                    <div class="card-header" id="headingConfigure">
                        <h2 class="mb-0">
                            <button class="btn btn-link {{ Route::is('banner-images.*') || Route::is('shipping.create') || Route::is('users.*') || Route::is('pages.*') || Route::is('contact.*') ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapseConfigure" aria-expanded="{{ Route::is('banner-images.*') || Route::is('shipping.create') || Route::is('users.*') || Route::is('pages.*') || Route::is('contact.*') ? 'true' : 'false' }}" aria-controls="collapseConfigure">
                                Configure
                            </button>
                        </h2>
                    </div>

                    <div id="collapseConfigure" class="collapse {{ Route::is('banner-images.*') || Route::is('shipping.create') || Route::is('users.*') || Route::is('pages.*') || Route::is('coupons.*') || Route::is('contact.*') ? 'show' : '' }}" aria-labelledby="headingConfigure" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="{{ route('banner-images.index') }}" class="nav-link {{ Route::is('banner-images.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-images"></i>
                                        <p>Banner Images</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('shipping.create') }}" class="nav-link {{ Route::is('shipping.create') ? 'active' : '' }}">
                                        <i class="fas fa-truck nav-icon"></i>
                                        <p>Delivery</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('coupons.index') }}" class="nav-link {{ Route::is('coupons.*') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-percent" aria-hidden="true"></i>
                                        <p>Discount</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pages.index') }}" class="nav-link {{ Route::is('pages.*') ? 'active' : '' }}">
                                        <i class="nav-icon far fa-file-alt"></i>
                                        <p>Pages</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('contact.index') }}" class="nav-link {{ Route::is('contact.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-address-card"></i>
                                        <p>Contact</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Setting Section -->
                <div class="card bg-dark">
                    <div class="card-header" id="headingSetting">
                        <h2 class="mb-0">
                            <button class="btn btn-link {{ Route::is('admins.*') ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapseSetting" aria-expanded="{{ Route::is('admins.*') ? 'true' : 'false' }}" aria-controls="collapseSetting">
                                Setting
                            </button>
                        </h2>
                    </div>

                    <div id="collapseSetting" class="collapse {{ Route::is('admins.*') ? 'show' : '' }}" aria-labelledby="headingSetting" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="{{ route('admins.index') }}" class="nav-link {{ Route::is('admins.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Admins</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
