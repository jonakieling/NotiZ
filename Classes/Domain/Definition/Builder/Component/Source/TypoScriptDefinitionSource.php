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

namespace CuyZ\Notiz\Domain\Definition\Builder\Component\Source;

use CuyZ\Notiz\Core\Definition\Builder\Component\Source\DefinitionSource;
use CuyZ\Notiz\Core\Exception\FileNotFoundException;
use CuyZ\Notiz\Core\Support\NotizConstants;
use Romm\ConfigurationObject\ConfigurationObjectInstance;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 * This definition source component is used to fetch a definition array from
 * TypoScript files.
 *
 * You can register your own TypoScript definition file in a component
 * registration service (see example below).
 *
 * To know how to register a new component:
 *
 * @see \CuyZ\Notiz\Core\Definition\Builder\DefinitionBuilder
 *
 * ```
 * class MyCustomComponents
 * {
 *     public function register(\CuyZ\Notiz\Core\Definition\Builder\Component\DefinitionComponents $components)
 *     {
 *         if ($components->hasSource(\CuyZ\Notiz\Core\Definition\Builder\Component\Source\DefinitionSource::SOURCE_TYPOSCRIPT)) {
 *             $components->getSource(\CuyZ\Notiz\Core\Definition\Builder\Component\Source\DefinitionSource::SOURCE_TYPOSCRIPT)
 *                 ->addTypoScriptFilePath("EXT:my_extension/Definition/TypoScript/NotiZ/setup.typoscript")
 *         }
 *     }
 * }
 * ```
 */
class TypoScriptDefinitionSource implements DefinitionSource, SingletonInterface
{
    /**
     * @var array
     */
    protected $filePaths = [];

    /**
     * @var ConfigurationObjectInstance
     */
    protected $objectDefinition;

    /**
     * @var TypoScriptService|\TYPO3\CMS\Core\TypoScript\TypoScriptService
     */
    protected $typoScriptService;

    /**
     * @param TypoScriptService $typoScriptService
     */
    public function __construct(TypoScriptService $typoScriptService)
    {
        $this->typoScriptService = $typoScriptService;
    }

    /**
     * Registers a TypoScript file that should contain definition for the API.
     *
     * See class description for more information.
     *
     * @param string $path
     * @return $this
     *
     * @throws FileNotFoundException
     */
    public function addTypoScriptFilePath($path)
    {
        if (isset($this->filePaths[$path])) {
            return $this;
        }

        $absolutePath = GeneralUtility::getFileAbsFileName($path);

        if (false === file_exists($absolutePath)) {
            throw FileNotFoundException::definitionSourceTypoScriptFileNotFound($path);
        }

        $this->filePaths[$path] = $absolutePath;

        return $this;
    }

    /**
     * Loops on each registered TypoScript definition file, transforms every
     * content in a plain definition array.
     *
     * The arrays are merged to have a single array tree which is then returned.
     *
     * @return array
     */
    public function getDefinitionArray()
    {
        $content = '';

        foreach ($this->filePaths as $path) {
            $content .= GeneralUtility::getUrl($path) . LF;
        }

        /** @var TypoScriptParser $typoScriptParser */
        $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $content = TypoScriptParser::checkIncludeLines($content);
        $typoScriptParser->parse($content);

        $definition = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptParser->setup);

        return isset($definition[NotizConstants::DEFINITION_ROOT_PATH])
            ? $definition[NotizConstants::DEFINITION_ROOT_PATH]
            : [];
    }
}
