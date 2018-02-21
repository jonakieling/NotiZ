<?php

/*
 * Copyright (C) 2018
 *
 * Romain Canon <romain.hydrocanon@gmail.com>
 * Nathan Boiron <nathan.boiron@gmail.com>
 *
 * This file is part of the NotiZ project.
 * It is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License, either
 * version 3 of the License, or any later version.
 *
 * For the full copyright and license information, see:
 * http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CuyZ\Notiz\Controller\Backend;

use CuyZ\Notiz\Core\Definition\DefinitionService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

abstract class BackendController extends ActionController
{
    /**
     * @var DefinitionService
     */
    protected $definitionService;

    /**
     * Checking if the definition contains errors.
     */
    public function initializeAction()
    {
        if ($this->definitionService->getValidationResult()->hasErrors()) {
            $this->definitionError();
        }
    }

    /**
     * If the definition contain errors, this method will be called. It should
     * do things like forwar the request or display an error message.
     *
     * @return void
     */
    abstract protected function definitionError();

    /**
     * @param DefinitionService $definitionService
     */
    public function injectDefinitionService(DefinitionService $definitionService)
    {
        $this->definitionService = $definitionService;
    }
}