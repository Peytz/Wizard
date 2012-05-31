<?php

namespace Peytz\Wizard;

/**
 * @package PeytzWizard
 */
class Wizard implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    protected $steps = array();

    /**
     * @var ReportInterface
     */
    protected $report;

    /**
     * Generates a token to be used for saving
     */
    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    /**
     * @return ReportInterface
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->steps;
    }

    /**
     * Run through all steps including the provided and call
     * `StepInterface::process()` this allows for cleaning up
     * data when one step is dependent on data from another
     *
     * @param StepInterface $step
     */
    public function process(StepInterface $step)
    {
        $step->process($this->report);

        while ($step = $this->getNextStepByStep($step)) {
            $step->process($this->report);
        }
    }

    /**
     * @return StepInterface
     */
    public function getFirstStep()
    {
        return current(array_values($this->all()));
    }

    /**
     * @return StepInterface
     */
    public function getLastVisibleStep()
    {
        $report = $this->getReport();
        $steps = $this->all();

        foreach ($steps as $step) {
            if (false == $step->isVisible($report)) {
                break;
            }

            $lastVisibleStep = $step;
        }

        return $lastVisibleStep;
    }

    /**
     * @param  StepInterface $step
     * @return StepInterface
     */
    public function getNextStepByStep(StepInterface $step)
    {
        $steps = array_keys($this->steps);
        $position = array_search($step->getName(), $steps) + 1;

        if (isset($steps[$position])) {
            return $this->get($steps[$position]);
        }

        return null;
    }

    /**
     * @param  StepInterface $step
     * @return StepInterface
     */
    public function getPreviousStepByStep(StepInterface $step)
    {
        $steps = array_keys($this->steps);
        $position = array_search($step->getName(), $steps) - 1;

        if (isset($steps[$position])) {
            return $this->get($steps[$position]);
        }

        return null;
    }

    /**
     * @param string $identifier
     */
    public function remove($identifier)
    {
        unset($this->steps[$identifier]);
    }

    /**
     * @param StepInterface $step
     */
    public function set(StepInterface $step)
    {
        $this->steps[$step->getName()] = $step;

        return $this;
    }

    /**
     * @param  string        $identifier
     * @return StepInterface
     */
    public function get($identifier)
    {
        return $this->has($identifier) ? $this->steps[$identifier] : null;
    }

    /**
     * @param  string  $identifier
     * @return Boolean
     */
    public function has($identifier)
    {
        return isset($this->steps[$identifier]);
    }

    /**
     * @see IteratorAggregate
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->steps);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->steps);
    }
}
