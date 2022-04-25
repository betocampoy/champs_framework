<?php

namespace BetoCampoy\ChampsFramework;

use CoffeeCode\Optimizer\Optimizer;

/**
 * Class Seo
 *
 * @package BetoCampoy\ChampsController\Support
 */
class Seo
{
    /** @var Optimizer */
    protected $optimizer;

    /**
     * Seo constructor.
     * @param string $schema
     */
    public function __construct(string $schema = "article")
    {
        $this->optimizer = new Optimizer();
        $this->optimizer->openGraph(
            CHAMPS_SITE_NAME,
            CHAMPS_SITE_LANG,
            $schema
        )->publisher(
            CHAMPS_SOCIAL_FACEBOOK_PAGE,
            CHAMPS_SOCIAL_FACEBOOK_AUTHOR,
            CHAMPS_SOCIAL_GOOGLE_PAGE,
            CHAMPS_SOCIAL_GOOGLE_AUTHOR
        );

        /* twitter seo informations  */
        if (
          defined("CHAMPS_SOCIAL_TWITTER_CREATOR") && !empty(CHAMPS_SOCIAL_TWITTER_CREATOR) &&
          defined("CHAMPS_SOCIAL_TWITTER_PUBLISHER") && !empty(CHAMPS_SOCIAL_TWITTER_PUBLISHER) &&
          defined("CHAMPS_SITE_DOMAIN") && !empty(CHAMPS_SITE_DOMAIN)
        ){
            $this->optimizer->twitterCard(
              CHAMPS_SOCIAL_TWITTER_CREATOR,
              CHAMPS_SOCIAL_TWITTER_PUBLISHER,
              CHAMPS_SITE_DOMAIN
            );
        }

        /* facebook seo informations  */
        if (defined("CHAMPS_SOCIAL_FACEBOOK_APP") && !empty(CHAMPS_SOCIAL_FACEBOOK_APP)){
            $this->optimizer->facebook(CHAMPS_SOCIAL_FACEBOOK_APP);
        }

    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->optimizer->data()->$name;
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $image
     * @param bool $follow
     * @return string
     */
    public function render(string $title, string $description, string $url, string $image, bool $follow = true): string
    {
        return $this->optimizer->optimize($title, $description, $url, $image, $follow)->render();
    }

    /**
     * @return Optimizer
     */
    public function optimizer(): Optimizer
    {
        return $this->optimizer;
    }

    /**
     * @param string|null $title
     * @param string|null $desc
     * @param string|null $url
     * @param string|null $image
     * @return null|object
     */
    public function data(?string $title = null, ?string $desc = null, ?string $url = null, ?string $image = null)
    {
        return $this->optimizer->data($title, $desc, $url, $image);
    }
}