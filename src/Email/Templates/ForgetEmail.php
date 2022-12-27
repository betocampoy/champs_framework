<?php


namespace BetoCampoy\ChampsFramework\Email\Templates;


use BetoCampoy\ChampsFramework\Email\EmailTemplate;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class ForgetEmail extends EmailTemplate
{

    public function __construct(User $user, array $aditional_data = [])
    {
        parent::__construct($user, $aditional_data);
    }

    public function templateName():string
    {
        return "forget";
    }

    public function subject():string
    {
        return "Change or Recover you password ". CHAMPS_SITE_NAME;
    }

}