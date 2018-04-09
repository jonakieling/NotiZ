<?php

namespace CuyZ\Notiz\ViewHelpers\Format;

use TYPO3\CMS\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class Nl2brTrimViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @inheritdoc
     */
    public function render()
    {
        return \nl2br(\trim($this->renderChildren()));
    }

    /**
     * @inheritdoc
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        return sprintf(
            '\nl2br(\trim(%s()))',
            $closureName
        );
    }
}
