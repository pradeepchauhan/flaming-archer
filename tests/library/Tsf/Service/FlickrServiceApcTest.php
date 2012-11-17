<?php

namespace Tsf\Service;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-11-14 at 22:19:42.
 */
class FlickrServiceApcTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FlickrServiceApc
     */
    protected $serviceApc;
    
    /**
     * @var FlickrService
     */
    protected $service;
    
    /**
     * @var Tsf\Cache\Adapter\Apc
     */
    protected $cache;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->service = $this->getMock('Tsf\Service\FlickrService', array('getSizes'), array(), '', false);
        $this->cache = $this->getMock('Tsf\Cache\Adapter\Apc', array('store', 'fetch'));
        $this->serviceApc = new FlickrServiceApc($this->service, $this->cache);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->serviceApc = null;
    }
    
    public function testObjectCreation()
    {
        $this->assertInstanceOf('Tsf\Service\FlickrServiceApc', $this->serviceApc);
        $this->assertInstanceOf('Tsf\Service\FlickrInterface', $this->serviceApc);
        $this->assertObjectHasAttribute('apc', $this->serviceApc);
        $this->assertObjectHasAttribute('flickr', $this->serviceApc);

    }

    /**
     * @covers Tsf\Service\FlickrServiceApc::getSizes
     */
    public function testGetSizesCacheMiss()
    {
        $this->cache->expects($this->once())
                ->method('fetch')
                ->with(1234)
                ->will($this->returnValue(false));
        
        $this->service->expects($this->once())
                ->method('getSizes')
                ->with(1234)
                ->will($this->returnValue(array('Image information')));
        
        $this->cache->expects($this->once())
                ->method('store')
                ->with(1234, array('Image information'))
                ->will($this->returnValue(true));
        
        $result = $this->serviceApc->getSizes(1234);
        
        $this->assertEquals(array('Image information'), $result);
    }
    
    /**
     * @covers Tsf\Service\FlickrServiceApc::getSizes
     */
    public function testGetSizesCacheHit()
    {
        $this->cache->expects($this->once())
                ->method('fetch')
                ->with(1234)
                ->will($this->returnValue(array('Image information')));
        
        $this->service->expects($this->never())->method('getSizes');
        
        $result = $this->serviceApc->getSizes(1234);
        
        $this->assertEquals(array('Image information'), $result);
    }

}