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

use CuyZ\Notiz\Backend\Module\Uri\UriBuilder;
use CuyZ\Notiz\Core\Definition\DefinitionService;
use CuyZ\Notiz\Service\Container;
use CuyZ\Notiz\Service\Traits\ExtendedSelfInstantiateTrait;
use TYPO3\CMS\Core\SingletonInterface;

abstract class ModuleManager implements SingletonInterface
{
    use ExtendedSelfInstantiateTrait;

    /**
     * @var DefinitionService
     */
    protected $definitionService;

    /**
     * @var UriBuilder
     */
    protected $uriBuilder;

    /**
     * @param DefinitionService $definitionService
     * @param UriBuilder $uriBuilder
     */
    public function __construct(DefinitionService $definitionService, UriBuilder $uriBuilder)
    {
        $this->definitionService = $definitionService;
        $this->uriBuilder = $uriBuilder;
    }

    /**
     * Returns the manager class for the given module.
     *
     * @param string $module
     * @return ModuleManager
     */
    public static function for($module)
    {
        /** @var ModuleManager $className */
        $className = __NAMESPACE__ . '\\' . $module . 'ModuleManager';

        return $className::get();
    }

    /**
     * @return UriBuilder
     */
    public function getUriBuilder()
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = Container::get(UriBuilder::class);

        return $uriBuilder->with($this);
    }

    /**
     * @return string
     */
    abstract public static function getDefaultControllerName();

    /**
     * @return string
     */
    abstract public static function getModuleName();
}
