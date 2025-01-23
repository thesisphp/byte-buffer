<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use Thesis\ByteWriter\Flushable;
use Thesis\ByteWriter\Writer;

/**
 * @api
 */
final class BufferedWriter implements
    Writer,
    Flushable
{
    /** @var int in bytes */
    private const DEFAULT_BUFFER_SIZE = 4096;

    private string $buffer = '';

    /**
     * @param positive-int $bufferSize
     */
    public function __construct(
        private readonly Writer $writer,
        private readonly int $bufferSize = self::DEFAULT_BUFFER_SIZE,
    ) {}

    public function write(string $bytes): void
    {
        $this->buffer .= $bytes;

        if (\strlen($this->buffer) >= $this->bufferSize) {
            $this->doFlush();
        }
    }

    public function flush(): void
    {
        $this->doFlush();
        $this->doWrite($this->buffer);
        $this->buffer = '';
    }

    private function doFlush(): void
    {
        $n = \strlen($this->buffer);
        $cursor = 0;

        while ($cursor <= $n) {
            $bytes = substr($this->buffer, $cursor, $this->bufferSize);
            $this->buffer = substr($this->buffer, $cursor);
            $cursor += $this->bufferSize;

            if (\strlen($bytes) === $this->bufferSize) {
                $this->doWrite($bytes);
            }
        }
    }

    private function doWrite(string $bytes): void
    {
        if ($bytes !== '') {
            $this->writer->write($bytes);
        }
    }
}
