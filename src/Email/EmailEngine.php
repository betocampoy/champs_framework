<?php

namespace BetoCampoy\ChampsFramework\Email;

use BetoCampoy\ChampsFramework\Log;
use BetoCampoy\ChampsFramework\Message;
use BetoCampoy\ChampsFramework\Models\Email\Queue;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class EmailEngine
 *
 * @package Source\Support
 */
abstract class EmailEngine
{
    /** @var array */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var array|null */
    protected ?array $messages = null;

    /** @var Log  */
    protected Log $log;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->log = new Log(__CLASS__);

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
     * @param string $type
     * @param $messages
     */
    protected function setMessage(string $type, $messages):void
    {
        if(is_array($messages)){
            foreach ($messages as $message){
                $this->messages[$type][] = $message;
            }
        }else{
            $this->messages[$type][] = $messages;
        }
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
            $this->setMessage("error", champs_messages("email_check_data"));
            return false;
        }

        if (!filter_var($this->data->recipient_email, FILTER_VALIDATE_EMAIL)) {
            $this->setMessage("warning", champs_messages("email_invalid_address", ['email' => "recipient"]));
            return false;
        }

        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $this->setMessage("warning", champs_messages("email_invalid_address", ['email' => "sender"]));
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
            $this->setMessage("error", champs_messages("email_fail"));
            $this->log->critical("Fail do send the email", $exception->getTrace());
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
            $mailQueue = (new Queue());
            $mailQueue->subject = $this->data->subject;
            $mailQueue->body = $this->data->body;
            $mailQueue->from_email = $from;
            $mailQueue->from_name = $fromName;
            $mailQueue->recipient_email = $this->data->recipient_email;
            $mailQueue->recipient_name = $this->data->recipient_name;

            if(!$mailQueue->save()){
                $this->setMessage("error", champs_messages("email_queue_fail"));
                return false;
            }
            return true;

        } catch (\PDOException $exception) {
            $this->setMessage("error", champs_messages("email_queue_fail"));
            $this->log->critical("Fail to save into the queue", $exception->getTrace());
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
                    $mail->update(["sent_at" => date_fmt_app()]);
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
     * @return \BetoCampoy\ChampsFramework\Message|null
     */
    public function message():?Message
    {
        $message = new Message();
        foreach ($this->messages as $type => $msg){
            if($msg){
                if(method_exists($message, $type)){
                    $message->$type($msg);
                }
            }
        }
        return $message ?? null;
    }
}