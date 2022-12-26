<?php

namespace BetoCampoy\ChampsFramework\Models\Email;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class Queue
 *
 * @package BetoCampoy\ChampsFramework\Models\Email
 */
class Queue extends Model
{
    protected ?string $entity = "email_queue";
    protected array $protected = ["id"];
    protected array $required = ["subject", "body", "from_email", "from_name", "recipient_email", "recipient_name"];


    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function filteredDataByPending():Model
    {
        return $this->where("sent_at IS NULL");
    }
}