<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Thesis\ByteReader\Reader;

#[CoversClass(BufferedReader::class)]
final class BufferedReaderTest extends TestCase
{
    public function testRead(): void
    {
        $reader = $this->createMock(Reader::class);
        $reader
            ->expects(self::once())
            ->method('read')
            ->willReturn('simplestringcontinue');

        $buffer = new BufferedReader($reader, bufferSize: 100);
        self::assertSame('simple', $buffer->read(6));
        self::assertSame('string', $buffer->read(6));
        self::assertSame('cont', $buffer->read(4));
        self::assertSame('inue', $buffer->read(4));
    }

    public function testReadWithTries(): void
    {
        $reader = $this->createMock(Reader::class);
        $reader
            ->expects(self::exactly(2))
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                'string',
                'stri',
            );

        $buffer = new BufferedReader($reader, bufferSize: 100);
        self::assertSame('stringstri', $buffer->read(10));
    }

    public function testInsufficientBuffer(): void
    {
        $reader = $this->createMock(Reader::class);
        $reader
            ->expects(self::exactly(10))
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                'string',
                ...explode(' ', str_repeat(' ', 9)),
            );

        $buffer = new BufferedReader($reader, bufferSize: 100);
        self::expectException(InsufficientBuffer::class);
        self::expectExceptionMessage(\sprintf('There is not enough data in a buffer of size "%d" to read bytes of size "%d".', 6, 10));
        $buffer->read(10);
    }
}
