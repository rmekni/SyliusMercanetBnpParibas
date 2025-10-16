<?php

declare(strict_types=1);

namespace Agenceweb\MercanetBnpParibasPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class StatusAction implements ActionInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritDoc
     *
     * @param GetStatusInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        /** @var Request $requestCurrent */
        $requestCurrent = $this->requestStack->getCurrentRequest();

        $transactionReference = $model['transactionReference'] ?? null;

        $status = $model['status'] ?? null;

        if ((null === $transactionReference) && !$requestCurrent->isMethod('POST')) {
            $request->markNew();

            return;
        }

        if (PaymentInterface::STATE_CANCELLED === $status) {
            $request->markCanceled();

            return;
        }
        if (PaymentInterface::STATE_COMPLETED === $status) {
            $request->markCaptured();

            return;
        }

        $request->markUnknown();
    }

    /**
     * @inheritDoc
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
