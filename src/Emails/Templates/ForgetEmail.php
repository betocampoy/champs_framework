<?php


namespace BetoCampoy\ChampsFramework\Emails\Templates;


use BetoCampoy\ChampsModel\Auth\Auth;
use BetoCampoy\ChampsModel\Email\MailTemplate;

class ForgetEmail extends MailTemplate
{

    public function __construct(Auth $user, array $aditional_data = [])
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