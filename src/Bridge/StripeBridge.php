<?php

namespace App\Bridge;


use App\Manager\ParametersManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeBridge
{


    /**
     * @var ParametersManager
     */
    private $parametersManager;

    /**
     * @var Request
     */
    private $request;


    public function __construct(ParametersManager $parametersManager, RequestStack $requestStack)
    {

        $this->request = $requestStack->getCurrentRequest();
        $this->parametersManager = $parametersManager;
    }

    public function pay($description, $amount) {
        $token = $this->request->get('stripeToken');
        \Stripe\Stripe::setApiKey($this->parametersManager->getStripeSecretKey());

        try {
            \Stripe\Charge::create(array(
                "amount" => $amount * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => $description
            ));
            $paid = true;


        }catch (\Exception $e){
            $paid = false;
        }

        return $paid;
    }

}
