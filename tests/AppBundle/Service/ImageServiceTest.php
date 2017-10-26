<?php

namespace Tests\AppBundle\Service;

class ImageServiceTest extends \PHPUnit_Framework_TestCase
{

    private $_oInstance;

    public function setUp()
    {
        parent::setUp();

        $this->_oInstance = $this->getMockBuilder('AppBundle\Service\ImageService')
            ->setMethods()
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetImageNature()
    {
        $this->setUp();

        $aFixture = $this->_oInstance->getImageNature(4);

        $this->assertArrayHasKey(0,$aFixture);

        $this->assertArrayHasKey(1,$aFixture);

        $this->assertArrayHasKey(2,$aFixture);

        $this->assertArrayHasKey(3,$aFixture);

        $this->assertArrayNotHasKey(4,$aFixture);

        parent::tearDown();
    }


    public function testMatriceFormat()
    {
        $this->setUp();

        $aFixture = $this->_oInstance->getImageNature(4);

        $this->assertSameSize($aFixture,[
            0 => 'image 1',
            1 => 'image 2',
            2=> 'image 3',
            3=> 'image 4'
        ]);


        parent::tearDown();
    }
}
