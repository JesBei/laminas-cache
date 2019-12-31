<?php

/**
 * @see       https://github.com/laminas/laminas-cache for the canonical source repository
 * @copyright https://github.com/laminas/laminas-cache/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-cache/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Cache\Storage\Adapter;

use Laminas\Cache\Storage\Adapter\AbstractZendServer;
use Laminas\Cache\Storage\Adapter\AdapterOptions;

/**
 * @category   Laminas
 * @package    Laminas_Cache
 * @subpackage UnitTests
 * @group      Laminas_Cache
 */
class AbstractZendServerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->_options = new AdapterOptions();
        $this->_storage = $this->getMockForAbstractClass('Laminas\Cache\Storage\Adapter\AbstractZendServer');
        $this->_storage->setOptions($this->_options);
        $this->_storage->expects($this->any())
                       ->method('getOptions')
                       ->will($this->returnValue($this->_options));
    }

    public function testGetOptions()
    {
        $options = $this->_storage->getOptions();
        $this->assertInstanceOf('Laminas\Cache\Storage\Adapter\AdapterOptions', $options);
        $this->assertInternalType('boolean', $options->getWritable());
        $this->assertInternalType('boolean', $options->getReadable());
        $this->assertInternalType('integer', $options->getTtl());
        $this->assertInternalType('string', $options->getNamespace());
        $this->assertInternalType('string', $options->getKeyPattern());
    }

    public function testGetItem()
    {
        $this->_options->setNamespace('ns');

        $this->_storage->expects($this->once())
                       ->method('zdcFetch')
                       ->with($this->equalTo('ns' . AbstractZendServer::NAMESPACE_SEPARATOR . 'key'))
                       ->will($this->returnValue('value'));

        $this->assertEquals('value', $this->_storage->getItem('key'));
    }

    public function testGetMetadata()
    {
        $this->_options->setNamespace('ns');

        $this->_storage->expects($this->once())
                       ->method('zdcFetch')
                       ->with($this->equalTo('ns' . AbstractZendServer::NAMESPACE_SEPARATOR . 'key'))
                       ->will($this->returnValue('value'));

        $this->assertEquals(array(), $this->_storage->getMetadata('key'));
    }

    public function testHasItem()
    {
        $this->_options->setNamespace('ns');

        $this->_storage->expects($this->once())
                       ->method('zdcFetch')
                       ->with($this->equalTo('ns' . AbstractZendServer::NAMESPACE_SEPARATOR . 'key'))
                       ->will($this->returnValue('value'));

        $this->assertEquals(true, $this->_storage->hasItem('key'));
    }

    public function testSetItem()
    {
        $this->_options->setTtl(10);
        $this->_options->setNamespace('ns');

        $this->_storage->expects($this->once())
                       ->method('zdcStore')
                       ->with(
                           $this->equalTo('ns' . AbstractZendServer::NAMESPACE_SEPARATOR . 'key'),
                           $this->equalTo('value'),
                           $this->equalTo(10)
                       )
                       ->will($this->returnValue(true));

        $this->assertEquals(true, $this->_storage->setItem('key', 'value'));
    }

    public function testRemoveItem()
    {
        $this->_options->setNamespace('ns');

        $this->_storage->expects($this->once())
                       ->method('zdcDelete')
                       ->with($this->equalTo('ns' . AbstractZendServer::NAMESPACE_SEPARATOR . 'key'))
                       ->will($this->returnValue(true));

        $this->assertEquals(true, $this->_storage->removeItem('key'));
    }
}
