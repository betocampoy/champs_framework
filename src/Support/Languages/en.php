<?php

define("CHAMPS_FRAMEWORK_DEFAULT_MESSAGES", [

    /*
     * Framewark initial operations
     */
    /* documentation */
    "docs_fail_to_load" => "Fail to load the CHAMPSframework page",
    /* initial data creation */
    "init_data_fail_used_not_informed" => "To create the first CHAMPSframework admin user, use the route /auth_initial_data/{email}/{password}. 
            Replace {user_key} by email, cpf or mobile (according framework configuration) and {password} by your password.
            For security purpose, create difficult passwords",
    "init_data_fail_table_not_fount" => "The :table was not found in database. Consult the documentation in route /champs-docs/auth_model for help!",
    "init_data_fail_table_has_data" => "To use this feature, the :table table must be empty!",
    "init_data_fail_level_missing" => "Access Level [:name] must exists in database under id [:id]. Check documentation if necessary!",
    "init_data_fail_role_missing" => "Role [:name] must exists in database under id [:id]. Check documentation if necessary!",
    "init_data_fail_user_creation" => "Fail to created the MASTER ADMIN USER [:name]!",
    "init_data_fail_user_assignee" => "Fail to assignee the user [:name] to role [:role]!",
    "init_data_fail_success" => "The initial data was successfully created!",

    /* Mensagens do processo de autenticação */
    "login_welcome" => "Bem-vondo de volta :user",
    "login_mandatory_data" => "Digite os campos obrigatórios para continuar!",
    "login_user_not_registered" => "O usuario informado não está cadastrado!",
    "login_user_disabled" => "O usuario informado está desativado!",
    "attempts_exceeded" => "Você excedeu o numero de tentativas. Aguarde alguns minutos e tente novamente!",
    /* optin messages */
    "optin_register_mandatory_data" => "Informe seus dados para criar sua conta!",
    "optin_register_success" => "Usuário cadastrado, verifique seu email para validar sua conta!",
    /* Authentication with Facebook */
    "facebook_fail" => "A autenticação com o Facebook falhou!",
    "facebook_linked" => "Seu usuário foi conectado a conta :facebook_user do Facebook!",

]);