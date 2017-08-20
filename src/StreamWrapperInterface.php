<?php

namespace Pcelta\VirtualStream;

interface StreamWrapperInterface
{
    public function stream_open(string $path, string $mode, int $options, &$opened_path);

    public function stream_read($count): string;

    public function stream_eof(): bool;

    public function stream_seek(int $offset, int $whence = SEEK_SET);

    public function stream_flush();

    public function stream_tell();

    public function stream_stat(): array;

    public function stream_truncate(int $newSize): bool;

    public static function register(string $protocol): bool;

    public static function unregister(string $protocol): bool;
}
