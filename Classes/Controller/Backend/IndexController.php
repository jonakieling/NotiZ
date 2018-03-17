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

use CuyZ\Notiz\Core\Definition\Tree\Definition;
use CuyZ\Notiz\Core\Exception\EntryNotFoundException;
use CuyZ\Notiz\Domain\Property\Email;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class IndexController extends BackendController
{
    /**
     * @todo
     *
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        $this->view->assign('definition', $this->getDefinition());
    }

    public function listNotificationTypesAction()
    {
        $this->view->assign('user', $GLOBALS['BE_USER']->user);
    }

    /**
     * @param string $notificationIdentifier
     * @param string $filterEvent
     * @throws \Exception
     */
    public function listNotificationsAction($notificationIdentifier, $filterEvent = null)
    {
        $definition = $this->getDefinition();

        if (!$definition->hasNotification($notificationIdentifier)) {
            throw new \Exception('@todo'); // @todo
        }

        $this->view->assign('notificationDefinition', $definition->getNotification($notificationIdentifier));
    }

    public function listEventsAction()
    {
    }

    /**
     * @param string $eventIdentifier
     */
    public function showEventAction($eventIdentifier)
    {
        $definition = $this->getDefinition();

        try {
            $eventDefinition = $definition->getEventFromFullIdentifier($eventIdentifier);
        } catch (EntryNotFoundException $exception) {
            throw new \Exception('@todo'); // @todo
        }

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

    /**
     * @todo
     */
    protected function definitionError()
    {
    }
}
