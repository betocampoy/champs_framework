<?php

namespace BetoCampoy\ChampsFramework\Email;

use BetoCampoy\ChampsFramework\Models\Email\Queue;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class EmailEngine
 *
 * @package Source\Support
 */
class EmailEngine
{
    /** @var array */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var array */
    private $message = [];

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();

        //setup
        $this->mail->isSMTP();
        $this->mail->setLanguage(CHAMPS_MAIL_OPTION_LANG);
        $this->mail->isHTML(CHAMPS_MAIL_OPTION_HTML);
        $this->mail->SMTPAuth = CHAMPS_MAIL_OPTION_AUTH;
        $this->mail->SMTPSecure = CHAMPS_MAIL_OPTION_SECURE;
        $this->mail->CharSet = CHAMPS_MAIL_OPTION_CHARSET;

        //auth
        $this->mail->Host = CHAMPS_MAIL_HOST;
        $this->mail->Port = CHAMPS_MAIL_PORT;
        $this->mail->Username = CHAMPS_MAIL_USER;
        $this->mail->Password = CHAMPS_MAIL_PASS;
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @param string $recipientName
     *
     * @return $this
     */
    public function bootstrap(string $subject, string $body, string $recipient, string $recipientName): EmailEngine
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient;
        $this->data->recipient_name = $recipientName;

        return $this;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     *
     * @return $this
     */
    public function attach(string $filePath, string $fileName): EmailEngine
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param $from
     * @param $fromName
     * @return bool
     */
    public function send(string $from = CHAMPS_MAIL_SENDER['address'], string $fromName = CHAMPS_MAIL_SENDER["name"]): bool
    {
        if (empty($this->data)) {
            array_push($this->message['error'], "Erro ao enviar, favor verifique os dados");
            return false;
        }

        if (!filter_var($this->data->recipient_email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->message['warning'], "O e-mail de destinatário não é válido");
            return false;
        }

        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            array_push($this->message['warning'], "O e-mail de remetente não é válido");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
            $this->mail->setFrom($from, $fromName);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            array_push($this->message['error'], $exception->getMessage());
            return false;
        }
    }

    /**
     * @param string $from
     * @param string $fromName
     * @return bool
     */
    public function queue(string $from = CHAMPS_MAIL_SENDER['address'], string $fromName = CHAMPS_MAIL_SENDER["name"]): bool
    {
        try {
            $params = [
              "subject" => $this->data->subject,
              "body" => $this->data->body,
              "from_email" => $from,
              "from_name" => $fromName,
              "recipient_email" => $this->data->recipient_email,
              "recipient_name" => $this->data->recipient_name,
            ];

            $mailQueue = (new Queue())->fill($params);
            if(!$mailQueue->save()){
                array_push($this->message['error'][], "Falha ao enviar");
                return false;
            }
            return true;
//            $stmt = \BetoCampoy\ChampsModel\Connect::getInstance()->prepare(
//                "INSERT INTO
//                    mail_queue (subject, body, from_email, from_name, recipient_email, recipient_name)
//                    VALUES (:subject, :body, :from_email, :from_name, :recipient_email, :recipient_name)"
//            );
//
//            $stmt->bindValue(":subject", $this->data->subject, \PDO::PARAM_STR);
//            $stmt->bindValue(":body", $this->data->body, \PDO::PARAM_STR);
//            $stmt->bindValue(":from_email", $from, \PDO::PARAM_STR);
//            $stmt->bindValue(":from_name", $fromName, \PDO::PARAM_STR);
//            $stmt->bindValue(":recipient_email", $this->data->recipient_email, \PDO::PARAM_STR);
//            $stmt->bindValue(":recipient_name", $this->data->recipient_name, \PDO::PARAM_STR);
//
//            $stmt->execute();
//            return true;
        } catch (\PDOException $exception) {
            array_push($this->message['error'], $exception->getMessage());
            return false;
        }
    }

    /**
     * @param int $perSecond
     */
    public function sendQueue(int $perSecond = 5)
    {
        $queue = (new Queue())->filteredDataByPending();
        if ($queue->count() > 0) {
            foreach ($queue->fetch(true) as $mail) {
                $toSend = $this->bootstrap(
                    $mail->subject,
                    $mail->body,
                    $mail->recipient_email,
                    $mail->recipient_name
                );

                if ($toSend->send($mail->from_email, $mail->from_name)) {
                    usleep(1000000 / $perSecond);
                    $mail->udate("sent_at = CURRENT_TIMESTAMP");
//                    \BetoCampoy\ChampsModel\Connect::getInstance()
//                      ->exec("UPDATE mail_queue SET sent_at = NOW() WHERE id = {$send->id}");
                }
            }
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return array
     */
    public function message(?string $type = null): array
    {
        if($type && in_array($type, ["error", "warning"])){
            return $this->message[$type];
        }

        return $this->message;
    }
}