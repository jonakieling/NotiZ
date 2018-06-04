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
use CuyZ\Notiz\Service\Container;
use CuyZ\Notiz\Service\Traits\ExtendedSelfInstantiateTrait;
use TYPO3\CMS\Core\SingletonInterface;

abstract class ModuleManager implements SingletonInterface
{
    use ExtendedSelfInstantiateTrait;

    /**
     * @var UriBuilder
     */
    protected $uriBuilder;

    /**
     * @param UriBuilder $uriBuilder
     */
    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
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
