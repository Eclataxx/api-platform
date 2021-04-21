<?php

namespace App\Tests\Behat;

use Behat\Gherkin\Node\TableNode;

trait FixturesContextTrait
{
    /**
     * @Given The fixtures file :file is loaded
     */
    public function loadFileFixtures($file)
    {
        $this->fixturesLoader->load(['./fixtures/' . $file . '.yml']);
    }

    /**
     * @Given The fixtures files
     */
    public function loadFilesFixtures(TableNode $table)
    {
        $files = array_map(fn($row) => './fixtures/' . $row[0] . '.yml', $table->getRows());
        $this->fixturesLoader->load($files);
    }
}
