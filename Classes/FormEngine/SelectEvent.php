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

namespace CuyZ\Notiz\FormEngine;

use CuyZ\Notiz\Core\Definition\DefinitionService;
use CuyZ\Notiz\Core\Exception\EntryNotFoundException;
use CuyZ\Notiz\Service\Container;
use TYPO3\CMS\Backend\Form\Element\SelectSingleElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SelectEvent extends SelectSingleElement
{
    /**
     * Will check if an argument `selectedEvent` exists and matches an existing
     * event, in which case this event will be selected.
     *
     * @return array
     */
    public function render()
    {
        $definitionService = Container::get(DefinitionService::class);

        $selectedEvent = GeneralUtility::_GP('selectedEvent');

        if ($selectedEvent) {
            try {
                $this->data['parameterArray']['itemFormElValue'][0] = $definitionService->getDefinition()->getEventFromFullIdentifier($selectedEvent)->getFullIdentifier();
            } catch (EntryNotFoundException $exception) {
            }
        }

        return parent::render();
    }
}
