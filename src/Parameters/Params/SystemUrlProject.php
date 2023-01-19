<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemUrlProject extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    protected bool $runTimeParameter = true;
    protected array $dependencies = [
        SystemEnvironmentIdentifier::class,
        SecurityForceHttps::class,
        SystemUrlDev::class,
        SystemUrlPrd::class,
        SystemUrlUat::class,
    ];

    public function getInputType(): string
    {
        return "hidden";
    }

    public function getInputAttributes(): array
    {
        return [];
    }

    public function getSection(): string
    {
        return "system";
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getDefaultValue(): ?string
    {
        return null;
    }

    public function getValidValues(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function value():?string
    {
        if(!defined("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER)) return null;

        $projectUrl = strtolower(constant("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER));

        if (CHAMPS_SECURITY_FORCE_HTTPS) {
            $projectUrl = substr($projectUrl, 0, 8) == "https://"
                ? $projectUrl
                : (substr($projectUrl, 0, 7) == "http://"
                    ? str_replace("http://", "https://", $projectUrl) : "https://{$projectUrl}");
        } else {
            $projectUrl = (substr($projectUrl, 0, 8) == "https://" || substr($projectUrl, 0, 7) == "http://")
                ? $projectUrl
                : "http://{$projectUrl}";
        }

        return $projectUrl[strlen($projectUrl) - 1] == "/"
            ? substr($projectUrl, 0, strlen($projectUrl) - 1) : $projectUrl;

    }
}