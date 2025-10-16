<?php

declare(strict_types=1);


namespace Agenceweb\MercanetBnpParibasPlugin\Bridge;

use Agenceweb\MercanetBnpParibasPlugin\Legacy\Mercanet;

interface MercanetBnpParibasBridgeInterface
{
    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function createMercanet(string $secretKey): Mercanet;

    public function paymentVerification(): bool;

    public function getAuthorisationId(): string;

    public function isPostMethod(): bool;

    public function getSecretKey(): string;

    public function setSecretKey(string $secretKey): void;

    public function getMerchantId(): string;

    public function setMerchantId(string $merchantId): void;

    public function getKeyVersion(): string;

    public function setKeyVersion(string $keyVersion): void;

    public function getEnvironment(): string;

    public function setEnvironment(string $environment): void;
}
