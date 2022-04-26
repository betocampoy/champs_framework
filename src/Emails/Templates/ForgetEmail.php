<?php


namespace BetoCampoy\ChampsFramework\Emails\Templates;



use BetoCampoy\ChampsFramework\Emails\MailTemplate;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class ForgetEmail extends MailTemplate
{

    public function __construct(User $user, array $aditional_data = [])
    {
        parent::__construct($user, $aditional_data);
    }

    public function templateName()
    {
        return "forget";
    }

    public function assunto()
    {
        return "Recuperar/Trocar a senha do ". CHAMPS_SITE_NAME;
    }

}