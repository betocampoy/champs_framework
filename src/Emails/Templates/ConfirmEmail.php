<?php

namespace BetoCampoy\ChampsFramework\Emails\Templates;


use BetoCampoy\ChampsFramework\Emails\MailTemplate;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class ConfirmEmail extends MailTemplate
{

    public function __construct(User $user, array $aditional_data = [])
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