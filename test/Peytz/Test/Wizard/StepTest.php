<?php

namespace Peytz\Wizard\Test;

use Acme\Wizard\NoobStep;

class StepTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $noob = new NoobStep();
        $this->assertEquals('noob', $noob->getName());
    }

    public function testProcess()
    {
        $report = $this->createReportInterfaceMock();
        $noob = new NoobStep();

        $this->assertInternalType('null', $noob->process($report));
    }

    public function testGetTranslationKey()
    {
        $noob = new NoobStep();
        $this->assertEquals('noob', $noob->getTranslationKey());
    }

    public function testIsVisible()
    {
        $step = new NoobStep();
        $report = $this->createReportInterfaceMock();

        $this->assertTrue($step->isVisible($report));
    }

    protected function createReportInterfaceMock()
    {
        return $this->getMock('Peytz\Wizard\ReportInterface');
    }
}
