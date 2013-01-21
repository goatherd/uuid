<?php

namespace Goatherd\Uuid;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $uuid = Factory::generate(Factory::UUID_TIME);
        $this->assertTrue($this->isUuid($uuid));
        $uuid = Factory::generate(Factory::UUID_NAME_MD5);
        $this->assertTrue($this->isUuid($uuid));
        $uuid = Factory::generate(Factory::UUID_RANDOM);
        $this->assertTrue($this->isUuid($uuid));
        $uuid = Factory::generate(Factory::UUID_NAME_SHA1);
        $this->assertTrue($this->isUuid($uuid));
    }

    protected function isUuid($uuid) {
        return is_array($uuid) && count($uuid) == 16 && array_sum($uuid) > 0;
    }
}
