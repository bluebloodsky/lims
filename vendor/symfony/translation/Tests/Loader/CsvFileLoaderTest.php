<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests\Loader;

use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Config\Resource\FileResource;

class CsvFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $loader = new CsvFileLoader();
        $respource = __DIR__.'/../fixtures/resources.csv';
        $catalogue = $loader->load($respource, 'en', 'domain1');

        $this->assertEquals(array('foo' => 'bar'), $catalogue->all('domain1'));
        $this->assertEquals('en', $catalogue->getLocale());
        $this->assertEquals(array(new FileResource($respource)), $catalogue->getResources());
    }

    public function testLoadDoesNothingIfEmpty()
    {
        $loader = new CsvFileLoader();
        $respource = __DIR__.'/../fixtures/empty.csv';
        $catalogue = $loader->load($respource, 'en', 'domain1');

        $this->assertEquals(array(), $catalogue->all('domain1'));
        $this->assertEquals('en', $catalogue->getLocale());
        $this->assertEquals(array(new FileResource($respource)), $catalogue->getResources());
    }

    /**
     * @expectedException \Symfony\Component\Translation\Exception\NotFoundResourceException
     */
    public function testLoadNonExistingResource()
    {
        $loader = new CsvFileLoader();
        $respource = __DIR__.'/../fixtures/not-exists.csv';
        $loader->load($respource, 'en', 'domain1');
    }

    /**
     * @expectedException \Symfony\Component\Translation\Exception\InvalidResourceException
     */
    public function testLoadNonLocalResource()
    {
        $loader = new CsvFileLoader();
        $respource = 'http://example.com/resources.csv';
        $loader->load($respource, 'en', 'domain1');
    }
}
