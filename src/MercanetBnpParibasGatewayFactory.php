<?php

declare(strict_types=1);

namespace Agenceweb\MercanetBnpParibasPlugin;

use Agenceweb\MercanetBnpParibasPlugin\Action\ConvertPaymentAction;
use Agenceweb\MercanetBnpParibasPlugin\Bridge\MercanetBnpParibasBridgeInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class MercanetBnpParibasGatewayFactory extends GatewayFactory
{
    const FACTORY_NAME = 'mercanet_bnp_paribas_payment';

    /**
     * @inheritDoc
     */
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'Mercanet BNP Paribas',

            'payum.action.convert' => new ConvertPaymentAction(),

            'payum.http_client' => '@agenceweb.mercanet_bnp_paribas.bridge.mercanet_bnp_paribas_bridge',
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => '',
                'secure_key' => '',
                'merchant_id' => '',
                'key_version' => '',
            ];

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['secret_key', 'environment', 'merchant_id', 'key_version'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var MercanetBnpParibasBridgeInterface $mercanetBnpParibasBridge */
                $mercanetBnpParibasBridge = $config['payum.http_client'];

                $mercanetBnpParibasBridge->setSecretKey($config['secret_key']);
                $mercanetBnpParibasBridge->setMerchantId($config['merchant_id']);
                $mercanetBnpParibasBridge->setKeyVersion($config['key_version']);
                $mercanetBnpParibasBridge->setEnvironment($config['environment']);

                return $mercanetBnpParibasBridge;
            };
        }
    }
}
