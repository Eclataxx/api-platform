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

    /**
     * @BeforeScenario
     */
    public static function beforeScenario(BeforeScenarioScope $scope)
    {
        (new ConsoleOutput())->writeln('<fg=magenta>Begin transaction</>');
        $container = static::$container ?? static::$kernel->getContainer();
        $container->get('doctrine')->getConnection(static::$connection)->beginTransaction();
    }

    /**
     * @AfterScenario
     */
    public static function afterScenario(AfterScenarioScope $scope)
    {
        (new ConsoleOutput())->writeln('<fg=magenta>Rollback transaction</>');
        $container = static::$container ?? static::$kernel->getContainer();
        $connection = $container->get('doctrine')->getConnection(static::$connection);
        if ($connection->isTransactionActive()) {
            $connection->rollback();
        }
    }
}
