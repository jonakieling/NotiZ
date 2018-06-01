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

namespace CuyZ\Notiz\Backend\Module;

use CuyZ\Notiz\Core\Definition\Tree\Definition;
use CuyZ\Notiz\Core\Definition\Tree\Notification\NotificationDefinition;

class IndexModuleManager extends ModuleManager
{
    /**
     * @return string
     */
    public static function getDefaultControllerName()
    {
        return 'Backend\\Index';
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'NotizNotiz_NotizNotizIndex';
    }

    /**
     * @param NotificationDefinition $notificationDefinition
     * @return string
     */
    public function controllerToShowNotification(NotificationDefinition $notificationDefinition)
    {
        return 'Backend\\Notification\\Show' . ucfirst($notificationDefinition->getIdentifier());
    }

    /**
     * @param Definition $definition
     */
    public function registerShowNotificationControllers(Definition $definition)
    {
        foreach ($definition->getNotifications() as $notification) {
            $controllerName = $this->controllerToShowNotification($notification);

            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Notiz']['modules'][IndexModuleManager::getModuleName()]['controllers'][$controllerName] = ['actions' => ['show', 'preview']];
        }
    }
}
