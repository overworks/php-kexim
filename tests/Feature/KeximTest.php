<?php

declare(strict_types=1);

namespace Minhyung\Kexim\Tests;

use Minhyung\Kexim\Exceptions\InvalidDateException;
use Minhyung\Kexim\Kexim;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Kexim::class)]
final class KeximTest extends TestCase
{
    /** @var \Minhyung\Kexim\Kexim */
    private $kexim;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $authKey = $_ENV['KEXIM_AUTH_KEY'];
        if (! $authKey) {
            $this->markTestSkipped('Did not exists auth key');
        }

        $this->kexim = new Kexim($authKey);
    }

    #[Test]
    public function testCurrency()
    {
        $result = $this->kexim->currency('2024-08-05');
        $this->assertIsArray($result);

        $this->expectException(InvalidDateException::class);
        $result = $this->kexim->currency('2024-08-04');
    }

    #[Test]
    public function testInterest()
    {
        $result = $this->kexim->interest('2024-08-05');
        $this->assertIsArray($result);
    }

    #[Test]
    public function testInternational()
    {
        $result = $this->kexim->international('2024-08-05');
        $this->assertIsArray($result);
    }
}
