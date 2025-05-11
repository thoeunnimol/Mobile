<?php
      $page = "pages.Dashboard";  // Using dot notation for subfolders
      $p = "Dashboard";
      
      // Check for route parameter first
      if(isset($page_param) && !empty($page_param)) {
          $p = ucfirst($page_param); // Capitalize first letter
      }
      // Then check for query parameter as fallback
      elseif(isset($_GET['p'])) {
          $p = $_GET['p'];
      }
      
      switch($p){
          case 'Customer':
            $page = "pages.Customer";  // Using dot notation for subfolders
            break;
            
          // Add more cases for other pages as needed
          case 'Category':
            $page = "pages.Category";
            break;
          case 'Product':
            $page = "pages.Product";
            break;
          case 'HeroSection':
            $page = "pages.HeroSection";
            break;
            case 'Order':
              $page = "pages.Order";  // Using dot notation for subfolders
              break;
         
            
          // case 'Category':
          //   $page = "pages.Category";
          //   break;
            
          // Default case to handle unknown page requests
          default:
            $page = "pages.Dashboard";
            break;
      }
?>
<!DOCTYPE html>
<html lang="en">
 
@include('include.header')

<body>
<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">
    
    <!-- Sidebar -->
    @include('include.sidebar_menu')
    <!-- End Sidebar -->

    <div class="layout-page">
    @include('include.head')
      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
          @include($page)
        </div>
        <!-- / Content -->
      </div>
      <!-- / Content wrapper -->
      
    </div>
  </div>
</div>
  @include('include.footer')

</body>
</html>
