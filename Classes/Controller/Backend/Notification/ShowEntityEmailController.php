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

use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\EntityEmailNotification;
use CuyZ\Notiz\Domain\Property\Email;

class ShowEntityEmailController extends ShowNotificationController
{
    /**
     * @var EntityEmailNotification
     */
    protected $notification;

    /**
     * @param string $notificationIdentifier
     */
    public function showAction($notificationIdentifier)
    {
        parent::showAction($notificationIdentifier);

        $aze = $this->notification->getSendToProvided();
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($aze, __CLASS__ . ':' . __LINE__ . ' $aze');
        $eventDefinition = $this->notification->getEventDefinition();
        $emailProperties = $eventDefinition->getPropertiesDefinition(Email::class, $this->notification);

        $this->view->assign('emailProperties', $emailProperties);
    }

    /**
     * @return string
     */
    public function getNotificationDefinitionIdentifier()
    {
        return EntityEmailNotification::getNotificationIdentifier();
    }
}
