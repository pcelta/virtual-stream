<?php

namespace Pcelta\VirtualStream;

use RuntimeException;

class OperationNotPermittedException extends RuntimeException
{
    const OPERATION_READING = 'Reading';
    const OPERATION_WRITING = 'Writing';

    public function __construct($mode, $operation)
    {
        $message = sprintf('Using this mode:%s is not permitted executing this operation: %s', $mode, $operation);
        parent::__construct($message);
    }
}
