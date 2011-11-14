<?php

namespace Peytz\Wizard;

/**
 * @package PeytzWizard
 */
interface StepInterface
{
    /**
     * @return Symfony\Component\Form\FormTypeInterface
     */
    function getFormType();

    /**
     * @return string
     */
    function getName();

    /**
     * @param ReportInterface $report
     */
    function process(ReportInterface $report);

    /**
     * @param ReportInterface $report
     * @return Boolean
     */
    function isVisible(ReportInterface $report);

    /**
     * @return string
     */
    function getTranslationKey();
}
