<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use Thesis\ByteReader\Reader;
use Thesis\ByteReader\UnexpectedEof;

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

    public function read(int $limit): string
    {
        $size = max($limit, $this->bufferSize);

        for ($tries = 0; $tries < self::MAX_READS && $limit > \strlen($this->buffer); ++$tries) {
            $this->buffer .= $this->reader->read($size);
        }

        if ($limit > \strlen($this->buffer)) {
            throw new UnexpectedEof(
                \sprintf('There is not enough data in a buffer of size "%d" to read bytes of size "%d".', \strlen($this->buffer), $limit),
            );
        }

        /** @psalm-var non-empty-string $bytes */
        $bytes = substr($this->buffer, 0, $limit);
        $this->buffer = substr($this->buffer, $limit);

        return $bytes;
    }
}
