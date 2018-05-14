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

use CuyZ\Notiz\Core\Channel\Payload;
use CuyZ\Notiz\Core\Event\Event;
use CuyZ\Notiz\Core\Event\Service\EventFactory;
use CuyZ\Notiz\Core\Event\Support\ProvidesExampleMarkers;
use CuyZ\Notiz\Core\Property\Factory\PropertyContainer;
use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\EntityEmailNotification;
use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\Service\EntityEmailTemplateBuilder;
use CuyZ\Notiz\Domain\Property\Email;

class ShowEntityEmailController extends ShowNotificationController
{
    /**
     * @var EntityEmailNotification
     */
    protected $notification;

    /**
     * @var EventFactory
     */
    protected $eventFactory;

    /**
     * @param string $notificationIdentifier
     */
    public function showAction($notificationIdentifier)
    {
        parent::showAction($notificationIdentifier);

        $eventDefinition = $this->notification->getEventDefinition();
        $emailProperties = $eventDefinition->getPropertyDefinition(Email::class, $this->notification);

        $aze = $this->notification->getSendToProvided();
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($aze, __CLASS__ . ':' . __LINE__ . ' $aze!!');

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($emailProperties, __CLASS__ . ':' . __LINE__ . ' $emailProperties');
        $this->view->assign('emailProperties', $emailProperties);
    }

    /**
     * This action is called to show a preview of the given email notification.
     *
     * An event is simulated in order to render the original Fluid template used
     * by the notification. Example values may be added to simulate fake markers
     * in the view.
     *
     * @param string $notificationIdentifier
     */
    public function previewAction($notificationIdentifier)
    {
        $fakeEvent = $this->eventFactory->create($this->notification->getEventDefinition(), $this->notification);

        if ($fakeEvent instanceof ProvidesExampleMarkers) {
            $this->signalSlotDispatcher->connect(
                'aze',
                'test',
                function (PropertyContainer $container, Event $event) use ($fakeEvent) {
                    if ($event === $fakeEvent) {
                        $exampleMarkers = $fakeEvent->getExampleMarkers();

                        foreach ($container->getEntries() as $marker) {
                            if (isset($exampleMarkers[$marker->getName()])) {
                                $marker->setValue($exampleMarkers[$marker->getName()]);
                            }
                        }
                    }
                }
            );
        }

        $payload = new Payload($this->notification, $this->notificationDefinition, $fakeEvent);

        /** @var EntityEmailTemplateBuilder $entityEmailTemplateBuilder */
        $entityEmailTemplateBuilder = $this->objectManager->get(EntityEmailTemplateBuilder::class, $payload);

        return $entityEmailTemplateBuilder->getBody();
    }

    /**
     * @return string
     */
    public function getNotificationDefinitionIdentifier()
    {
        return EntityEmailNotification::getNotificationIdentifier();
    }

    /**
     * @param EventFactory $eventFactory
     */
    public function injectEventFactory(EventFactory $eventFactory)
    {
        $this->eventFactory = $eventFactory;
    }
}
