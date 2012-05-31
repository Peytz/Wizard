<?php

namespace Peytz\Test\Wizard;

use Peytz\Wizard\Wizard;

class WizardTest extends \PHPUnit_Framework_TestCase
{
    protected $wizard;

    public function setUp()
    {
        $this->wizard = new Wizard($this->getReportMock());
    }

    public function testFluentInterface()
    {
        $this->assertInstanceOf('Peytz\Wizard\Wizard', $this->wizard->set($this->getStepMock('step1')));
    }

    public function testContainerFunctionality()
    {
        $step1 = $this->getStepMock('step1');

        $this->assertFalse($this->wizard->has('step1'));
        $this->assertSame(null, $this->wizard->get('step1'));

        $this->wizard->set($step1);

        $this->assertTrue($this->wizard->has('step1'));
        $this->assertSame($step1, $this->wizard->get('step1'));

        $step2 = $this->getStepMock('step1');
        $this->wizard->set($step2);
        $this->assertSame($step2, $this->wizard->get('step1'));

        $this->assertEquals(1, $this->wizard->count());

        $this->wizard->remove('step1');
        $this->assertEquals(0, $this->wizard->count());

        $step3 = $this->getStepMock('step3');

        $this->wizard->set($step1);
        $this->wizard->set($step3);

        $this->assertEquals(array(
            $step1->getName() => $step1,
            $step3->getName() => $step3,
        ), $this->wizard->all());
    }

    public function testProcess()
    {
        $step1 = $this->getStepMock('step1');
        $step2 = $this->getStepMock('step2');
        $step3 = $this->getStepMock('step3');

        $step1
            ->expects($this->once())
            ->method('process')
        ;

        $step2
            ->expects($this->once())
            ->method('process')
        ;

        $step3
            ->expects($this->once())
            ->method('process')
        ;

        $this->wizard->set($step1);
        $this->wizard->set($step2);
        $this->wizard->set($step3);

        $this->wizard->process($step1);
    }

    public function testGetLastVisibleStep()
    {
        $visible = $this->getStepMock('visible');
        $visible
            ->expects($this->once())
            ->method('isVisible')
            ->will($this->returnValue(true))
        ;

        $invisible = $this->getStepMock('invisible');
        $invisible
            ->expects($this->once())
            ->method('isVisible')
            ->will($this->returnValue(false))
        ;

        $this->wizard->set($visible);
        $this->wizard->set($invisible);

        $this->assertEquals($visible, $this->wizard->getLastVisibleStep());

    }

    public function testGetFirstStep()
    {
        $step1 = $this->getStepMock('step1');
        $step2 = $this->getStepMock('step2');

        $this->wizard->set($step1);
        $this->wizard->set($step2);

        $this->assertEquals($step1, $this->wizard->getFirstStep());
    }

    public function testGetPreviousStepByStep()
    {
        $step1 = $this->getStepMock('step1');
        $step2 = $this->getStepMock('step2');
        $step3 = $this->getStepMock('step3');

        $this->wizard->set($step1);
        $this->wizard->set($step2);
        $this->wizard->set($step3);

        $this->assertEquals($step1, $this->wizard->getPreviousStepByStep($step2));
        $this->assertInternalType('null', $this->wizard->getPreviousStepByStep($step1));
        $this->assertEquals($step2, $this->wizard->getPreviousStepByStep($step3));
    }

    public function testGetNextStepByStep()
    {
        $step1 = $this->getStepMock('step1');
        $step2 = $this->getStepMock('step2');
        $step3 = $this->getStepMock('step3');

        $this->wizard->set($step1);
        $this->wizard->set($step2);
        $this->wizard->set($step3);

        $this->assertEquals($step2, $this->wizard->getNextStepByStep($step1));
        $this->assertInternalType('null', $this->wizard->getNextStepByStep($step3));
        $this->assertEquals($step3, $this->wizard->getNextStepByStep($step2));
    }

    public function testTemplateByStep()
    {
        $step = $this->getStepMock('Step1');
        $wizard = $this->getMock('Peytz\Wizard\Wizard', array('getTemplatePrefix'), array(), '', false);
        $wizard
            ->expects($this->any())
            ->method('getTemplatePrefix')
            ->will($this->returnValue('MyPrefix'))
        ;

        $this->assertEquals('MyPrefix/step:Step1.html.twig', $wizard->getTemplateByStep($step));
    }

    public function testIteratorAggregate()
    {
        $steps = array();
        $this->wizard->set($steps['step1'] = $this->getStepMock('step1'));
        $this->wizard->set($steps['step2'] = $this->getStepMock('step2'));
        $this->wizard->set($steps['step3'] = $this->getStepMock('step3'));

        $this->assertInstanceOf('ArrayIterator', $this->wizard->getIterator());

        foreach ($this->wizard as $index => $step) {
            $this->assertEquals($step, $steps[$index]);
        }

    }

    public function testCountable()
    {
        $this->wizard->set($this->getStepMock('step1'));
        $this->wizard->set($this->getStepMock('step2'));
        $this->wizard->set($this->getStepMock('step3'));

        $this->assertEquals(3, count($this->wizard));
    }

    protected function getStepMock($name)
    {
        $mock = $this->getMock('Peytz\Wizard\StepInterface');
        $mock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name))
        ;

        return $mock;
    }

    protected function getReportMock()
    {
        return $this->getMock('Peytz\Wizard\ReportInterface');
    }
}
