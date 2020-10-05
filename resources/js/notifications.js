const NOTIFICATION_TYPES = {
    follow: 'App\\Notifications\\UserFollowed'
}

export function makeNotification(notification) {
    var notificationMessage = makeNotificationMessage(notification)
    var to = routeNotification(notification)
    return '{message => ' + notificationMessage + ', route => ' + to + '}'
}

export function makeNotificationMessage(notification) {
    if (notification.type === NOTIFICATION_TYPES.follow) {
        return notification.data.follower_name + 'さんにフォローされました'
    }
}

export function routeNotification(notification) {
    var to = '?read=' + notification.id
    if (notification.type === NOTIFICATION_TYPES.follow) {
        return to = '/users/' + notification.data.follower_id + to
    }
}