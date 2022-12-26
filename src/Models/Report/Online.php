<?php

namespace BetoCampoy\ChampsFramework\Models\Report;


use BetoCampoy\ChampsFramework\ORM\Model;
use BetoCampoy\ChampsFramework\Session;

/**
 * Class Online
 *
 * @package Source\Models\Report
 */
class Online extends Model
{
    protected array $protected = ["id"];
    protected array $required = ["ip", "url", "agent"];

    /** @var int */
    private $sessionTime;

    /**
     * Online constructor.
     * @param int $sessionTime
     */
    public function __construct(int $sessionTime = 10)
    {
        $this->sessionTime = $sessionTime;
        parent::__construct();
    }

    /**
     * @param bool $count
     * @return array|int|null
     */
    public function findByActive(bool $count = false)
    {
        $this->filteredDataByAuthUser();
        $this->find("updated_at >= CURRENT_TIMESTAMP - INTERVAL {$this->sessionTime} MINUTE");
        if ($count) {
            return $this->count();
        }

        $this->order("updated_at DESC");
        return $this->fetch(true);
    }

    /**
     * @param bool  $clear
     * @param array $customFields
     *
     * @return $this
     */
    public function report(bool $clear = true, array $customFields = []): Online
    {
        $session = new Session();

        if ($clear) {
            $this->clear();
        }

        if (!$session->has("online")) {
            $this->user_id = ($session->authUser ?? null);
            foreach ($customFields as $field){
                $this->$field = user()->$field ?? null;
            }

            $this->url = current_url();
            $this->ip = filter_input(INPUT_SERVER, "REMOTE_ADDR");
            $this->agent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");

            $this->save();
            $session->set("online", $this->id);
            return $this;
        }

        $find = $this->findById($session->online);
        if (!$find) {
            $session->unset("online");
            return $this;
        }

        $this->user_id = ($session->authUser ?? null);
        foreach ($customFields as $field){
            $this->$field = user()->$field ?? null;
        }
        $find->url = current_url();
        $find->pages += 1;
        $find->save();

        return $this;
    }

    /**
     * CLEAR ONLINE
     */
    private function clear():void
    {
        (new Online())->delete("updated_at <= CURRENT_TIMESTAMP - INTERVAL {$this->sessionTime} MINUTE", null);
    }

}