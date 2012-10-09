<?php

namespace Peytz\Wizard;

/**
 * @package PeytzWizard
 */
class Wizard implements WizardInterface, \IteratorAggregate, \Countable
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
    public function __construct(ReportInterface $report = null)
    {
        if ($report) {
            $this->setReport($report);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setReport(ReportInterface $report)
    {
        $this->report = $report;
    }

    /**
     * {@inheritDoc}
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->steps;
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return current($this->all());
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return current(array_reverse($this->all()));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($identifier)
    {
        unset($this->steps[$identifier]);
    }

    /**
     * {@inheritDoc}
     */
    public function set(StepInterface $step)
    {
        $this->steps[$step->getName()] = $step;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        return $this->has($identifier) ? $this->steps[$identifier] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function has($identifier)
    {
        return isset($this->steps[$identifier]);
    }

    /**
     * {@inheritDoc}
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
    public function getLastVisibleStep()
    {
        $report = $this->getReport();

        $steps = array_filter($this->all(), function (StepInterface $step) use ($report) {
            return $step->isVisible($report);
        });

        return end($steps);
    }

    /**
     * @param  StepInterface $step
     * @return StepInterface
     */
    public function getNextStepByStep(StepInterface $step)
    {
        $steps = array_keys($this->steps);
        $position = array_search($step->getName(), $steps) + 1;

        return isset($steps[$position]) ? $this->get($steps[$position]) : null;
    }

    /**
     * @param  StepInterface $step
     * @return StepInterface
     */
    public function getPreviousStepByStep(StepInterface $step)
    {
        $steps = array_keys($this->steps);
        $position = array_search($step->getName(), $steps) - 1;

        return isset($steps[$position]) ? $this->get($steps[$position]) : null;
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
