<?php


namespace BetoCampoy\ChampsFramework\Emails;



use BetoCampoy\ChampsFramework\Models\Auth\Auth;

class MailTemplate extends EmailEngine implements MailContract
{
    /** @var EmailView */
    protected $view;

    /** @var Auth */
    protected $user;

    protected $aditionalData;

    public function __construct(Auth $user, array $injected_template_data = [])
    {
        $this->user = $user;
        $this->aditionalData = $injected_template_data;
        $this->view = new EmailView();

        parent::__construct();

        return $this->renderTemplate();
    }

    public function renderTemplate()
    {
        $message = $this->view->render($this->templateName(), $this->aditionalData);
        return $this->bootstrap($this->assunto(), $message, $this->user->email, $this->user->first_name);
    }

    public function templateName()
    {
        return "default";
    }

    public function assunto()
    {
        return "Essa mensagem foi enviada pelo " . CHAMPS_SITE_NAME;
    }

}