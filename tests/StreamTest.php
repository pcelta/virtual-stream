<?php

namespace Pcelta\VirtualStream;

use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    protected $customProtocol = 'cuuuuuustom';

    public function setUp()
    {
        $registeredProtocols = stream_get_wrappers();
        if (in_array(Stream::DEFAULT_PROTOCOL, $registeredProtocols)) {
            stream_wrapper_unregister(Stream::DEFAULT_PROTOCOL);
        }

        if (in_array($this->customProtocol, $registeredProtocols)) {
            stream_wrapper_unregister($this->customProtocol);
        }
    }

    public function testRegisterShouldRegisterDefaultProtocolWhenItIsMissed()
    {
        Stream::register();
        $this->assertContains(Stream::DEFAULT_PROTOCOL, stream_get_wrappers());
    }

    public function testRegisterShouldRegisterCustomProtocolWhenItIsGiven()
    {
        Stream::register($this->customProtocol);
        $this->assertContains($this->customProtocol, stream_get_wrappers());
    }

    public function testUnregisterShouldUnregisterStreamWrapperWhenRightProtocolIsGiven()
    {
        Stream::register($this->customProtocol);
        $this->assertContains($this->customProtocol, stream_get_wrappers());

        Stream::unregister($this->customProtocol);
        $this->assertNotContains($this->customProtocol, stream_get_wrappers());
    }

    public function testOpenShouldReturnAResourceWhenStreamWrapperIsRegister()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'r');
        $this->assertNotNull($resource);
        Stream::unregister();
    }

    public function testWriteShouldAddContentInTheBeginningOfFileWhenWritingModeIsGiven()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'w+');

        fwrite($resource, 'first');
        fwrite($resource, 'second,');
        $result = fread($resource, 20);

        fclose($resource);

        $expected = 'second,first';
        $this->assertEquals($expected, $result);
        Stream::unregister();
    }

    public function testWriteShouldAddContentInTheEndOfFileWhenAppendingModeIsGiven()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'a+');

        fwrite($resource, 'first,');
        fwrite($resource, 'second');
        $result = fread($resource, 20);

        fclose($resource);

        $expected = 'first,second';
        $this->assertEquals($expected, $result);
        Stream::unregister();
    }

    public function testReadShouldUsePointerWhenItIsGiven()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'a+');

        fwrite($resource, 'first,');
        fwrite($resource, 'second');
        fseek($resource, 4);
        $result = fread($resource, 20);

        fclose($resource);

        $expected = 't,second';
        $this->assertEquals($expected, $result);
        Stream::unregister();
    }

    /**
     * @expectedException \Pcelta\VirtualStream\OperationNotPermittedException
     */
    public function testReadShouldThrowOperationNotPermittedWhenTryingToReadUsingOnlyAppendingMode()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'a');
        fread($resource, 20);
    }

    /**
     * @expectedException \Pcelta\VirtualStream\OperationNotPermittedException
     */
    public function testReadShouldThrowOperationNotPermittedWhenTryingToWriteUsingOnlyReadingMode()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'r');
        fwrite($resource, 'first,');
    }

    public function testTruncateShouldDiscardPartOfTheContent()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'w+');

        fwrite($resource, 'first');
        fwrite($resource, 'second,');
        ftruncate($resource, 3);
        $result = fread($resource, 20);

        fclose($resource);

        $expected = 'sec';
        $this->assertEquals($expected, $result);
        Stream::unregister();
    }

    public function testEndOfFileShouldReturnFalseWhenPointerIsNotInTheEnd()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'w+');

        fwrite($resource, 'firstANDlast');
        fseek($resource, 5);
        $result = feof($resource);
        fclose($resource);

        $this->assertFalse($result);
        Stream::unregister();
    }

    public function testEndOfFileShouldReturnTrueWhenPointerIsInTheEnd()
    {
        Stream::register();
        $filename = sprintf('%s://temporary/file.anything', Stream::DEFAULT_PROTOCOL);
        $resource = fopen($filename, 'w+');

        fwrite($resource, 'firstANDlast');
        fseek($resource, 13);
        $result = feof($resource);
        fclose($resource);

        $this->assertTrue($result);
        Stream::unregister();
    }
}
