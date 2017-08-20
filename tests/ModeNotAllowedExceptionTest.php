<?php

namespace Pcelta\VirtualStream;

use PHPUnit\Framework\TestCase;

class ModeNotAllowedExceptionTest extends TestCase
{
    /**
     * @expectedException \Pcelta\VirtualStream\ModeNotAllowedException
     * @expectedExceptionMessage Mode given is not allowed: INVALID-MODE
     */
    public function testConstructShouldSetACustomMessageAlways()
    {
        throw new ModeNotAllowedException('INVALID-MODE');
    }
}
