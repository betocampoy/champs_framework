<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthFacebookOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected bool $runTimeParameter = true;
    protected array $dependencies = [
        AuthModel::class,
        AuthFacebookAppId::class,
        AuthFacebookSecret::class,
        AuthFacebookCallback::class,
        AuthFacebookVersion::class
    ];

    public function getInputType(): string
    {
        return "hidden";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the the OAuth Facebook authentication is active!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication - oauth facebook";
    }

    public function getValue():bool
    {
        return $this->value;
    }

    public function getDefaultValue():bool
    {
        return false;
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function value():bool
    {
        $authModel = CHAMPS_AUTH_MODEL;
        if (!empty(CHAMPS_AUTH_FACEBOOK_APP_ID) && !empty(CHAMPS_AUTH_FACEBOOK_SECRET)
            && !empty(CHAMPS_AUTH_FACEBOOK_CALLBACK) && !empty(CHAMPS_AUTH_FACEBOOK_VERSION)) {
            if (!class_exists("\League\OAuth2\Client\Provider\Facebook")) {
                // gera um alerta
                (new \BetoCampoy\ChampsFramework\Log())->error("It was not possible to activate OAUTH2 Facebook login. Package \"league/oauth2-facebook\": \"^2.0\" wasn't installed");
                return false;
            }

            if (!in_array('facebook_id', (new $authModel)->getColumns() ?? [])) {
                (new \BetoCampoy\ChampsFramework\Log())->error("It was not possible to activate OAUTH2 Facebook login. There isn't the collumn facebook_id in database!");
                return false;
            }
            return true;
        }
        return false;
    }
}