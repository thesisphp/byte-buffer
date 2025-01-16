<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Thesis\ByteWriter\Writer;

#[CoversClass(BufferedWriter::class)]
final class BufferedWriterTest extends TestCase
{
    public function testWrite(): void
    {
        $matcher = self::exactly(3);

        $writer = $this->createMock(Writer::class);
        $writer
            ->expects($matcher)
            ->method('write')
            ->willReturnCallback(static function (string $value) use ($matcher): void {
                match ($matcher->numberOfInvocations()) {
                    1 => self::assertSame('simple', $value),
                    2 => self::assertSame('string', $value),
                    3 => self::assertSame('bytes', $value),
                    default => throw new \LogicException('unreachable'),
                };
            });

        $buffer = new BufferedWriter($writer, bufferSize: 6);
        $buffer->write('simplestring');
        $buffer->write('bytes');
        $buffer->flush();
    }
}
