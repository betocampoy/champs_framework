<?php

namespace BetoCampoy\ChampsFramework\Email\Templates;


use BetoCampoy\ChampsFramework\Email\EmailTemplate;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class ConfirmEmail extends EmailTemplate
{

    public function __construct(User $user, array $aditional_data = [])
    {
        parent::__construct($user, $aditional_data);
    }

    public function templateName()
    {
        return "confirm";
    }

    public function assunto()
    {
        return "Seja bem-vindo ao ". CHAMPS_SITE_NAME;
    }

}