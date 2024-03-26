<?php

namespace App\Common\Messages;

use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Message;

class VerificationCode extends Message
{
    protected $template = '1876077';

    public function __construct(protected $code)
    {
        parent::__construct();
    }

    public function getData(GatewayInterface $gateway = null): array
    {
        return [$this->code, config('sms.expiration')];
    }
}
