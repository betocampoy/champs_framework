<?php

namespace BetoCampoy\ChampsFramework;


/**
 * Class Message
 *
 * @package BetoCampoy\ChampsMessages
 */
class Message
{
    /** @var array */
    private array $styleClasses = [];

    /** @var string|array */
    private $text;

    /** @var string */
    private $type;

    /** @var string */
    private $before;

    /** @var string */
    private $after;

    /** @var bool */
    private $filter = true;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->styleClasses = [
            'main_class' => (defined("CHAMPS_MESSAGE_CLASS") && !empty(CHAMPS_MESSAGE_CLASS) ? CHAMPS_MESSAGE_CLASS : "message"),
            'info' => (defined("CHAMPS_MESSAGE_INFO") && !empty(CHAMPS_MESSAGE_INFO) ? CHAMPS_MESSAGE_INFO : "info icon-info"),
            'success' => (defined("CHAMPS_MESSAGE_SUCCESS") && !empty(CHAMPS_MESSAGE_SUCCESS) ? CHAMPS_MESSAGE_SUCCESS : "success icon-check-square-o"),
            'warning' => (defined("CHAMPS_MESSAGE_WARNING") && !empty(CHAMPS_MESSAGE_WARNING) ? CHAMPS_MESSAGE_WARNING : "warning icon-warning"),
            'error' => (defined("CHAMPS_MESSAGE_ERROR") && !empty(CHAMPS_MESSAGE_ERROR) ? CHAMPS_MESSAGE_ERROR : "error icon-warning"),
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return null|array|string
     */
    public function getText()
    {
        if (is_array($this->text)) {
            $message = [];
            foreach ($this->text as $text) {
                $message[] = $this->before . $text . $this->after;
            }
            return $message;
        }
        return $this->before . $this->text . $this->after;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    /**
     * @param string|array $message
     * @return Message
     */
    public function info($message): Message
    {
        $this->type = $this->styleClasses['info'];
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string|array $message
     * @return Message
     */
    public function success($message): Message
    {
        $this->type = $this->styleClasses['success'];
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string|array $message
     * @return Message
     */
    public function warning($message): Message
    {
        $this->type = $this->styleClasses['warning'];
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string|array $message
     * @return Message
     */
    public function error($message): Message
    {
        $this->type = $this->styleClasses['error'];
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * Activate/Deactivate filter message. By default the message is filtered
     *
     * @param bool $filter
     * @return $this
     */
    public function setFilter(bool $filter): Message
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $messageMainClass = $this->styleClasses['main_class'];
        if (is_array($this->text)) {
            $response = "";
            foreach ($this->getText() as $text) {
                $response .= "<div class='{$messageMainClass} {$this->getType()}'>{$text}</div>";
            }
            return $response;
        }
        return "<div class='{$messageMainClass} {$this->getType()}'>{$this->getText()}</div>";
    }

    /**
     * Set flash Session Key
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    /**
     * @param string|array $message
     * @return string|array
     */
    private function filter($message)
    {
        if (is_array($message)) {
            return $this->filter ? filter_var_array($message, FILTER_SANITIZE_STRIPPED) : $message;
        }
        return $this->filter ? filter_var($message, FILTER_SANITIZE_STRIPPED) : $message;
    }
}