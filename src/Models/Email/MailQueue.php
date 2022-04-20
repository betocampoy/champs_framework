<?php

namespace BetoCampoy\ChampsFramework\Emails\Model;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class MailQueue
 *
 * @package BetoCampoy\ChampsModel\Email\Model
 */
class MailQueue extends Model
{

    /**
     * Role constructor.
     */
    public function __construct()
    {
        parent::__construct("mail_queue", ["id"], ["subject", "body", "from_email", "from_name", "recipient_email", "recipient_name"]);
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function filteredDataByPending():Model
    {
        return $this->where("sent_at IS NULL");
    }
}