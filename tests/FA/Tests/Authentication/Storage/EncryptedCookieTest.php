<?php

namespace FA\Tests\Authentication\Storage;

use FA\Authentication\Storage\EncryptedCookie;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-11-13 at 20:38:48.
 */
class EncryptedCookieTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EncryptedCookie
     */
    protected $cookie;

    /**
     * @var \Slim\Slim
     */
    protected $app;

    /**
     * @var string JSON contents of cookie
     */
    protected $cookieContents = '{"message":"Cookie contents"}';

    protected function setUp()
    {
        $this->app = $this->getMock('\Slim\Slim', array('getCookie', 'setCookie', 'deleteCookie'), array(), '', false);
        $this->cookie = new EncryptedCookie($this->app, 'cookieName');
    }

    protected function tearDown()
    {
        $this->cookie = null;
    }

    public function testConstuction()
    {
        $this->assertInstanceOf('FA\Authentication\Storage\EncryptedCookie', $this->cookie);
        $this->assertInstanceOf('\Zend\Authentication\Storage\StorageInterface', $this->cookie);
    }

    public function testIsEmptyTrue()
    {
        $this->app->expects($this->once())
                ->method('getCookie')
                ->with('cookieName')
                ->will($this->returnValue(false));
        $this->assertTrue($this->cookie->isEmpty());
    }

    public function testIsEmptyFAlse()
    {
        $this->app->expects($this->once())
                ->method('getCookie')
                ->with('cookieName')
                ->will($this->returnValue($this->cookieContents));
        $this->assertFAlse($this->cookie->isEmpty());
    }

    public function testRead()
    {
        $this->app->expects($this->once())
                ->method('getCookie')
                ->with('cookieName')
                ->will($this->returnValue($this->cookieContents));
        $contents = $this->cookie->read();
        $this->assertEquals(json_decode($this->cookieContents, true), $contents);
    }

    public function testWrite()
    {
        $this->app->expects($this->once())
                ->method('setCookie')
                ->with('cookieName', json_encode(array("I'm a cookie")), '1 day');
        $this->cookie->setTime('1 day');
        $this->cookie->write(array("I'm a cookie"));
    }

    public function testClear()
    {
        $this->app->expects($this->once())
                ->method('deleteCookie')
                ->with('cookieName');
        $this->cookie->clear();
    }
}
