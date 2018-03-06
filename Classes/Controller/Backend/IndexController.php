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

use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\EntityEmailNotification;
use CuyZ\Notiz\Domain\Property\Email;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class IndexController extends BackendController
{
    public function indexAction()
    {
        $this->view->assign('definition', $notifications = $this->definitionService->getDefinition());
        $this->view->assign('user', $GLOBALS['BE_USER']->user);

        // tmp
        /** @var EntityEmailNotification $email */
        $email = $this->definitionService
            ->getDefinition()
            ->getNotification('entityEmail')
            ->getProcessor()
            ->getNotificationFromIdentifier(1);

        $this->view->assign('myEmail', $email);
        
        $eventDefinition = $email->getEventDefinition();
        $emailProperties = $eventDefinition->getPropertiesDefinition(Email::class, $email);

        $this->view->assign('emailProperties', $emailProperties);
    }

    /**
     * @todo
     */
    protected function definitionError()
    {
    }
}
