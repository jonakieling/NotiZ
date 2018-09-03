<?php

namespace CuyZ\Notiz\Controller\Backend\Notification;

use CuyZ\Notiz\Domain\Notification\Slack\Application\EntitySlack\EntitySlackNotification;

class ShowEntitySlackController extends ShowNotificationController
{
    /**
     * @return string
     */
    public function getNotificationDefinitionIdentifier()
    {
        return EntitySlackNotification::getDefinitionIdentifier();
    }
}
