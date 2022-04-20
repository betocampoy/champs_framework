<?php

namespace BetoCampoy\ChampsFramework\Emails\Templates;


use BetoCampoy\ChampsModel\Auth\Auth;
use BetoCampoy\ChampsModel\Email\MailTemplate;

class ConfirmEmail extends MailTemplate
{

    public function __construct(Auth $user, array $aditional_data = [])
    {
        parent::__construct($user, $aditional_data);
    }

    public function templateName()
    {
        return "Confirm";
    }

    public function assunto()
    {
        return "Seja bem-vindo ao ". CHAMPS_SITE_NAME;
    }

}