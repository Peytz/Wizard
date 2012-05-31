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
    public function getFormType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param ReportInterface $report
     */
    public function process(ReportInterface $report);

    /**
     * @param  ReportInterface $report
     * @return Boolean
     */
    public function isVisible(ReportInterface $report);
}
