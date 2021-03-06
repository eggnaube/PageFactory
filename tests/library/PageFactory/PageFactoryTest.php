<?php
namespace Tzander\Tests;

use Tzander\PageFactory;

require_once dirname(__FILE__)."/../fixtures/PageTestClass.php";

class PageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase
     */
    private $testCase;

    public function setUp()
    {
        $url = new \PHPUnit_Extensions_Selenium2TestCase_URL("http://localhost");
        $driver = $this->getMock("PHPUnit_Extensions_Selenium2TestCase_Driver", array(), array($url));
        $this->element = $this->getMock("PHPUnit_Extensions_Selenium2TestCase_Element", array(), array($driver, $url));

        $this->testCase = $this->getMock('PHPUnit_Extensions_Selenium2TestCase', array("byName", "byId", "byClassName"));
    }

    /**
     * @test
     */
    public function shouldInitPageObjectByName()
    {
        $this->testCase->expects($this->any())
            ->method("byName")
            ->will($this->returnValue($this->element));

        $page = PageFactory::initElements($this->testCase, 'PageTestClass');
        $this->assertInstanceOf('PageTestClass', $page);
    }

    /**
     * @test
     */
    public function shouldInitPageObjectById()
    {
        $this->testCase->expects($this->any())
            ->method("byId")
            ->will($this->returnValue($this->element));
        $this->testCase->expects($this->any())
            ->method("byClassName")
            ->will($this->returnValue($this->element));

        $page = PageFactory::initElements($this->testCase, 'PageTestClass');
        $this->assertInstanceOf('PageTestClass', $page);
    }

    /**
     * @test
     */
    public function shouldInitPageObjectByAnnotation()
    {
        $this->testCase->expects($this->any())
            ->method("byClassName")
            ->will($this->returnValue($this->element));
        $page = PageFactory::initElements($this->testCase, 'PageTestClass');
        $this->assertInstanceOf('PageTestClass', $page);
    }


    /**
     * @test
     */
    public function shouldHavePropertyFromDecorator()
    {
        $url = new \PHPUnit_Extensions_Selenium2TestCase_URL("http://localhost");
        $driver = $this->getMock("PHPUnit_Extensions_Selenium2TestCase_Driver", array(), array($url));
        $element = $this->getMock("PHPUnit_Extensions_Selenium2TestCase_Element", array(), array($driver, $url));
        $elementLocator = $this->getMock(
            "Tzander\PageFactory\DefaultElementLocatorFactory",
            array(),
            array($this->testCase)
        );

        $decorator = $this->getMock(
            "Tzander\PageFactory\DefaultFieldDecorator",
            array("decorate"),
            array($elementLocator)
        );
        $decorator->expects($this->any())
            ->method("decorate")
            ->will($this->returnValue($element));

        $page = new \PageTestClass();
        PageFactory::initElementsWithDecorator($decorator, $page);

        $this->assertInstanceOf('PageTestClass', $page);
        $this->assertInstanceOf('PHPUnit_Extensions_Selenium2TestCase_Element', $page->link);
    }

}
