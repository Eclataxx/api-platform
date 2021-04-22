<?php

namespace App\Tests\Behat\Processor;

use App\Tests\Behat\DataList;
use Fidry\AliceDataFixtures\ProcessorInterface;

class FixturesProcessor implements ProcessorInterface
{
    /** @var DataList|null */
    public ?DataList $dataList;

    public function __construct()
    {
        $this->dataList = DataList::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function preProcess(string $id, $object): void
    {
        $this->dataList->data = [];
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function postProcess(string $id, $object): void
    {
        $this->dataList->data[$id] = ["id" => $object->getId()];
        // var_dump((method_exists($object, 'getId')) ?$object->getId() : null);
        // var_dump((method_exists($object, 'getEmail')) ?$object->getEmail() : null);
    }
}
