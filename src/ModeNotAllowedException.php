<?php

namespace Pcelta\VirtualStream;

use InvalidArgumentException;

class ModeNotAllowedException extends InvalidArgumentException
{
    public function __construct($invalidMode)
    {
        $message = sprintf('Mode given is not allowed: %s', $invalidMode);
        parent::__construct($message, 0, null);
    }
}
