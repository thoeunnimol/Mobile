<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-sm">
  <div class="app-brand demo">
    <a href="/" class="app-brand-link">
      <span class="app-brand-logo demo me-1">
        <span style="color: var(--bs-primary)">
          <!-- Your SVG logo would go here -->
        </span>
      </span>
      <span class="app-brand-text demo menu-text fw-semibold ms-2">Mobile APP</span>
    </a>
    <a href="#" class="layout-menu-toggle menu-link text-large ms-auto" id="menuToggle">
      <i class="menu-toggle-icon d-xl-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ !isset($_GET['p']) || $_GET['p'] == 'Dashboard' ? 'active' : '' }}">
      <a href="/?p=Dashboard" class="menu-link" id="dashboardLink">
        <i class="menu-icon tf-icons ri-dashboard-line"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <!-- Product -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Product' ? 'active' : '' }}">
      <a href="/?p=Product" class="menu-link" id="productLink">
        <i class="menu-icon tf-icons ri-shopping-bag-line"></i>
        <div data-i18n="Product">Product</div>
      </a>
    </li>
    
    <!-- Category -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Category' ? 'active' : '' }}">
      <a href="/?p=Category" class="menu-link" id="categoryLink">
        <i class="menu-icon tf-icons ri-file-chart-line"></i>
        <div data-i18n="Category">Category</div>
      </a>
    </li>

    <!-- Order -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Order' ? 'active' : '' }}">
      <a href="/?p=Order" class="menu-link" id="orderLink">
        <i class="menu-icon tf-icons ri-shopping-cart-2-line"></i>
        <div data-i18n="Order">Order</div>
      </a>
    </li>

    <!-- Customer -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Customer' ? 'active' : '' }}">
      <a href="/?p=Customer" class="menu-link" id="customerLink">
        <i class="menu-icon tf-icons ri-user-line"></i>
        <div data-i18n="Customer">Customer</div>
      </a>
    </li>

    <!-- Invoices -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Invoices' ? 'active' : '' }}">
      <a href="/?p=Invoices" class="menu-link" id="invoicesLink">
        <i class="menu-icon tf-icons ri-file-text-line"></i>
        <div data-i18n="Invoices">Invoices</div>
      </a>
    </li>

    <!-- Settings -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'Settings' ? 'active' : '' }}">
      <a href="/?p=Settings" class="menu-link" id="settingsLink">
        <i class="menu-icon tf-icons ri-settings-line"></i>
        <div data-i18n="Settings">Settings</div>
      </a>
    </li>

    <!-- Optional Section - divider -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Optional</span>
    </li>
    
    <!-- Optional Menu Item -->
    <li class="menu-item {{ isset($_GET['p']) && $_GET['p'] == 'HeroSection' ? 'active' : '' }}">
      <a href="/?p=HeroSection" class="menu-link" id="heroSectionLink">
        <i class="menu-icon tf-icons ri-star-line"></i>
        <div data-i18n="HeroSection">HeroSection</div>
      </a>
    </li>
  </ul>
</aside>