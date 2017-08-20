<?php

namespace Pcelta\VirtualStream;

use PHPUnit\Framework\TestCase;

class OperationNotPermittedExceptionTest extends TestCase
{
    /**
     * @dataProvider operationsProvider
     * @expectedException \Pcelta\VirtualStream\OperationNotPermittedException
     * @expectedExceptionMessageRegExp  /Using this mode\:[a-z]{1}(\+|) is not permitted executing this operation\: [A-Z]{1}[a-z]{1,}$/
     */
    public function testConstructShouldSetACustomMessageAlways($mode, $operation)
    {
        throw new OperationNotPermittedException($mode, $operation);
    }

    public static function operationsProvider()
    {
        return [
            ['w', OperationNotPermittedException::OPERATION_READING],
            ['r', OperationNotPermittedException::OPERATION_WRITING]
        ];
    }
}
