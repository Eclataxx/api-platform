<?php

namespace App\Tests\Behat;

use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Symfony\Component\Console\Output\ConsoleOutput;

trait HookContextTrait
{
    /**
     * @BeforeSuite
     */
    public static function beforeSuite(BeforeSuiteScope $scope)
    {
        (new ConsoleOutput())->writeln('<fg=magenta>Populate Database</>');
        static::ensureKernelTestCase();
        $kernel = parent::bootKernel();
        static::populateDatabase();
    }
}
