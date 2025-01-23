<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use Thesis\ByteReader\Reader;
use Thesis\ByteWriter\Flushable;
use Thesis\ByteWriter\Writer;

/**
 * @api
 */
final class BufferedReaderWriter implements
    Writer,
    Reader,
    Flushable
{
    private readonly BufferedReader $reader;

    private readonly BufferedWriter $writer;

    public function __construct(
        Reader $reader,
        ?Writer $writer = null,
    ) {
        $writer ??= $reader;
        if (!$writer instanceof Writer) {
            throw new \UnexpectedValueException(\sprintf('The $reader must be a subtype of "%s", when $writer is not passed, but "%s" given.', Writer::class, get_debug_type($writer)));
        }

        $this->reader = new BufferedReader($reader);
        $this->writer = new BufferedWriter($writer);
    }

    public function write(string $bytes): void
    {
        $this->writer->write($bytes);
    }

    public function read(int $limit): string
    {
        return $this->reader->read($limit);
    }

    public function flush(): void
    {
        $this->writer->flush();
    }
}
