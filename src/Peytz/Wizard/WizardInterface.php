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
    function getReport();

    /**
     * @return StepInterface[]
     */
    function all();

    /**
     * @return StepInterface
     */
    function first();

    /**
     * @param StepInterface $step
     */
    function set(StepInterface $step);

    /**
     * @param string $identifier
     * @return StepInterface
     */
    function get($identifier);

    /**
     * @param string $identifier
     * @return boolean
     */
    function has($identifier);

    /**
     * @return StepInterface
     */
    function last();

    /**
     * @param string $identifier
     */
    function remove($identifier);

    /**
     * Run through all steps after and including `$step` and call `StepInterface::process`.
     * This makes it possible to cleanup values that have been defined when jumping back
     * and forth between steps.
     *
     * @param StepInterface $step
     */
    function process(StepInterface $step);
}
