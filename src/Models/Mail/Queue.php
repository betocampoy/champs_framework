<?php

namespace BetoCampoy\ChampsFramework\Models\Mail;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class MailQueue
 *
 * @package BetoCampoy\ChampsModel\Email\Model
 */
class Queue extends Model
{
    protected ?string $entity = "mail_queue";
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