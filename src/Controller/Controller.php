<?php

namespace BetoCampoy\ChampsFramework\Controller;

use BetoCampoy\ChampsFramework\Models\Report\Access;
use BetoCampoy\ChampsFramework\Models\Report\Online;
use BetoCampoy\ChampsFramework\Router\Router;
use BetoCampoy\ChampsFramework\Seo;
use BetoCampoy\ChampsFramework\View;
use BetoCampoy\ChampsFramework\Message;
use BetoCampoy\ChampsFramework\Log;
use BetoCampoy\ChampsFramework\ORM\Model;
use function ICanBoogie\singularize;

/**
 *
 * ###   MAIN CONTROLLER MODEL   ###
 *
 * The controller will try to find a model based in controller name, if a valid model was found, the model will be
 * loaded in the property $this->model
 *
 * Besides it will try to found a valid model id in input data. Basicly it will append _id in model name (ex. User model, becames user_id.
 * If was necessary change the model id, set the property modelFieldIdName (ex. protected $modelFieldIdName = "usuario_id";)
 *
 * the rules to find a model is, singularize the controller name and search in the name space \Source\Models.
 *
 * If you need to set diferente model name and name space, set the properties bellow
 *       protect $modelsNamespace = "Models\\Path\\";
 *       protect $model = "Models\\Path\\CustomModelName"; or protect $model = new CustomModelName;
 *
 *
 * ###   CONTROLLER PROTECTED WITH PERMISSION   ###
 *
 * To activate the protection based on users, permissions and roles, install the betocampoy/champs_auth and setup the database [see betocampoy/champs_auth documentation for more information]
 * Assuming that environment is configured, you need to set the attribute
 *     protected $protectedController = true;
 *
 * Once activated, the controller will create and array assoc af action => permission_name in the property [controllerPermissions].
 * And to build this array, is use a GLOBAL CONSTANTE [CHAMPS_GLOBAL_PERMISSIONS] and the protected property [controllerPermissions].
 * Theywill be merged and in case of conflict, prevails the value of controllerPermission.
 *
 * The permission_name is composed by default for the controller class name the value defined in the array. To change this pattern, use the property
 *    protected $basePermissionName
 *
 * Example:
 *
 * ===> in some boot or config file of you app, define the constante
 * define("CHAMPS_GLOBAL_PERMISSIONS", [
 *   //"action" => "permission",
 *   "list" => "listar",
 *   "create" => "incluir",
 *   "store" => "incluir",
 *   "delete" => "deletar",
 * ]);
 *
 * ===> in Users controller class.
 *
 * protected $basePermissionName = "Usuarios" // if comment this line, the default world be Users
 *
 * protected $controllerPermissions = [
 *   "list" => "home",
 *   "edit" => "editar",
 * ];
 *
 * the final permitions property will be
 * protect $controllerPermissions = [
 *   "list" => "Usuarios Home",
 *   "edit" => "Usuarios Editar",
 *   "create" => "Usuarios Incluir",
 *   "store" => "Usuarios Incluir",
 *   "delete" => "Usuarios Deletar",
 * ];
 *
 * If none of constante or controllerPermission was set, the array will be filled by default values
 *     ["list" => "list", "create" => "create", "store" => "create", "edit" => "update" , "update" => "update", "delete" => "delete"];
 *
 * IMPORTANTE: To performe de permission validation, use the method bellow
 * checkPermission("edit") => if user has the permission "Usuarios Editar" the method returns true;
 *
 * ###   CSRF VALIDATION   ###
 *
 * By default, the controller will try performe the CSRF validations for post connection
 *
 * If you need to disable the csrf check, set the attribute bellow in controller
 *     protect $csrfValidation = false;
 *
 * ###   INPUTS VALIDATION   ###
 *
 * The controller optionally can performe the validation of data input for POST method. The controller use the "rakit/validation"
 * see the rakit documentation for more information about the validation rules.
 *
 * To activate the inputs validation you must set the properties below
 *     protected $inputsValidation = true;
 *     protected $validationNamespace = "Validators\\Path\\"; // The property is option, by default the validator name space is "Source\\Validators\\"
 *
 * Create the model validator classes, extending [BetoCampoy\ChampsFramework\Support\Validator\Validator] in the name space above
 * the validator clases must have the same name of the model class with the sufix "Validator", so the validator of the User model
 * will be UserValidator.
 *
 * So, if the $inputsValidation = true and the controller find a valid validator class in $validationNamespace, the rules will
 * be validated.
 *    Sometimes, it's necessary overwrite some rule or add a new rules, to do that, create the method below
 *
 *    protected function validationRules(array $data = []):array
 *    {
 *       return [
 *          "create" => ["input_name" => "required"],
 *          "update" => [],
 *       ];
 *    }
 *
 *    protected function validationAliases():array
 *    {
 *        return ["input_name" => "Name in the error messages"];
 *    }
 *
 *
 *
 * Class Controller
 *
 * @package BetoCampoy\ChampsController
 */
abstract class Controller
{
    /** @var Router */
    protected Router $router;

    /** @var null|array */
    protected ?array $request = [];

    /** @var null|Seo */
    protected ?Seo $seo = null;

    /** @var View */
    protected View $view;

    /** @var Message */
    protected Message $message;

    /** @var Log */
    protected Log $log;

    /** @var string none, all, online, access */
    protected string $reports = 'none';

    /** @var array used to customize user fields in reports, ex. client_id, operation_id */
    protected array $reportsCustomFields = [];

    /** @var bool */
    protected bool $reportsClearOnline = true;

    /** @var null|string $pathToViews */
    protected ?string $pathToViews = __CHAMPS_THEME_DIR__ . "/" . CHAMPS_VIEW_WEB . "/";

    /** @var null|Model */
    protected ?Model $loadedModel = null;

    /** @var null|string */
    protected ?string $modelClass = null;

    /** @var null|string */
    protected ?string $modelFieldIdName = null;

    /** @var bool */
    protected bool $csrfValidation = true;

    /** @var bool */
    protected bool $inputsValidation = false;

    /** @var array besides store and update methods, inform in this attribute other methods you need validate inputs */
    protected array $validatedMethods = [];

    /** @var null $validationNamespace informe where the controller finds the validators classes */
    protected ?string $validationNamespace = null;

    /** @var bool $protectedController define if the controller must be protected by permissions */
    protected bool $protectedController = false;

    /** @var string $base_permission_name by default is the class name. */
    protected ?string $basePermissionName;

    /**
     * The set of action that must bo controlled, example List, Create, Update, Delete...
     *
     * The default actions are List, Create, Update and Delete. To customize you can create a system constant named CONF_SYS_PERMISSIONS
     * as an array
     *
     * @var array $controllerPermissions
     */
    protected array $controllerPermissions = [];

    /**
     * Controller constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->request = $router ? $router->request() : [];
        $this->view = new View($this->pathToViews);
        $this->seo = new Seo();
        $this->message = new Message();
        $this->log = new Log();

        $this->defineMainControllerModel();

        /**
         * Check if it is a protected controller e call the methods to verify permission
         */
        if ($this->protectedController == true) {
            $this->setControllerPermissions();
            $this->checkPermission($this->filterRequestAction());
        }

        /* CSRF validation */
        if (!$this->csrfValidation()) {
            $this->returnErrorMessage();
        }

        /* load main model if needed */
        if ($this->loadModel() === false) {
            $this->returnErrorMessage();
        }

        /** Check if validator class exists and perform the validation */
        if (!$this->inputsValidation()) {
            $this->returnErrorMessage();
        }

        /*
         * validar se o reports estao ativos antes de logar
         */
        if ($this->reports == 'all' || $this->reports == 'access') {
            (new Access())->report($this->reportsCustomFields);
        }
        if ($this->reports == 'all' || $this->reports == 'online') {
            (new Online())->report($this->reportsClearOnline, $this->reportsCustomFields);
        }

    }

    public function __get($name)
    {
        return null;
    }

    protected function redirect(string $url): void
    {
        if (isXmlHttpRequest()) {
            echo json_encode(["redirect" => $url]);
            exit();
        }
        redirect($url);
    }

    /**
     * @param array|string $message
     */
    protected function returnErrorMessage(): void
    {
        if (isXmlHttpRequest()) {
            $json['message'] = $this->message->render();
            echo json_encode($json);
            die;
        } else {
            $this->message->flash();
            $arrayControllerName = explode("\\", get_class($this));
            $controllerName = strtolower(end($arrayControllerName));
            $this->redirect($this->router->route("{$controllerName}.home") ?? url("/"));
        }
    }

    /**
     * @param string $modelClass
     * @param array $data
     * @param array $requiredFields
     *
     * @return \stdClass
     */
    protected function sanitizeData(string $modelClass, array $data = [], array $requiredFields = []): \stdClass
    {
        if (!strstr($modelClass, "\\")) {
            $modelClass = "Source\\Models\\{$modelClass}";
        }

        if (!class_exists($modelClass)) {
            return (object)$data;
        }

        /** @var Model $model */
        $model = (new $modelClass);
        if (!in_array("BetoCampoy\ChampsFramework\ORM\Model", class_parents($model))) {
            return (object)$data;
        }

        $requiredFields = array_merge($model->getRequiredFields(), $requiredFields);

        $sanitizedData = new \stdClass();
        foreach ($requiredFields as $field) {
            $sanitizedData->$field = isset($data[$field]) ? filter_var($data[$field], $model->getFilterDataType($field)) : null;
        }

        return $sanitizedData;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getDefaultMessage(string $type): string
    {
        if (!isset($this->default_messages[$type])) {
            return "Ocorreu um erro ao executar a operação";
        }

        return $this->default_messages[$type];
    }

    /*
     * The two methods bellow are used by trait InputsValidator to customize the validator rules and aliases
     * create the desired method in child class and create the validator rules.
     */

    /**
     * @param array $data
     *
     * @return array[]
     */
    protected function validationRules(array $data = []): array
    {
        return [
            "create" => [],
            "update" => [],
        ];
    }

    /**
     * @return array
     */
    protected function validationAliases(): array
    {
        return [];
    }

    /**
     * ###   MAIN CONTROLLER MODEL   ###
     *
     * The controller will try to find a relative model based in controller name, if it find a valid model the model will be
     * loaded in the property $this->model.
     *
     * the rules to find a model is, singularize the controller name and search in the name space \Source\Models.
     *
     * If you need to set diferente model name and name space, set the attributes bellow
     *       protect $modelsNamespace = "\\New\\Models\\Path\\";
     *       protect $mainControllerModel = "CustomModelName";
     */
    protected function defineMainControllerModel(): void
    {
        $controllerName = explode("\\", get_class($this));
        $modelFullName = (property_exists($this, 'modelsNamespace') && !empty($this->modelsNamespace)
                ? $this->modelsNamespace
                : "\\Source\\Models\\") . singularize(end($controllerName));

        /* if it was not specified a model class, try automaticly load model based in controller's name */
        if (!$this->modelClass) {
            $this->loadedModel = class_exists($modelFullName) ? new $modelFullName() : null;
        } else {
            $this->loadedModel = class_exists($this->modelClass) ? new $this->modelClass() : null;
        }

        /* Model is already loaded */
        if ($this->loadedModel instanceof Model) {
            return;
        }

        $this->loadedModel = null;

        //        $model = is_string() $this->model ? t
        //        if($this->model)
        //
        //        $controllerName = explode("\\", get_class($this));
        //        $modelFullName = (property_exists($this, 'modelsNamespace') && !empty($this->modelsNamespace) ? $this->modelsNamespace : "\\Source\\Models\\" ) . str_singularize(end($controllerName));
        //
        //        $this->mainControllerModel = $this->mainControllerModel ?? $modelFullName;
        //        $this->mainControllerModel = class_exists($this->mainControllerModel) ? $this->mainControllerModel : null;
        //
        //        var_dump([
        //          $this->mainControllerModel
        //        ]);
    }

    /**
     * ###   CSRF VALIDATION   ###
     *
     * By default, the controller will try to performe the CSRF validations for post connection
     *
     * If you need to disable the csrf check, set the attribute bellow in controller
     *     protect $csrfValidation = false;
     *
     * @param array $request
     *
     * @return bool
     */
    protected function csrfValidation(): bool
    {

        if (!isset($this->request['method']) || !isset($this->request['data'])) {
            $this->message->error("Request is invalid!");
            return false;
        }

        if (!$this->csrfValidation) {
            return true;
        }

        if ($this->request['method'] == 'POST' || $this->request['method'] == 'DELETE') {
            if (!csrf_verify($this->request['data'])) {
                $this->message->error("CSRF token invalid!");
                return false;
            }
        }

        return true;
    }

    /**
     * If the modelValidator class exists in [$this->validationNamespace] namespace , perform the
     * inputs validation.
     *
     * If the property validationNamespace isn't defined, the namespace default by validator is [Source\Validators]
     *
     * @param array $request
     *
     * @return bool
     */
    protected function inputsValidation(): bool
    {

        if ($this->inputsValidation === false) {
            return true;
        }

        if (!isset($this->request['method']) || !isset($this->request['action']) || !isset($this->request['data'])) {
            $this->message->error("Request is invalid!");
            return false;
        }

        $method = $this->request['method'];
        $action = $this->request['action'];
        $data = $this->request['data'];

        if ($method == 'POST' && in_array($action, array_merge(["store", "update"], $this->validatedMethods))) {
            /* validate data */

//            $validatorNameSpace = $this->validationNamespace ?? "Source\\Validators\\";
//            $validatorVendorNameSpace = "\\BetoCampoy\\ChampsFramework\\Support\\Validator\\Validators\\";
//            $arrayClass = explode("\\", get_class($this));
//            $className = singularize(end($arrayClass));
//            $projectClass = $validatorNameSpace . $className . "Validator";
//            $vendorClass = $validatorVendorNameSpace . $className . "Validator";
//            $validatorClass = class_exists($projectClass) ? $projectClass : (class_exists($vendorClass) ? $vendorClass : null);

            $rules = [];
            if (method_exists($this, 'validationRules')) {
                $rules = isset($this->validationRules($data)[$action])
                    ? $this->validationRules($data)[$action]
                    : [];
            }
            $aliases = method_exists($this, 'validationAliases')
                ? $this->validationAliases()
                : [];

            return $this->performValidation(null, $data, $rules, $aliases);

//            $validator = new $validatorClass($data, $rules, $aliases);
//            $validation = $validator->make();
//            $validation->validate();

//            if ($errors = $validator->errors($validation)) {
//                $this->message->error($errors);
//                return false;
//            }

        }

        return true;
    }

    /**
     * Perform the Input Validation
     *
     * @param string|null $validatorName
     * @param array|null $data
     * @param array|null $rules
     * @param array|null $aliases
     * @return bool
     */
    protected function performValidation(?string $validatorName = null, ?array $data = [], ?array $rules = [], ?array $aliases = []): bool
    {
        $validatorProjectNameSpace = $this->validationNamespace ?? "Source\\Validators\\";
        $validatorVendorNameSpace = "BetoCampoy\\ChampsFramework\\Support\\Validator\\Validators\\";

        if (empty($validatorName)) {
            /* None validator was informed, search for the validator in Source\Validators project folder then in
            BetoCampoy\ChampsFramework\Support\Validator\Validators using the {Model}Validator default class name*/
            $arrayClass = explode("\\", get_class($this));
            $className = singularize(end($arrayClass));
            $projectClass = $validatorProjectNameSpace . $className . "Validator";
            $vendorClass = $validatorVendorNameSpace . $className . "Validator";
            $validatorClass = class_exists($projectClass) ? $projectClass : (class_exists($vendorClass) ? $vendorClass : null);
        } elseif (strpos($validatorName, '\\') === false) {
            /* The Shortname of validator class was informed, search for it in project validators folder and then on vendor validators folder */
            $validatorClass = class_exists("{$validatorProjectNameSpace}{$validatorName}Validator")
                ? "{$validatorProjectNameSpace}{$validatorName}Validator"
                : (class_exists("{$validatorVendorNameSpace}{$validatorName}Validator") ? "{$validatorVendorNameSpace}{$validatorName}Validator" : null);
        } else {
            /* The full namespace class name was informed, use it */
            $validatorClass = class_exists($validatorName)
                ? $validatorName
                : null;
        }

        if ($validatorClass) {
            $validator = new $validatorClass($data, $rules, $aliases);
            $validation = $validator->make();
            $validation->validate();

            if ($errors = $validator->errors($validation)) {
                $this->message->error($errors);
                return false;
            }
        }
        return true;
    }

    /**
     * @param array|null $request
     *
     * @return bool|null
     */
    private function loadModel(): ?bool
    {
        /* Controller don't have a main model */
        if (!$this->loadedModel instanceof Model) {
            $this->loadedModel = null;
            return null;
        }

        /** Check if main id was informed and model */
        $ar = explode("\\", get_class($this->loadedModel));
        $main_key = property_exists($this, 'modelFieldIdName') && !empty($this->modelFieldIdName)
            ? $this->modelFieldIdName
            : strtolower(str_snake_case_reverse(end($ar)) . "_id");

        $model_id = isset($this->request['data'][$main_key])
            ? filter_var($this->request['data'][$main_key], FILTER_SANITIZE_NUMBER_INT)
            : (
            isset($this->request['data']['id']) ? filter_var($this->request['data']['id'], FILTER_SANITIZE_NUMBER_INT) : null
            );

        /* if actin is edit update or delete, and id wasn't informed */
        if (in_array($this->request['action'], ["edit", "update", "delete"]) && empty($model_id)) {
            $this->message->error("Não informado um ID válido!");
            return false;
        }

        /* if model id was informed, try to load model */
        if ($model_id) {
            $loadedModel = $this->loadedModel->findById($model_id);
            $this->loadedModel = !empty($loadedModel) ? $loadedModel : null;
            if (!$this->loadedModel) {
                $this->message->error("Não foi possível carregar o registro selecionado");
                return false;
            }
        }
        return true;
    }

    /**
     *
     */
    protected function setControllerPermissions()
    {
        $this->mergePermissions();

        $this->basePermissionName = $this->basePermissionName ??
            explode("\\", get_class($this))[count(explode("\\", get_class($this))) - 1];
        foreach ($this->controllerPermissions as $permission => $name) {
            $this->controllerPermissions[$permission] = "{$this->basePermissionName} {$name}";
        }
    }

    /**
     *
     */
    protected function mergePermissions(): void
    {
        $defaultPermissions = ["list" => "list", "create" => "create", "store" => "create", "edit" => "update", "update" => "update", "delete" => "delete"];
        $systemPermissions = defined('CHAMPS_GLOBAL_PERMISSIONS') ? CHAMPS_GLOBAL_PERMISSIONS : [];
        $controllerPermissions = $this->controllerPermissions;
        foreach ($controllerPermissions as $key => $item) {
            unset($systemPermissions[$key]);
        }

        $this->controllerPermissions = !empty(array_merge($controllerPermissions, $systemPermissions))
            ? array_merge($controllerPermissions, $systemPermissions)
            : $defaultPermissions;
    }

    /**
     * @param string $permission
     */
    protected function checkPermission(string $permission): void
    {
        if (!user()) {
            $this->message->error("Para acessar é preciso logar-se")->flash();
            redirect($this->router->route("login.form"));
        }

        if (!isset($this->controllerPermissions[$permission])) {
            if (isXmlHttpRequest()) {
                echo json_encode(["redirect" => $this->router->route("error", ["errcode" => 'forbidden'])]);
                die();
            }

            redirect($this->router->route("error", ["errcode" => 'forbidden']));
        }

        if (!hasPermission($this->controllerPermissions[$permission])) {
            if (isXmlHttpRequest()) {
                echo json_encode(["redirect" => $this->router->route("error", ["errcode" => 'forbidden'])]);
                die();
            }
            redirect($this->router->route("error", ["errcode" => 'forbidden']));
        }
    }

    /**
     * @return string
     */
    protected function filterRequestAction(): string
    {
        return isset($this->request['action']) ? $this->request['action'] : 'list';
    }

}