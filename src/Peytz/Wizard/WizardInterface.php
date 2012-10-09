<?php

namespace Peytz\Wizard;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface WizardInterface
{
    /**
     * @return ReportInterface
     */
    public function getReport();

    /**
     * @param ReportInterface $report
     */
    public function setReport(ReportInterface $report);

    /**
     * @return StepInterface[]
     */
    public function all();

    /**
     * @return StepInterface
     */
    public function first();

    /**
     * @param StepInterface $step
     */
    public function set(StepInterface $step);

    /**
     * @param  string        $identifier
     * @return StepInterface
     */
    public function get($identifier);

    /**
     * @param  string  $identifier
     * @return boolean
     */
    public function has($identifier);

    /**
     * @return StepInterface
     */
    public function last();

    /**
     * @param string $identifier
     */
    public function remove($identifier);

    /**
     * Run through all steps after and including `$step` and call `StepInterface::process`.
     * This makes it possible to cleanup values that have been defined when jumping back
     * and forth between steps.
     *
     * @param StepInterface $step
     */
    public function process(StepInterface $step);
}
