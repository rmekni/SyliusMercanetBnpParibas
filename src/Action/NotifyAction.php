<?php

declare(strict_types=1);

namespace Agenceweb\MercanetBnpParibasPlugin\Action;

use Agenceweb\MercanetBnpParibasPlugin\Bridge\MercanetBnpParibasBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Webmozart\Assert\Assert;
use Sylius\Abstraction\StateMachine\StateMachineInterface;

final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

    private MercanetBnpParibasBridgeInterface $mercanetBnpParibasBridge;

    //sylius_abstraction.state_machine
    public function __construct(private StateMachineInterface $stateMachine) {}

    /**
     * @param $request Notify
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if ($this->mercanetBnpParibasBridge->paymentVerification()) {
            /** @var PaymentInterface $payment */
            $payment = $request->getFirstModel();

            //$payment->getDetails()['authorisationId'] = $this->mercanetBnpParibasBridge->getAuthorisationId();

            Assert::isInstanceOf($payment, PaymentInterface::class);

            $this->stateMachine->apply($payment, PaymentTransitions::GRAPH, PaymentTransitions::TRANSITION_CREATE);
        }
    }

    public function setApi($mercanetBnpParibasBridge): void
    {
        if (!$mercanetBnpParibasBridge instanceof MercanetBnpParibasBridgeInterface) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->mercanetBnpParibasBridge = $mercanetBnpParibasBridge;
    }

    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayObject;
    }
}
