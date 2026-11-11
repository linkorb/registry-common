<?php

require_once __DIR__ . '/../../src/Common/DottedPropertyTrait.php';

use Registry\Common\DottedPropertyTrait;

/**
 * Pins DottedPropertyTrait::unflatten as it behaves today, including the
 * scalar collision that wraps the previous value at index 0.
 */
class DottedPropertyTraitUnflattenTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyListReturnsEmptyArray()
    {
        $this->assertSame(array(), $this->unflatten(array()));
    }

    public function testKeyWithoutDotStaysAtTopLevel()
    {
        $this->assertSame(
            array('foo' => 'bar'),
            $this->unflatten(array('foo' => 'bar'))
        );
    }

    public function testSharedPrefixDescendsIntoExistingArray()
    {
        $this->assertSame(
            array(
                'foo' => array(
                    'bar' => 'a',
                    'baz' => 'b',
                ),
            ),
            $this->unflatten(array(
                'foo.bar' => 'a',
                'foo.baz' => 'b',
            ))
        );
    }

    public function testDottedKeyUnderScalarWrapsPreviousValueAtIndexZero()
    {
        $this->assertSame(
            array(
                'foo' => array(
                    0 => 'scalar',
                    'bar' => 'nested',
                ),
            ),
            $this->unflatten(array(
                'foo' => 'scalar',
                'foo.bar' => 'nested',
            ))
        );
    }

    private function unflatten(array $properties)
    {
        $subject = $this->getMockForTrait(DottedPropertyTrait::class);

        return $subject->unflatten($properties);
    }
}
