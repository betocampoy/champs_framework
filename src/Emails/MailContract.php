<?php


namespace BetoCampoy\ChampsFramework\Emails;


use BetoCampoy\ChampsFramework\Models\Auth\Auth;

interface MailContract
{
    public function __construct(Auth $user, array $aditional_data = []);

    public function templateName();

    public function assunto();
}