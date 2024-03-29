<?php


namespace BetoCampoy\ChampsFramework\Email;


use BetoCampoy\ChampsFramework\Models\Auth\User;

class EmailTemplate extends EmailEngine implements EmailContract
{
    /** @var EmailView */
    protected EmailView $view;

    /** @var string */
    protected string $pathToViews = __DIR__."/Views";

    /** @var User */
    protected $user;

    /** @var array  */
    protected $aditionalData;

    /**
     * EmailTemplate constructor.
     *
     * @param User $user
     * @param array|null $injected_template_data
     */
    public function __construct(User $user, ?array $injected_template_data = [])
    {
        $this->user = $user;
        $this->aditionalData = $injected_template_data;
        $this->view = new EmailView($this->pathToViews);

        parent::__construct();

        return $this->renderTemplate();
    }

    /**
     * @return EmailEngine|EmailTemplate
     */
    public function renderTemplate()
    {
        $message = $this->view->render($this->templateName(), $this->aditionalData);
        return $this->bootstrap($this->subject(), $message, $this->user->email, $this->user->name);
    }

    /**
     * @return string
     */
    public function templateName():string
    {
        return "default";
    }

    /**
     * @return string
     */
    public function subject():string
    {
        return "Message sent by " . CHAMPS_SEO_SITE_NAME;
    }

}