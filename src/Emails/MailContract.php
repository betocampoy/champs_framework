<?php


namespace BetoCampoy\ChampsFramework\Emails;

use BetoCampoy\ChampsFramework\Models\Auth\User;

interface MailContract
{
    public function __construct(User $user, array $aditional_data = []);

    public function templateName();

    public function assunto();
}