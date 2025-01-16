# ByteBuffer

## Installation

```shell
composer require thesis/bytebuffer
```

## Basic usage

```php
<?php

declare(strict_types=1);

use Thesis\ByteBuffer\BufferedReader;
use Thesis\ByteBuffer\BufferedWriter;
use Thesis\ByteBuffer\BufferedReaderWriter;

$reader = new BufferedReader(/* an implementation of Thesis\ByteReader\Reader */);
$reader->read(10)

$writer = new BufferedWriter(/* an implementation of Thesis\ByteReader\Writer */)
$writer->write('test');

$rw = new BufferedReaderWriter(
    /* an implementation of Thesis\ByteReader\Reader or Thesis\ByteReader\Reader&Thesis\ByteReader\Writer */,
    /* an implementation of ?Thesis\ByteReader\Writer */,
);

$rw->write('test');
$rw->read(4);
$rw->flush();
```
