<?php

namespace App\Manager;


use Symfony\Component\HttpFoundation\Request;

class StripeManager
{
    private $parametersManager;
    private $request;

    public function __construct(ParametersManager $parametersManager, Request $request)
    {
        $this->parametersManager = $parametersManager;
        $this->request = $request;
    }

    public function payCommand($command) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($this->parametersManager->getStripeSecretKey());

            try {
                \Stripe\Charge::create(array(
                    "amount" => $command->getPrice() * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "First test charge!"
                ));
                $message = "successful command";

                // TODO $mailManager->sendMail($command)
                // TODO $commandManager->recordSuccessfulCommand($command)

            }catch (\Exception $e){
                $message = "stripe error";
            }

        return $message;
    }
}
