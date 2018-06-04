<?php

/*
 * Copyright (C) 2018
 * Nathan Boiron <nathan.boiron@gmail.com>
 * Romain Canon <romain.hydrocanon@gmail.com>
 *
 * This file is part of the TYPO3 NotiZ project.
 * It is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License, either
 * version 3 of the License, or any later version.
 *
 * For the full copyright and license information, see:
 * http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CuyZ\Notiz\Controller\Backend;

use CuyZ\Notiz\Service\LocalizationService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class IndexController extends BackendController
{
    /**
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        $this->view->assign('definition', $this->getDefinition());
    }

    /**
     * Lists all the existing notification types (email, log, Slack, etc.).
     */
    public function listNotificationTypesAction()
    {
    }

    /**
     * Lists all notifications entries belonging to a given type.
     *
     * @param string $notificationIdentifier
     */
    public function listNotificationsAction($notificationIdentifier)
    {
        $definition = $this->getDefinition();

        if (!$definition->hasNotification($notificationIdentifier)) {
            $this->addFlashMessage(
                LocalizationService::localize('Backend/Module/Index/ListNotifications:notification_type_not_found', [$notificationIdentifier]),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('listNotificationTypes');
        }

        $this->view->assign('notificationDefinition', $definition->getNotification($notificationIdentifier));
    }

    /**
     * Lists all registered events.
     */
    public function listEventsAction()
    {
    }

    /**
     * Show detailed information about a given event.
     *
     * @param string $eventIdentifier
     */
    public function showEventAction($eventIdentifier)
    {
        $definition = $this->getDefinition();

        if (!$definition->hasEventFromFullIdentifier($eventIdentifier)) {
            $this->addFlashMessage(
                LocalizationService::localize('Backend/Module/Index/ShowEvent:event_not_found', [$eventIdentifier]),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('listEvents');
        }

        $eventDefinition = $definition->getEventFromFullIdentifier($eventIdentifier);

        $notifications = [];

        foreach ($definition->getNotifications() as $notification) {
            $notifications[] = [
                'definition' => $notification,
                'count' => $notification->getProcessor()->countNotificationsFromEventDefinition($eventDefinition),
            ];
        }

        $this->view->assign('eventDefinition', $eventDefinition);
        $this->view->assign('notifications', $notifications);
    }
}
