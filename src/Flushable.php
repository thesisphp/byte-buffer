<?php

declare(strict_types=1);

namespace Thesis\ByteBuffer;

use Thesis\ByteWriter\WriteFailed;

/**
 * @api
 */
interface Flushable
{
    /**
     * @throws WriteFailed
     */
    public function flush(): void;
}
