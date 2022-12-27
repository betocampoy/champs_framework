<?php

namespace BetoCampoy\ChampsFramework\Email\Templates;


use BetoCampoy\ChampsFramework\Email\EmailTemplate;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class ConfirmEmail extends EmailTemplate
{

    /**
     * ConfirmEmail constructor.
     * @param User $user
     * @param array|null $aditional_data
     */
    public function __construct(User $user, ?array $aditional_data = [])
    {
        parent::__construct($user, $aditional_data);
    }

    public function templateName():string
    {
        return "confirm";
    }

    public function subject():string
    {
        return "Welcome to ". CHAMPS_SITE_NAME;
    }

}