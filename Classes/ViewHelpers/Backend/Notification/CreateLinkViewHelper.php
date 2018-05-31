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

use CuyZ\Notiz\Core\Definition\Tree\EventGroup\Event\EventDefinition;
use CuyZ\Notiz\Core\Definition\Tree\Notification\NotificationDefinition;
use CuyZ\Notiz\Domain\Notification\EntityNotification;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class CreateLinkViewHelper extends AbstractTagBasedViewHelper
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
            'notificationDefinition',
            NotificationDefinition::class,
            '',
            true
        );

        $this->registerArgument(
            'eventDefinition',
            EventDefinition::class,
            ''
        );

        $this->registerArgument(
            'addUriTemplate',
            'bool',
            ''
        );
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        /** @var NotificationDefinition $notificationDefinition */
        $notificationDefinition = $this->arguments['notificationDefinition'];

        /** @var EventDefinition $eventDefinition */
        $eventDefinition = $this->arguments['eventDefinition'];

        /** @var EntityNotification $className */
        $className = $notificationDefinition->getClassName();

        if (!in_array(EntityNotification::class, class_parents($className))) {
            return '';
        }

        $tableName = $className::getTableName();

        $href = BackendUtility::getModuleUrl(
            'record_edit',
            [
                "edit[$tableName][0]" => 'new',
                'returnUrl' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            ]
        );

        if ($eventDefinition) {
            $href .= "&selectedEvent={$eventDefinition->getFullIdentifier()}";
        }

        if ($this->arguments['addUriTemplate']) {
            $this->tag->addAttribute('data-href', $href . '&selectedEvent=#EVENT#');
        }

        $this->tag->addAttribute('href', $href);
        $this->tag->setContent($this->renderChildren());

        return $this->tag->render();
    }
}
