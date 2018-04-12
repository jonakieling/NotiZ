<?php

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
