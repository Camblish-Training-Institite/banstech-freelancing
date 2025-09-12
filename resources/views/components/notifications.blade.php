<!--
  This component will display the notification bell and dropdown.
  It uses plain JavaScript for dropdown toggling.
-->
<div class="relative" id="notification-component">
    <button id="notification-bell-button" class="relative z-10 block p-2 text-gray-400 hover:text-white focus:outline-none">
        {{-- <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg> --}}
        <i class="fas fa-bell notification-icon"></i>
        <div id="notification-badge" class="absolute top-1 right-1 px-1.5 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full {{ $user->notifications->whereNull('read_at')->count() > 0 ? '' : 'hidden' }}">{{ $user->notifications->whereNull('read_at')->count() }}</div>
    </button>

    <div id="notification-dropdown-panel" class="absolute right-0 z-20 w-80 mt-2 overflow-hidden bg-gray-800 rounded-lg shadow-xl hidden">
        <div class="py-2">
            <div class="px-4 py-2 flex justify-between items-center border-b border-gray-700">
                <p class="font-semibold text-white">Notifications</p>
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-400 hover:underline">Mark all as read</button>
                </form>
            </div>
            @php
                // dd($user->notifications);
            @endphp
            <div id="notification-list" class="max-h-80 overflow-y-auto">
                @forelse ($user->notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="flex items-center px-4 py-3 border-b hover:bg-gray-700 -mx-2 border-gray-700 {{ is_null($notification->read_at) ? 'bg-gray-700' : '' }}" data-id="{{ $notification->id }}"> 
                        @if (is_null($notification->read_at))
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 flex-shrink-0"></div>
                            <p class="text-sm text-gray-300 mx-2">{{ $notification->data['message'] ?? 'No message' }}</p>
                            <a href="{{route('notifications.markAsReadSingle', $notification->id)}}" class="text-sm font-medium text-blue-600 hover:underline">Mark as read</p>
                        @else
                            <div class="w-3 h-3 mr-3 flex-shrink-0"></div>
                            <p class="text-sm text-gray-500 mx-2">{{ $notification->data['message'] ?? 'No message' }}</p>
                        @endif
                    </a>
                @empty
                    <p>No notifications yet</p>
                @endforelse
            </div>
             <a href="#" class="block px-4 py-3 text-sm text-center font-medium text-gray-300 bg-gray-900 hover:bg-gray-700">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userId = "{{ Auth::id() }}";

    // --- Dropdown Toggle Logic ---
    const notificationComponent = document.getElementById('notification-component');
    const notificationButton = document.getElementById('notification-bell-button');
    const notificationPanel = document.getElementById('notification-dropdown-panel');

    notificationButton.addEventListener('click', function(event) {
        // Stop the click from bubbling up to the document listener
        event.stopPropagation();
        notificationPanel.classList.toggle('hidden');
    });

    // Hide dropdown if clicked outside
    document.addEventListener('click', function(event) {
        if (!notificationComponent.contains(event.target)) {
            notificationPanel.classList.add('hidden');
        }
    });


    // --- Notification Fetching and Real-time Logic (Unchanged) ---
    const notificationBadge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');

    let unreadCount = 0;

    const updateBadge = (count) => {
        unreadCount = count;
        if (unreadCount > 0) {
            notificationBadge.innerText = unreadCount > 9 ? '9+' : unreadCount;
            notificationBadge.classList.remove('hidden');
        } else {
            notificationBadge.classList.add('hidden');
        }
    };

    const createNotificationElement = (notification) => {
        const link = document.createElement('a');
        link.href = notification.data.url || '#';
        link.dataset.id = notification.id;
        link.className = 'flex items-center px-4 py-3 border-b hover:bg-gray-700 -mx-2 border-gray-700';

        if (!notification.read_at) {
             link.innerHTML = `<div class="w-3 h-3 bg-blue-500 rounded-full mr-3 flex-shrink-0"></div> <p class="text-sm text-gray-300 mx-2">${notification.data.message}</p>`;
        } else {
             link.innerHTML = `<div class="w-3 h-3 mr-3 flex-shrink-0"></div> <p class="text-sm text-gray-500 mx-2">${notification.data.message}</p>`;
        }
        return link;
    };

    fetch('{{ route("notifications.index") }}')
        .then(response => response.json())
        .then(data => {
            notificationList.innerHTML = '';
            updateBadge(data.unread.length);

            if (data.unread.length === 0 && data.read.length === 0) {
                notificationList.innerHTML = '<p class="text-center text-gray-400 py-4">No notifications yet.</p>';
                return;
            }

            data.unread.forEach(notification => {
                notificationList.appendChild(createNotificationElement(notification));
            });

             data.read.forEach(notification => {
                notificationList.appendChild(createNotificationElement(notification));
            });
        });

    if (userId) {
        window.Echo.private('App.Models.User.' + userId)
            .notification((notification) => {
                console.log('New notification received:', notification);
                const notificationElement = createNotificationElement(notification);
                notificationList.prepend(notificationElement);
                updateBadge(unreadCount + 1);
            });
    }
});
</script>

