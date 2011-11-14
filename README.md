Wizard Component for Symfony2
=============================

[![Build Status](https://secure.travis-ci.org/Peytz/Wizard.png?branch=master)](http://travis-ci.org/Peytz/Wizard)

This is a simple component mainly developed for usage with the Symfony2 framework. And because
of that there is some references to that. Which easily can be changed.

License
-------

See LICENSE for licensing terms.

Contributing
------------

Everybody is welcome to send Pull Requests. But we reserve the right to reject any that is not
in line with our goals for this component.

See https://github.com/Peytz/Wizard/contributors for a list of contributors

Running tests
-------------

``` shell
$ phpunit
```

Usage
-----

The api is very simple. You create a Report which properly should be an Doctrine Entity. The Report
holds your data and must implement `ReportInterface`

A Report is required as a contructor argument for a `Wizard`. A Wizard should be subclassed to add
custom steps or use a DependencyInjection framework to inject them into a Wizard object.

A Wizard holds a n number of Steps that implements the `StepInterface`. There is a basic `Step`
implementation of `StepInterface` availible.

``` php
<?php

namespace Vendor\Wizard;

use Peytz\Wizard\Step;
use Vendor\Wizard\Form\CustomFormType;

class CustomStep extends Step
{
    public function getFormType()
    {
        return CustomFormType();
    }
}
```

``` php
<?php

namespace Vendor\Wizard;

use Peytz\Wizard\Wizard;
use Peytz\Wizard\ReportInterface;
use Vendor\Wizard\CustomStep;

class CustomWizard extends Wizard
{
    public function __construct(ReportInterface $report)
    {
        parent::__construct($report);
        $this->add(CustomStep());
    }
}
```

``` php
<?php

namespace Vendor\Wizard;

use Peytz\Wizard\ReportInterface;

class Report implements ReportInterface
{
}
```

Controller action implementation using Symfony Validator component for validation. Validation Groups
are extremely useful for this.

``` php
<?php

namespace Vendor\Wizard;

class Controller
{
    protected $validator;

    protected $wizard;

    public function myAction($stepIdentifier)
    {
        $step = $this->wizard->get($stepIdentifier);
        $form = $this->createForm($step->getFormType(), $this->wizard->getReport(), array(
            'validation_groups' => array($step->getName()),
        ));

        if ($_POST) {
            $form->bind($_POST);
            if ($form->isValid()) {
                $this->wizard->process($step);

                // You should proberly save some stuff here? And redirect
            }
        }

        return array(
            'form' => $form,
        );
    }
}
```

Sample Symfony2 DIC definition
------------------------------

I you want to use DependencyInjection with Symfony2 this is another way of having a Wizard and its steps associated.

``` xml
<container>
    <services>
        <service id="vendor.wizard.custom" class="Peytz\Wizard\Wizard">
            <argument type="service" id="vendor.wizard.custom.report" />
            <call method="add">
                <argument type="service" id="vendor.wizard.custom.my_step" />
            </call>
        </service>
    </services>
</container>
```

Updating `gh-pages` branch
--------------------------

``` bash
$ ./apigen.sh
$ git push
```

