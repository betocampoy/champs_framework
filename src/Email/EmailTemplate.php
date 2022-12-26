<?php


namespace BetoCampoy\ChampsFramework\Email;


use BetoCampoy\ChampsFramework\Models\Auth\User;

class EmailTemplate extends EmailEngine implements EmailContract
{
    /** @var EmailView */
    protected EmailView $view;

    /** @var string|null */
    protected ?string $pathToViews = null;

    /** @var User */
    protected $user;

    protected $aditionalData;

    public function __construct(User $user, array $injected_template_data = [])
    {
        $this->user = $user;
        $this->aditionalData = $injected_template_data;
        $this->view = new EmailView($this->pathToViews);

        parent::__construct();

        return $this->renderTemplate();
    }

    public function renderTemplate()
    {
        $message = $this->view->render($this->templateName(), $this->aditionalData);
        return $this->bootstrap($this->assunto(), $message, $this->user->email, $this->user->name);
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