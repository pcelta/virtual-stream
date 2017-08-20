<?php

namespace Pcelta\VirtualStream;

use DateTime;

class Stream implements StreamWrapperInterface
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var Mode
     */
    private $mode;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $pointer;

    /**
     * @var string
     */
    private $contentLeft;

    /**
     * @var DateTime
     */
    private $lastAccess;

    /**
     * @var DateTime
     */
    private $lastModification;

    const HOST = 'host';
    const POINTER_INITIAL_POSITION = 0;
    const DEFAULT_PROTOCOL = 'virtual';

    public function __construct()
    {
        $this->pointer = self::POINTER_INITIAL_POSITION;
    }

    public function stream_open(string $path, string $mode, int $options, &$opened_path)
    {
        $this->setLastAccess();

        $url = parse_url($path);
        $this->mode = new Mode($mode);
        $this->path = $url[self::HOST];

        return true;
    }

    public function stream_read($count): string
    {
        $this->setLastAccess();

        if (!$this->mode->isReading()) {
            throw new OperationNotPermittedException(
                $this->mode->getMode(),
                OperationNotPermittedException::OPERATION_READING
            );
        }

        $data = substr($this->content, $this->pointer++, $count);
        $this->contentLeft = str_replace($data, '', $this->contentLeft);

        return $data;
    }

    public function stream_eof(): bool
    {
        return $this->pointer > strlen($this->content);
    }

    public function stream_seek(int $position , int $whence = SEEK_SET)
    {
        $this->pointer = $position;
    }

    public function stream_write(string $data)
    {
        if (!$this->mode->isWriting()) {
            throw new OperationNotPermittedException(
                $this->mode->getMode(),
                OperationNotPermittedException::OPERATION_WRITING
            );
        }

        $this->setLastModification();

        if ($this->mode->isAppending()) {
            $this->content .= $data;

            return;
        }

        $this->content = $data . $this->content;
    }

    public function stream_truncate(int $newSize): bool
    {
        $this->setLastModification();

        $this->content = substr($this->content, self::POINTER_INITIAL_POSITION, $newSize);

        return true;
    }

    public function stream_tell()
    {
        return $this->pointer;
    }

    public function stream_stat(): array
    {
        return [
            0 => 1,
            1 => '',
            2 => '',
            3 => 0,
            4 => '',
            5 => '',
            6 => '',
            7 => strlen($this->content),
            8 => '',
            9 => $this->lastAccess->getTimestamp(),
            10 => $this->lastModification->getTimestamp(),
            11 => '',
            12 => '',
        ];
    }


    public function stream_flush()
    {
        // As this StreamWrapper does not write into a file or anywhere else, this method will not do anything.
    }

    public static function register(string $protocol = self::DEFAULT_PROTOCOL): bool
    {
        return stream_wrapper_register($protocol, self::class);
    }

    public static function unregister(string $protocol = self::DEFAULT_PROTOCOL): bool
    {
        return stream_wrapper_unregister($protocol);
    }

    protected function setLastAccess()
    {
        $this->lastAccess = new DateTime();
    }

    protected function setLastModification()
    {
        $this->lastModification = new DateTime();
    }
}
