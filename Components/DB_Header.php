<!-- Header -->
<header
  class="bg-white border-b border-border px-4 mb-4 lg:px-6 py-4 flex items-center justify-between sticky top-0 z-30">
  <div class="flex items-center gap-4">
    <button
      onclick="toggleSidebar()"
      class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
      <svg
        class="w-6 h-6"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
    <div class="relative hidden sm:block">
      <h1 class="text-3xl md:text-4xl text-[#1a1333] ml-12 md:ml-0">
        <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard'; ?>
      </h1>
    </div>
  </div>
  <div class="flex items-center gap-4 relative">
    <!-- Notification bell -->
    <button id="notifButton" class="p-2 hover:bg-gray-100 rounded-lg relative">
      <svg
        class="w-5 h-5 text-gray-500"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <span id="notifBadge" class="hidden absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center"></span>
    </button>
    <div
      id="notifDropdown"
      class="hidden absolute right-20 top-11 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-40 max-h-96 overflow-y-auto">
      <div class="px-4 py-2 border-b sticky top-0 bg-white">
        <p class="text-sm font-semibold text-gray-800">Notifications</p>
      </div>
      <div id="notifContent" class="p-4">
        <p class="text-sm text-gray-500">Loading...</p>
      </div>
    </div>

    <!-- Messages icon -->
    <button id="msgButton" class="p-2 hover:bg-gray-100 rounded-lg relative">
      <svg
        class="w-5 h-5 text-gray-500"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
      <span id="msgBadge" class="hidden absolute -top-0.5 -right-0.5 w-5 h-5 bg-blue-500 rounded-full text-white text-xs flex items-center justify-center"></span>
    </button>
    <div
      id="msgDropdown"
      class="hidden absolute right-4 top-11 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-40 max-h-96 overflow-y-auto">
      <div class="px-4 py-2 border-b sticky top-0 bg-white">
        <p class="text-sm font-semibold text-gray-800">Messages</p>
      </div>
      <div id="msgContent" class="p-4">
        <p class="text-sm text-gray-500">Loading...</p>
      </div>
    </div>

    <div class="flex items-center gap-3 pl-4 border-l border-border">
      <div class="text-right hidden sm:block">
        <p class="text-sm font-medium text-gray-800">
          <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin'; ?>
        </p>
        <p class="text-xs text-gray-400">
          <?php echo isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 ? 'Administrator' : 'Staff'; ?>
        </p>
      </div>
      <img
        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
        alt="Profile"
        class="w-10 h-10 rounded-full object-cover" />
    </div>
  </div>
</header>

<script>
  (function () {
    const notifBtn = document.getElementById('notifButton');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifContent = document.getElementById('notifContent');
    const notifBadge = document.getElementById('notifBadge');
    const msgBtn = document.getElementById('msgButton');
    const msgDropdown = document.getElementById('msgDropdown');
    const msgContent = document.getElementById('msgContent');
    const msgBadge = document.getElementById('msgBadge');

    function toggleDropdown(dropdown) {
      if (!dropdown) return;
      dropdown.classList.toggle('hidden');
    }

    // Load notifications
    function loadNotifications() {
      fetch('../api/get_notifications.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            notifContent.innerHTML = '<p class="text-sm text-gray-500">Error loading notifications.</p>';
            return;
          }
          
          const notifications = data.notifications || [];
          const count = data.count || 0;
          
          if (count > 0) {
            notifBadge.textContent = count > 9 ? '9+' : count;
            notifBadge.classList.remove('hidden');
          } else {
            notifBadge.classList.add('hidden');
          }
          
          if (notifications.length === 0) {
            notifContent.innerHTML = '<p class="text-sm text-gray-500">No new notifications.</p>';
          } else {
            let html = '';
            notifications.forEach(notif => {
              const date = new Date(notif.date);
              const timeAgo = getTimeAgo(date);
              const iconColor = notif.type === 'stock' && notif.status === 'critical' ? 'text-red-500' : 'text-blue-500';
              html += `
                <div class="py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 ${iconColor === 'text-red-500' ? 'bg-red-100' : 'bg-blue-100'} rounded-full flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${notif.type === 'stock' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'}"></path>
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800">${notif.title}</p>
                      <p class="text-xs text-gray-600 mt-1">${notif.message}</p>
                      <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                    </div>
                  </div>
                </div>
              `;
            });
            notifContent.innerHTML = html;
          }
        })
        .catch(error => {
          console.error('Error loading notifications:', error);
          notifContent.innerHTML = '<p class="text-sm text-gray-500">Error loading notifications.</p>';
        });
    }

    // Load messages
    function loadMessages() {
      fetch('../api/get_messages.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            msgContent.innerHTML = '<p class="text-sm text-gray-500">Error loading messages.</p>';
            return;
          }
          
          const messages = data.messages || [];
          const count = data.count || 0;
          
          if (count > 0) {
            msgBadge.textContent = count > 9 ? '9+' : count;
            msgBadge.classList.remove('hidden');
          } else {
            msgBadge.classList.add('hidden');
          }
          
          if (messages.length === 0) {
            msgContent.innerHTML = '<p class="text-sm text-gray-500">No new messages.</p>';
          } else {
            let html = '';
            messages.forEach(msg => {
              const date = new Date(msg.date);
              const timeAgo = getTimeAgo(date);
              const messagePreview = msg.message.length > 50 ? msg.message.substring(0, 50) + '...' : msg.message;
              html += `
                <div class="py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800">${msg.name}${msg.procedure ? ' - ' + msg.procedure : ''}</p>
                      <p class="text-xs text-gray-600 mt-1">${messagePreview}</p>
                      <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                    </div>
                  </div>
                </div>
              `;
            });
            msgContent.innerHTML = html;
          }
        })
        .catch(error => {
          console.error('Error loading messages:', error);
          msgContent.innerHTML = '<p class="text-sm text-gray-500">Error loading messages.</p>';
        });
    }

    function getTimeAgo(date) {
      const now = new Date();
      const diff = Math.floor((now - date) / 1000);
      if (diff < 60) return 'Just now';
      if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
      if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
      if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
      return date.toLocaleDateString();
    }

    if (notifBtn && notifDropdown) {
      notifBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleDropdown(notifDropdown);
        if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
          loadNotifications();
        }
        if (msgDropdown && !msgDropdown.classList.contains('hidden')) {
          msgDropdown.classList.add('hidden');
        }
      });
    }

    if (msgBtn && msgDropdown) {
      msgBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleDropdown(msgDropdown);
        if (msgDropdown && !msgDropdown.classList.contains('hidden')) {
          loadMessages();
        }
        if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
          notifDropdown.classList.add('hidden');
        }
      });
    }

    document.addEventListener('click', function () {
      if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
        notifDropdown.classList.add('hidden');
      }
      if (msgDropdown && !msgDropdown.classList.contains('hidden')) {
        msgDropdown.classList.add('hidden');
      }
    });

    // Load initial counts
    loadNotifications();
    loadMessages();
    
    // Refresh every 30 seconds
    setInterval(() => {
      loadNotifications();
      loadMessages();
    }, 30000);
  })();
</script>