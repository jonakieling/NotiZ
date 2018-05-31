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

namespace CuyZ\Notiz\ViewHelpers\Backend\Notification;

use CuyZ\Notiz\Core\Notification\Notification;
use CuyZ\Notiz\Domain\Notification\EntityNotification;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class EditLinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @inheritdoc
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerUniversalTagAttributes();

        $this->registerArgument(
            'notification',
            Notification::class,
            '',
            true
        );
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        /** @var Notification $notification */
        $notification = $this->arguments['notification'];

        if (!$notification instanceof EntityNotification) {
            return '';
        }

        $identifier = $notification->getNotificationDefinition()->getIdentifier();
        $tableName = $notification::getTableName();
        $uid = $notification->getUid();

        $href = BackendUtility::getModuleUrl(
            'record_edit',
            [
                "edit[$tableName][$uid]" => 'edit',
                'returnUrl' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL') . "#$identifier-$uid",
            ]
        );

        $this->tag->addAttribute('href', $href);
        $this->tag->setContent($this->renderChildren());

        return $this->tag->render();
    }
}
