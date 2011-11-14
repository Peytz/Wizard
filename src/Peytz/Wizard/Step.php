<?php

namespace Peytz\Wizard;

/**
 * Abstract Implementation of StepInterface
 *
 * @package PeytzWizard
 */
abstract class Step implements StepInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return substr(strtolower(current(array_reverse(explode('\\', get_called_class())))), 0, -4);
    }

    /**
     * Processing. If a previous step have altered a report and this step depends on it
     * do the necesarry invalidation here.
     */
    public function process(ReportInterface $report)
    {
    }

    /**
     * @return Boolean
     */
    public function isVisible(ReportInterface $report)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTranslationKey()
    {
        return $this->getName();
    }
}
