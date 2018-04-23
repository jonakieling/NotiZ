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

namespace CuyZ\Notiz\Service\Extension;

use CuyZ\Notiz\Core\Definition\Builder\DefinitionBuilder;
use CuyZ\Notiz\Core\Definition\Tree\Definition;
use CuyZ\Notiz\Core\Support\NotizConstants;
use CuyZ\Notiz\Service\Traits\SelfInstantiateTrait;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * This class replaces the old-school procedural way of handling configuration
 * in `ext_tables.php` file.
 *
 * @internal
 */
class TablesConfigurationService implements SingletonInterface
{
    use SelfInstantiateTrait;

    /**
     * @var string
     */
    protected $extensionKey;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * Manual dependency injection.
     */
    public function __construct()
    {
        $this->extensionKey = NotizConstants::EXTENSION_KEY;
        $this->dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
    }

    /**
     * Main processing methods that will call every method of this class.
     */
    public function process()
    {
        $this->registerBackendModule();
        $this->registerShowNotificationControllers();
    }

    /**
     * Registers the main backend module used to display notifications,
     * definition and more.
     */
    protected function registerBackendModule()
    {
        ExtensionUtility::registerModule(
            'CuyZ.Notiz',
            'notiz',
            '',
            '',
            [],
            [
                'access' => 'user,group',
                'icon' => '',
                'iconIdentifier' => 'tx-notiz-icon-main-module',
                'labels' => "LLL:EXT:{$this->extensionKey}/Resources/Private/Language/Backend/Module/Main/Module.xlf",
            ]
        );

        ExtensionUtility::registerModule(
            'CuyZ.Notiz',
            'notiz',
            'notiz_index',
            '',
            [
                'Backend\Index' => 'listNotificationTypes, listNotifications, listEvents, showEvent'
            ],
            [
                'access' => 'user,group',
                'icon' => NotizConstants::EXTENSION_ICON_MODULE_PATH,
                'labels' => "LLL:EXT:{$this->extensionKey}/Resources/Private/Language/Backend/Module/Index/Module.xlf",
            ]
        );

        ExtensionUtility::registerModule(
            'CuyZ.Notiz',
            'notiz',
            'notiz_administration',
            '',
            [
                'Backend\Administration' => 'index, showDefinition, showException'
            ],
            [
                'access' => 'user,group',
                'icon' => NotizConstants::EXTENSION_ICON_MODULE_PATH,
                'labels' => "LLL:EXT:{$this->extensionKey}/Resources/Private/Language/Backend/Module/Administration/Module.xlf",
            ]
        );
    }

    /**
     * @todo
     */
    protected function registerShowNotificationControllers()
    {
        $this->dispatcher->connect(
            DefinitionBuilder::class,
            DefinitionBuilder::DEFINITION_BUILT_SIGNAL,
            function (Definition $definition) {
                foreach ($definition->getNotifications() as $notification) {
                    $controllerName = 'Backend\\Notification\\Show' . ucfirst($notification->getIdentifier());

                    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Notiz']['modules'][NotizConstants::BACKEND_MODULE_INDEX]['controllers'][$controllerName] = ['actions' => ['show', 'preview']];
                }
            }
        );
    }
}
