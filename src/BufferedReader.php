<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use Amp\Cancellation;
use Thesis\ByteReader\Reader;
use Thesis\ByteReader\ReaderIsClosed;

/**
 * @api
 */
final class BufferedReader implements Reader
{
    /** @var int in bytes */
    private const DEFAULT_BUFFER_SIZE = 4096;
    private const MAX_READS = 10;

    private string $buffer = '';

    /**
     * @param positive-int $bufferSize
     */
    public function __construct(
        private readonly Reader $reader,
        private readonly int $bufferSize = self::DEFAULT_BUFFER_SIZE,
    ) {}

    /**
     * @param positive-int $limit
     * @return non-empty-string
     * @throws ReaderIsClosed
     * @throws InsufficientBuffer
     */
    public function read(int $limit, ?Cancellation $cancellation = null): string
    {
        $size = max($limit, $this->bufferSize);

        for ($tries = 0; $tries < self::MAX_READS && $limit > \strlen($this->buffer); ++$tries) {
            $this->buffer .= $this->reader->read($size, $cancellation);
        }

        if ($limit > \strlen($this->buffer)) {
            throw InsufficientBuffer::expects($limit, \strlen($this->buffer));
        }

        /** @psalm-var non-empty-string $bytes */
        $bytes = substr($this->buffer, 0, $limit);
        $this->buffer = substr($this->buffer, $limit);

        return $bytes;
    }
}
