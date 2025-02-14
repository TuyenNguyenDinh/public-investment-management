import {CSRF_TOKEN} from "../../config.js";

$(function () {
    $('li.notifications').on('click', function () {
        fetchNotifications().then(() => console.log('Notifications fetched'));
    });

    $('.mark-all-read').on('click', function () {
        markAllNotificationsAsRead().then(() => 'All notifications marked as read');
    });

    async function markAllNotificationsAsRead() {
        try {
            const response = await fetch('/api/v1/notifications/mark-as-read', {
                method: 'PUT',
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN}
            });
            if (!response.ok) throw new Error('Failed to mark all notifications as read');
            $('#notification-count').hide();
        } catch (error) {
            toastr.error(translations.error_occurred)
            console.error('Error marking all notifications as read:', error);
        }
    }

// Hàm chính để xử lý fetch notifications
    async function fetchNotifications() {
        try {
            const res = await getNotificationsData();
            if (res.data.count.length > 0) updateNotificationCount(res.data.count);
            renderNotifications(res.data.notifications);
        } catch (error) {
            toastr.error(translations.error_occurred)
            console.error('Error fetching notifications:', error);
        }
    }

// Hàm gọi API và trả về dữ liệu
    const getNotificationsData = async () => {
        const response = await fetch('/api/v1/notifications');
        if (!response.ok) throw new Error('Failed to fetch notifications');
        return response.json();
    };

// Hàm cập nhật số lượng thông báo mới
    const updateNotificationCount = (count) => {
        const notificationCountElement = document.getElementById('notification-count');
        if (notificationCountElement) {
            notificationCountElement.innerText = count;
        }
    };

// Hàm render danh sách thông báo
    const renderNotifications = (notifications) => {
        const notificationsList = document.getElementById('notifications-list');
        if (!notificationsList) return;

        notificationsList.innerHTML = ''; // Xóa danh sách cũ

        if (notifications.length > 0) {
            notifications.forEach(notification => {
                notificationsList.appendChild(createNotificationItem(notification));
            });
        } else {
            notificationsList.appendChild(createNoNotificationItem());
        }
    };

// Hàm tạo một item thông báo
    const createNotificationItem = (notification) => {
        const notificationItem = document.createElement('li');
        const isUnread = notification.status === 'unread';
        notificationItem.className = `list-group-item list-group-item-action dropdown-notifications-item ${
            isUnread ? 'notification-unread' : ''
        }`;
        notificationItem.innerHTML = `
        <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <div class="avatar">
                  <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-chart-pie"></i></span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1 small">${notification.title}</h6>
                <small class="mb-1 d-block text-body">${notification.content}</small>
                <small class="text-muted">${notification.created_at}</small>
            </div>
        </div>
    `;
        return notificationItem;
    };

// Hàm tạo item thông báo "Không có thông báo"
    const createNoNotificationItem = () => {
        const noNotificationItem = document.createElement('li');
        noNotificationItem.classList.add('list-group-item', 'list-group-item-action', 'dropdown-notifications-item');
        noNotificationItem.innerHTML = `
        <div class="d-flex">
            <div class="flex-grow-1">
                <h6 class="mb-1 small">${translations.no_notification}</h6>
            </div>
        </div>
    `;
        return noNotificationItem;
    };

});
