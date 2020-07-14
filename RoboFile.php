<?php

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types = 1);

use Robo\Collection\CollectionBuilder;
use Robo\Tasks;

class RoboFile extends Tasks
{
    public function qaAll(): void
    {
        $this->checkAll();
        $this->testAll();
    }

    public function checkAll(): void
    {
        $this->checkCs();
        $this->checkPhpstan();
    }

    public function checkCs($opts = ['dry-run' => false])
    {
        $task = $this->taskExec('vendor/bin/php-cs-fixer fix');
        $task->arg('--diff');

        if ($opts['dry-run']) {
            $task->arg('--dry-run');
        }

        return $this->runAndReturnExitCode($task);
    }

    public function checkPhpstan($opts = ['no-progress' => false])
    {
        $task = $this->taskExec('vendor/bin/phpstan analyse src --level=max');

        if ($opts['no-progress']) {
            $task->arg('--no-progress');
        }

        return $this->runAndReturnExitCode($task);
    }

    public function secSensio(): void
    {
        $this->_exec('bin/console security:check');
    }

    public function secAll(): void
    {
        $this->secSensio();
    }

    public function dbDrop(): void
    {
        $this->_exec('bin/console doctrine:database:drop --force');
    }

    public function dbCreate(): void
    {
        $this->_exec('bin/console doctrine:database:create');
    }

    public function dbMigrate(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate --no-interaction');
    }

    public function dbMigrateNext(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate next');
    }

    public function dbMigratePrev(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate prev');
    }

    public function dbFixtures(): void
    {
        $this->_exec('bin/console doctrine:fixtures:load --no-interaction');
    }

    public function dbUp(): void
    {
        $this->dbMigrate();
        $this->dbFixtures();
    }

    public function dbReset(): void
    {
        $this->dbDrop();
        $this->dbCreate();

        $this->dbMigrate();
        $this->dbFixtures();
    }

    public function testUnit(): void
    {
        $this->_exec('bin/phpunit --testsuite Unit');
    }

    public function testFunctional(): void
    {
        $this->_exec('bin/phpunit --testsuite Functional');
    }

    public function testIntegration(): void
    {
        $this->_exec('bin/phpunit --testsuite Integration');
    }

    public function testAll(): void
    {
        // clear cache before tests
        $this->cacheClean('test');

        // run all kinds of testing
        $this->testUnit();
        $this->testFunctional();
        $this->testIntegration();
    }

    public function cacheClean(string $env = 'test'): void
    {
        $this->_exec("bin/console cache:clear --no-warmup --env=${env}");
    }

    private function runAndReturnExitCode(CollectionBuilder $task)
    {
        $result = $task->run();

        return $result->getExitCode();
    }
}
