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

namespace CuyZ\Notiz\Backend\FormEngine\ButtonBar;

use CuyZ\Notiz\Backend\Module\IndexModuleManager;
use CuyZ\Notiz\Core\Definition\DefinitionService;
use CuyZ\Notiz\Domain\Notification\EntityNotification;
use ReflectionClass;
use TYPO3\CMS\Backend\Controller\EditDocumentController;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Adds a button "View details" to the button bar at the top of the screen when
 * editing a notification record.
 *
 * Clicking this button loads the NotiZ module showing more information about
 * the current notification.
 */
class ShowNotificationDetailsButton implements SingletonInterface
{
    /**
     * @var DefinitionService
     */
    protected $definitionService;

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * @var IndexModuleManager
     */
    protected $indexModuleManager;

    /**
     * @param DefinitionService $definitionService
     * @param IconFactory $iconFactory
     * @param IndexModuleManager $indexModuleManager
     */
    public function __construct(DefinitionService $definitionService, IconFactory $iconFactory, IndexModuleManager $indexModuleManager)
    {
        $this->definitionService = $definitionService;
        $this->iconFactory = $iconFactory;
        $this->indexModuleManager = $indexModuleManager;
    }

    /**
     * @param EditDocumentController $controller
     */
    public function addButton(EditDocumentController $controller)
    {
        foreach ($this->definitionService->getDefinition()->getNotifications() as $notificationDefinition) {
            /** @var EntityNotification $className */
            $className = $notificationDefinition->getClassName();

            // Works only with TYPO3 entity notifications.
            if (!in_array(EntityNotification::class, class_parents($className))) {
                continue;
            }

            $tableName = $className::getTableName();

            if (!isset($controller->editconf[$tableName])) {
                continue;
            }

            $uid = reset(array_keys($controller->editconf[$tableName]));

            // We show the button only for existing records being edited.
            if ($controller->editconf[$tableName][$uid] !== 'edit') {
                continue;
            }

            /** @var EntityNotification $notification */
            $notification = $notificationDefinition->getProcessor()->getNotificationFromIdentifier($uid);

            $this->qsd($controller, $notification);

            break;
        }
    }

    /**
     * @todo
     *
     * @param EditDocumentController $controller
     * @param EntityNotification $notification
     */
    protected function qsd(EditDocumentController $controller, EntityNotification $notification)
    {
        $moduleTemplate = $this->getModuleTemplate($controller);
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $uri = $this->indexModuleManager
            ->getUriBuilder()
            ->forController($this->indexModuleManager->controllerToShowNotification($notification->getNotificationDefinition()))
            ->forAction('show')
            ->withArguments(['notificationIdentifier' => $notification->getUid()])
            ->build();

        $button = $buttonBar->makeLinkButton()
            ->setShowLabelText(true)
            ->setHref($uri)
            ->setTitle('View details') // @todo translate
            ->setIcon($this->iconFactory->getIcon(
                'actions-view',
                Icon::SIZE_SMALL
            ));

        $buttonBar->addButton($button, ButtonBar::BUTTON_POSITION_LEFT, 50);
    }

    /**
     * Unfortunately TYPO3 doesn't provide a public API to access the module
     * template and add an icon to it, so we need to cheat a bit.
     *
     * @param EditDocumentController $controller
     * @return ModuleTemplate
     */
    protected function getModuleTemplate(EditDocumentController $controller)
    {
        $reflection = new ReflectionClass($controller);
        $property = $reflection->getProperty('moduleTemplate');
        $property->setAccessible(true);

        /** @var ModuleTemplate $moduleTemplate */
        $moduleTemplate = $property->getValue($controller);

        return $moduleTemplate;
    }
}
