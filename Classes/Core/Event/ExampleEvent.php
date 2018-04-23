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

namespace CuyZ\Notiz\Core\Event;

use CuyZ\Notiz\Core\Definition\Tree\EventGroup\Event\EventDefinition;
use CuyZ\Notiz\Core\Event\Service\EventFactory;
use CuyZ\Notiz\Core\Notification\Notification;
use CuyZ\Notiz\Core\Property\Factory\PropertyContainer;
use CuyZ\Notiz\Core\Property\Factory\PropertyDefinition;
use CuyZ\Notiz\Core\Property\Factory\PropertyFactory;
use CuyZ\Notiz\Domain\Property\Marker;

class ExampleEvent implements Event
{
    /**
     * @var Event
     */
    protected $originalEvent;

    /**
     * @param Event $originalEvent
     */
    public function __construct(EventDefinition $eventDefinition, Notification $notification, EventFactory $eventFactory)
    {
        $this->originalEvent = $eventFactory->create($eventDefinition, $notification);
    }

    public function fillPropertyEntries(PropertyContainer $container)
    {
        $this->originalEvent->fillPropertyEntries($container);

        if ($container->getPropertyType() !== Marker::class) {
            return;
        }

        if (!$this->originalEvent instanceof ProvidesExampleMarkers) {
            return;
        }

        $exampleMarkers = $this->originalEvent->getExampleMarkers();

        foreach ($container->getEntries() as $marker) {
            if (isset($exampleMarkers[$marker->getName()])) {
                $marker->setValue($exampleMarkers[$marker->getName()]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function buildPropertyDefinition(PropertyDefinition $definition, Notification $notification)
    {
        // @todo!!!
        AbstractEvent::buildPropertyDefinition($definition, $notification);
    }

    /**
     * @inheritdoc
     */
    public function getProperties($propertyClassName)
    {
        return PropertyFactory::get()->getProperties($propertyClassName, $this);
    }

    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return $this->originalEvent->getDefinition();
    }

    /**
     * @inheritdoc
     */
    public function getNotification()
    {
        return $this->originalEvent->getNotification();
    }
}
