<?php

namespace BetoCampoy\ChampsFramework\Models\Report;


use BetoCampoy\ChampsFramework\ORM\Model;
use BetoCampoy\ChampsFramework\Session;

/**
 * Class Access
 *
 * @package Source\Models\Report
 */
class Access extends Model
{
    protected array $protected = ["id"];
    protected array $required = ["users", "views", "pages"];

    /**
     * @param array $customFields
     *
     * @return $this
     */
    public function report(array $customFields = []): Access
    {

        $this->where("DATE(created_at) = DATE(CURRENT_TIMESTAMP)");
        $find = $this->find("DATE(created_at) = DATE(CURRENT_TIMESTAMP)")->fetch();
        $session = new Session();

        if (!$find) {
            foreach ($customFields as $field){
                $this->$field = user()->$field ?? null;
            }
            $this->users = 1;
            $this->views = 1;
            $this->pages = 1;

            setcookie("access", true, time() + 86400, "/");
            $session->set("access", true);

            $this->save();
            return $this;
        }

        if (!filter_input(INPUT_COOKIE, "access")) {
            $find->users += 1;
            setcookie("access", true, time() + 86400, "/");
        }

        if (!$session->has("access")) {
            $find->views += 1;
            $session->set("access", true);
        }

        $find->pages += 1;
        $find->save();

        return $this;
    }

}