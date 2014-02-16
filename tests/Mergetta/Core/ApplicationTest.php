<?php namespace Mergetta\Core;

use PHPUnit_Framework_TestCase;

class TestApp extends Application {

    public function main()
    {
        return $this->hasOption('success')
            ? self::SUCCESS
            : self::FAILURE;
    }

}

class ApplicationTest extends PHPUnit_Framework_TestCase {

    private $app;

    public function setUp()
    {
        $this->app = new TestApp(array(
            "php script.php",
            "-s",
            "--optionA",
            "--optionB",
            "--argA=1",
            "--argB=\"a\"",
            "--argC=true",
            "--argD=false"
        ));
    }

    public function tearDown()
    {
        $this->app = null;
    }

    public function testHasOption()
    {
        $this->assertTrue($this->app->hasOption('s'));
        $this->assertTrue($this->app->hasOption('optionA'));
        $this->assertTrue($this->app->hasOption('optionB'));
        $this->assertFalse($this->app->hasOption('optionC'));
    }

    public function testHasArgument()
    {
        $this->assertTrue($this->app->hasArgument('argB'));
        $this->assertTrue($this->app->hasArgument('argD'));
        $this->assertFalse($this->app->hasArgument('argG'));
    }

    public function testGetArgument()
    {
        $this->assertEquals(1, $this->app->getArgument('argA'));
        $this->assertEquals("a", $this->app->getArgument('argB'));
        $this->assertTrue($this->app->getArgument('argC'));
        $this->assertFalse($this->app->getArgument('argD'));
    }

    public function testTypeOfGetArgument()
    {
        $this->assertInternalType('integer', $this->app->getArgument('argA'));
        $this->assertInternalType('string', $this->app->getArgument('argB'));
        $this->assertInternalType('bool', $this->app->getArgument('argC'));
        $this->assertInternalType('bool', $this->app->getArgument('argD'));
    }

    public function testResultStatus()
    {
        $app = new TestApp(array("php script.php", "--success"));
        $this->assertEquals(Application::SUCCESS, $app->main());

        $app = new TestApp(array("php script.php"));
        $this->assertEquals(Application::FAILURE, $app->main());
    }
}