<?php

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../Exceptions.php';

/**
 * Test class for BirianiRequiredExtensionNotFoundException.
 * Generated by PHPUnit on 2012-02-04 at 04:33:50.
 */
class BirianiRequiredExtensionNotFoundExceptionTest extends PHPUnit_Framework_TestCase {

    /**
     * @var BirianiRequiredExtensionNotFoundException
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new BirianiRequiredExtensionNotFoundException;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @todo Implement test__toString().
     */
    public function test__toString() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>