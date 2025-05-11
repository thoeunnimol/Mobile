<nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
  <div class="container-fluid">
      <!-- Mobile menu toggle -->
      <button class="navbar-toggler border-0 px-3 d-xl-none" type="button" id="menuToggle">
        <i class="ri-menu-fill" style="font-size: 24px;"></i>
      </button>

      <!-- Search bar -->
      <div class="navbar-nav align-items-center flex-grow-1">
        <form class="w-100" id="searchForm">
          <div class="input-group input-group-merge">
            <span class="input-group-text bg-transparent border-0">
              <i class="ri-search-line" style="font-size: 20px;"></i>
            </span>
            <input
              type="text"
              class="form-control border-0 shadow-none bg-transparent"
              placeholder="Search..."
              id="searchQuery"
            />
          </div>
        </form>
      </div>

      <!-- Right side icons -->
      <div class="navbar-nav align-items-center ms-auto">
        <!-- Notifications -->
        <div class="nav-item dropdown mx-2">
          <button class="nav-link dropdown-toggle hide-arrow position-relative" id="notificationsDropdown">
            <i class="ri-notification-line" style="font-size: 20px;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="unreadBadge">
              0
            </span>
          </button>
          <div class="dropdown-menu dropdown-menu-end py-0 mt-2" style="width: 300px;">
            <div class="dropdown-header border-bottom">
              <h6 class="m-0">Notifications</h6>
              <span class="text-muted small" id="notificationCount">0 New</span>
            </div>
            <div class="notification-list" style="max-height: 300px; overflow-y: auto;" id="notificationList">
              <!-- Notifications will be dynamically inserted here -->
            </div>
            <div class="dropdown-footer text-center py-2">
              <a href="#" class="text-primary">View all notifications</a>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <div class="nav-item dropdown mx-2">
          <button class="nav-link dropdown-toggle hide-arrow">
            <i class="ri-mail-line" style="font-size: 20px;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              3
            </span>
          </button>
        </div>

        <!-- User profile -->
        <div class="nav-item dropdown">
          <button class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown">
            <div class="avatar avatar-sm me-2">
              <img 
                src="https://ui-avatars.com/api/?name=User&background=random"
                alt="User"
                class="rounded-circle" 
                width="32"
                height="32"
                id="userAvatar"
              />
            </div>
            <span class="d-none d-md-inline" id="userName">User</span>
          </button>
          
          <div class="dropdown-menu dropdown-menu-end mt-2 py-2" id="profileDropdownMenu">
            <div class="dropdown-header px-3 py-2">
              <div class="d-flex align-items-center">
                <div class="avatar me-3">
                  <img 
                    src="https://ui-avatars.com/api/?name=User&background=random"
                    alt="User"
                    class="rounded-circle"
                    width="40"
                    height="40"
                    id="userAvatarLarge"
                  />
                </div>
                <div>
                  <h6 class="mb-0" id="dropdownUserName">User</h6>
                  <small class="text-muted" id="dropdownUserEmail">user@example.com</small>
                </div>
              </div>
            </div>
            <div class="dropdown-divider my-1"></div>
            <!-- Menu items will be dynamically inserted here -->
          </div>
        </div>
      </div>
    </div>
</nav>