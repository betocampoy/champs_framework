<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Verify if the e-mail module was active.
 * This is a run-time parameter. It will be set as true if the mandatory e-mail parameter were set
 *
 * Class EmailModuleOn
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailModuleOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected bool $runTimeParameter = true;
    protected array $dependencies = [
        EmailAuthHost::class,
        EmailAuthPort::class,
        EmailAuthUser::class,
        EmailAuthUserPassword::class,
        EmailDefaultSender::class,
    ];

    public function getInputType(): string
    {
        return "hidden";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Verify if the e-mail module was active!"];
    }

    public function getSectionGroup(): string
    {
        return "e-mail configuration";
    }

    public function getSection(): string
    {
        return "e-mail configuration options";
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function getDefaultValue(): bool
    {
        return false;
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function value():bool
    {
        if (!empty(CHAMPS_EMAIL_AUTH_HOST) && !empty(CHAMPS_EMAIL_AUTH_PORT)
            && !empty(CHAMPS_EMAIL_AUTH_USER) && !empty(CHAMPS_EMAIL_AUTH_USER_PASSWORD)
            && isset(CHAMPS_EMAIL_DEFAULT_SENDER['name']) && !empty(CHAMPS_EMAIL_DEFAULT_SENDER['name'])
            && isset(CHAMPS_EMAIL_DEFAULT_SENDER['address']) && !empty(CHAMPS_EMAIL_DEFAULT_SENDER['address'])
        ) {
            return true;
        }
        return false;
    }
}