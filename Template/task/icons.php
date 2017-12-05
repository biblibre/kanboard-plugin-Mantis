<?php
    $user_id = $this->task->userSession->getId();
    $notifications = $this->task->userUnreadNotificationModel->getAll($user_id);
    $notifications = array_filter($notifications, function ($notification) use ($task) {
        return $notification['event_data']['task']['id'] == $task['id'];
    });
    if (!empty($notifications)) {
        echo '<i aria-hidden="true" class="fa fa-bell-o"></i>';
    }
