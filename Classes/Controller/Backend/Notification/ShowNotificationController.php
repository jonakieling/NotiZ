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

namespace CuyZ\Notiz\Controller\Backend\Notification;

use CuyZ\Notiz\Controller\Backend\BackendController;
use CuyZ\Notiz\Core\Definition\Tree\Notification\NotificationDefinition;
use CuyZ\Notiz\Core\Notification\Notification;

abstract class ShowNotificationController extends BackendController
{
    /**
     * @var NotificationDefinition
     */
    protected $notificationDefinition;

    /**
     * @var Notification
     */
    protected $notification;

    /**
     * @param string $notificationIdentifier
     */
    public function showAction($notificationIdentifier)
    {
        $definition = $this->getDefinition();
        $notificationDefinitionIdentifier = $this->getNotificationDefinitionIdentifier();

        if (!$definition->hasNotification($notificationDefinitionIdentifier)) {
            throw new \Exception('@todo'); // @todo
        }

        $this->notificationDefinition = $definition->getNotification($notificationDefinitionIdentifier);
        $this->notification = $this->notificationDefinition->getProcessor()->getNotificationFromIdentifier($notificationIdentifier);

        $this->view->assign('notificationDefinition', $this->notificationDefinition);
        $this->view->assign('notification', $this->notification);
    }

    /**
     * @return string
     */
    abstract public function getNotificationDefinitionIdentifier();

    /**
     * @todo
     */
    protected function definitionError()
    {
    }
}
