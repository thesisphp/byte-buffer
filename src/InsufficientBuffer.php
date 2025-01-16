<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

/**
 * @api
 */
final class InsufficientBuffer extends \RuntimeException
{
    public static function expects(int $limit, int $bufferSize): self
    {
        return new self(\sprintf('There is not enough data in a buffer of size "%d" to read bytes of size "%d".', $bufferSize, $limit));
    }
}
