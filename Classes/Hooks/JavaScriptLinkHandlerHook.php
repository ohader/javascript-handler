<?php
namespace FoT3\JavascriptHandler\Hooks;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package FoT3\JavascriptHandler\Hooks
 */
class JavaScriptLinkHandlerHook implements SingletonInterface
{

    /**
     * Handles links passed to ContentObjectRenderer::typoLink() starting with
     * the "javascript:" URI scheme. This link handler passes by the insecure
     * setting which would be filtered out by the TYPO3 Core per default.
     *
     * Using this method is not recommended and not considered to be secure.
     * However, it serves for backward compatibility reasons.
     *
     * @param string $linkText
     * @param array $configuration
     * @param string $linkHandlerKeyword
     * @param string $linkHandlerValue
     * @param string $mixedLinkParameter
     * @param ContentObjectRenderer $cObj
     * @return string
     */
    public function main($linkText, $configuration, $linkHandlerKeyword, $linkHandlerValue, $mixedLinkParameter, ContentObjectRenderer $cObj)
    {

        if (isset($configuration['parameter.'])) {
            unset($configuration['parameter.']);
        }

        if (!$this->matches($mixedLinkParameter, $this->getAllowedExpressions())) {
            return $linkText;
        }

        $temporaryLinkHandler = uniqid();
        $configuration['parameter'] = str_replace($linkHandlerKeyword . ':', $temporaryLinkHandler . ':', $mixedLinkParameter);
        $linkTag = $cObj->typoLink($linkText, $configuration);

        $linkTag = str_replace($temporaryLinkHandler . ':', $linkHandlerKeyword . ':', $linkTag);
        return $linkTag;
    }

    /**
     * @param string $expression
     * @param array $allowedExpressions
     * @return bool
     */
    protected function matches($expression, array $allowedExpressions)
    {
        foreach ($allowedExpressions as $allowedExpression) {
            if (!empty($allowedExpression['value'])
                && $allowedExpression['value'] === $expression) {
                return true;
            }
            if (!empty($allowedExpression['pattern'])
                && preg_match($allowedExpression['pattern'], $expression)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    protected function getAllowedExpressions()
    {
        $templateService = $this->getTemplateService();
        if (empty($templateService->setup['config.']['tx_javascripthandler.']['allowedExpressions.'])) {
            return array();
        }
        return $templateService->setup['config.']['tx_javascripthandler.']['allowedExpressions.'];
    }

    /**
     * @return TemplateService
     */
    protected function getTemplateService()
    {
        return $this->getFrontendController()->tmpl;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

}