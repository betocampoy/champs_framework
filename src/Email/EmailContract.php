<?php


namespace BetoCampoy\ChampsFramework\Email;

use BetoCampoy\ChampsFramework\Models\Auth\User;

interface EmailContract
{
    public function __construct(User $user, ?array $aditional_data = []);

    public function templateName();

    public function subject();
}