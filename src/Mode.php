<?php


namespace Pcelta\VirtualStream;


class Mode
{
    /**
     * @var string
     */
    private $mode;

    // Pointer is placed in the beginning
    const READING = 'r';
    const READING_PLUS_WRITING = 'r+';
    const WRITING = 'w';
    const WRITING_PLUS_READING = 'w+';
    const CREATE_FOR_WRITING = 'x';
    const CREATE_FOR_WRITING_PLUS_READING = 'x+';
    const TRUNCATE_FOR_WRITING = 'c'; // If the file exists it will be truncated otherwise it will be created
    const TRUNCATE_FOR_WRITING_PLUS_READING = 'c+';

    // Pointer is placed in the end
    const APPEND_WRITING = 'a';
    const APPEND_WRITING_PLUS_READING = 'a+';

    /**
     * @var array
     */
    protected static $allowedModes = [
        self::READING,
        self::READING_PLUS_WRITING,
        self::WRITING,
        self::WRITING_PLUS_READING,
        self::APPEND_WRITING,
        self::APPEND_WRITING_PLUS_READING,
        self::CREATE_FOR_WRITING,
        self::CREATE_FOR_WRITING_PLUS_READING,
        self::TRUNCATE_FOR_WRITING,
        self::TRUNCATE_FOR_WRITING_PLUS_READING
    ];

    protected static $readingModes = [
        self::READING,
        self::READING_PLUS_WRITING,
        self::WRITING_PLUS_READING,
        self::APPEND_WRITING_PLUS_READING,
        self::CREATE_FOR_WRITING_PLUS_READING,
        self::TRUNCATE_FOR_WRITING_PLUS_READING
    ];

    protected static $writingModes = [
        self::READING_PLUS_WRITING,
        self::WRITING,
        self::WRITING_PLUS_READING,
        self::APPEND_WRITING,
        self::APPEND_WRITING_PLUS_READING,
        self::CREATE_FOR_WRITING,
        self::CREATE_FOR_WRITING_PLUS_READING,
        self::TRUNCATE_FOR_WRITING,
        self::TRUNCATE_FOR_WRITING_PLUS_READING
    ];

    public function __construct(string $mode)
    {
        $this->setMode($mode);
    }

    public function setMode(string $mode)
    {
        if (!in_array($mode, self::$allowedModes)) {
            throw new ModeNotAllowedException($mode);
        }

        $this->mode = $mode;
    }

    public function isReading(): bool
    {
        return in_array($this->mode, self::$readingModes);
    }

    public function isWriting(): bool
    {
        return in_array($this->mode, self::$writingModes);
    }
}
