<style>
    * { font-family: 'Inter', sans-serif; }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: #181818; }
    .sidebar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .submenu.open { max-height: 200px; }
    @media (max-width: 1024px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }
  </style>

<!-- Mobile Overlay -->
      <div
        id="overlay"
        class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"
        onclick="toggleSidebar()"
      ></div>

      <!-- Sidebar -->
      <aside
        id="sidebar"
        class="sidebar fixed left-0 top-0 h-screen w-[250px] bg-[#181818] rounded-tr-xl rounded-br-xl- border-r border-border flex flex-col z-50 transition-transform lg:translate-x-0"
      >
        <!-- Logo -->
        <div class="border-b border-border">
          <div class="flex items-center gap-2 p-4 bg-[#CFF752] rounded-tr-xl">
            <img src="../../images/elegance-saloon-logo-no-bg.png" alt="Elegance Logo" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Scrollable Menu -->
        <div class="flex-1 overflow-y-auto sidebar p-2">

          <!-- Home -->
          <a
            href="../Dashboard/ad_dashboard.php"
            class="flex items-center gap-3 px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg mb-1"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
              />
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
          </a>

          <!-- Users Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('usersMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                  />
                </svg>
                <span class="text-sm font-medium">User Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="usersArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="usersMenu" class="submenu pl-11">
              <a
                href="../User management/view_user.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >View Users</a
              >
              <a
                href="../User management/add_user.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Add User</a
              >
              <a
                href="../User management/edit_user.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Edit User</a
              >
              <a
                href="#"
                class="block py-2 text-sm text-white hover:text-primary"
                >Assign Roles & Permissions</a
              >
            </div>
          </div>
          <!-- Appointment Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('appointmentMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                  />
                </svg>
                <span class="text-xs font-medium text-ellipsis">Appointment Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="appointmentArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="appointmentMenu" class="submenu pl-11">
              <a
                href="../Appointments/list_appointments.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >All Appointments</a
              >
              <a
                href="../Appointments/calendar.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Appointment Calendar</a
              >
            </div>
          </div>

          <!-- Calendar
          <a
            href="#"
            class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg mb-1"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
            <span class="text-sm font-medium">Appointments Management</span>
          </a> -->

          <!-- Client Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('clientMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
                </svg>
                <span class="text-sm font-medium">Client Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="clientArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="clientMenu" class="submenu pl-11">
              <a
                href="../Clients/list_clients.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Client List</a
              >
              <a
                href="../Clients/add_client.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Add Client</a
              >
            </div>
          </div>

          <!-- Reports
          <a
            href="#"
            class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg mb-1"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
            <span class="text-sm font-medium">Reports</span>
          </a> -->

          <!-- Staff Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('staffMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Staff Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="staffArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="staffMenu" class="submenu pl-11">
              <a
                href="../Staff/list_staff.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Staff List</a
              >
              <a
                href="../Staff/add_staff.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Add Staff</a
              >
            </div>
          </div>

          <!-- Services Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('serviceMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Services Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="serviceArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="serviceMenu" class="submenu pl-11">
              <a
                href="../Services/list_services.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Service List</a
              >
              <a
                href="../Services/add_service.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Add Service</a
              >
            </div>
          </div>

          <!-- Inventory with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('inventoryMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Inventory Management</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="inventoryArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="inventoryMenu" class="submenu pl-11">
              <a
                href="../Products/list_products.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Products List</a
              >
              <a
                href="../Products/add_product.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Add Product</a
              >
            </div>
          </div>

          <!-- Reviews Management -->
          <a
            href="../Reviews/list_reviews.php"
            class="flex items-center gap-3 px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg mb-1"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <span class="text-sm font-medium">Manage Reviews</span>
          </a>

          <!-- Reports & Analytics with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('reportsMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Reports & Analytics</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="reportsArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="reportsMenu" class="submenu pl-11">
              <a
                href="../Reports/appointments_reports.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Appointments Reports</a
              >
              <a
                href="../Reports/sales_reports.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Sales & Revenue Reports</a
              >
            </div>
          </div>

          <!-- Payment & Invoice Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('paymentMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Payment & Invoice</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="paymentArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="paymentMenu" class="submenu pl-11">
              <a
                href="../Payments/payment_records.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Payment Records</a
              >
              <a
                href="../Payments/invoice_list.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Invoice List</a
              >
            </div>
          </div>

          <!-- Discounts Management -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('discountsMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Discounts</span>
              </div>
              <svg class="w-4 h-4 transition-transform" id="discountsArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <div id="discountsMenu" class="submenu pl-11">
              <a href="../Discounts/list_discounts.php" class="block py-2 text-sm text-white hover:text-primary">Manage Discounts</a>
              <a href="../Discounts/add_discount.php" class="block py-2 text-sm text-white hover:text-primary">Add Discount</a>
            </div>
          </div>

          <!-- Notification Management with submenu -->
          <div class="mb-1">
            <button
              onclick="toggleSubmenu('notificationsMenu')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg"
            >
              <div class="flex items-center gap-3">
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <span class="text-sm font-medium">Notification Settings</span>
              </div>
              <svg
                class="w-4 h-4 transition-transform"
                id="notificationsArrow"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>
            <div id="notificationsMenu" class="submenu pl-11">
              <a
                href="../Notifications/settings.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >WhatsApp Settings</a
              >
              <a
                href="../Account/settings.php"
                class="block py-2 text-sm text-white hover:text-primary"
                >Account Settings</a
              >
            </div>

          <!-- Logout -->
          <a
            href="../AD_logout.php"
            class="flex items-center gap-3 px-3 py-2.5 text-white hover:bg-red-600 rounded-lg mb-3 mt-4"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m4-10V5a2 2 0 10-4 0v1"
              />
            </svg>
            <span class="text-sm font-medium">Logout</span>
          </a>
          </div>

          <p class="text-xs font-medium text-gray-400 mb-3 mt-6 px-3">
            PREFERENCES
          </p>

          <!-- Online Profile -->
          <a
            href="../../src/Elegance_Salon.php" target="_blank"
            class="flex items-center gap-3 px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg mb-1"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
              />
            </svg>
            <span class="text-sm font-medium">Online Profile</span>
          </a>

          <!-- Account Setting -->
          <a
            href="#"
            class="flex items-center gap-3 px-3 py-2.5 text-white hover:bg-gray-600 rounded-lg mb-1"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
            </svg>
            <span class="text-sm font-medium">Account Setting</span>
          </a>
        </div>

        <!-- Profile Setup -->
        <!-- <div class="p-4 border-t border-border">
          <div class="flex items-center gap-3 mb-3">
            <div class="relative">
              <svg class="w-12 h-12 transform -rotate-90">
                <circle
                  cx="24"
                  cy="24"
                  r="20"
                  fill="none"
                  stroke="#E5E7EB"
                  stroke-width="4"
                />
                <circle
                  cx="24"
                  cy="24"
                  r="20"
                  fill="none"
                  stroke="#22C55E"
                  stroke-width="4"
                  stroke-dasharray="125.6"
                  stroke-dashoffset="50"
                  stroke-linecap="round"
                />
              </svg>
              <span
                class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-green-500"
                >60%</span
              >
            </div>
            <div>
              <p class="text-sm font-medium text-gray-800">Profile Setup</p>
              <p class="text-xs text-gray-400">Dec 14, 2023</p>
            </div>
          </div>
          <button
            class="w-full bg-primary text-white py-2.5 rounded-lg text-sm font-medium hover:bg-purple-700 transition"
          >
            Complete Setup
          </button>
        </div> -->
      </aside>
        <!-- End of Sidebar -->
<script>
    // SubMenu Toggle
        function toggleSubmenu(id) {
        const menu = document.getElementById(id);
        const arrow = document.getElementById(id.replace("Menu", "Arrow"));
        menu.classList.toggle("open");
        if (arrow) arrow.classList.toggle("rotate-180");
      }

      // Mobile Menu Toggle
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        sidebar.classList.toggle("open");
        overlay.classList.toggle("hidden");
      }
</script>