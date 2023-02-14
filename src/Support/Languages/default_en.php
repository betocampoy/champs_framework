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

    /* forbidden page */
    "forbidden_page_title" => "Unauthorized access :/",
    "forbidden_page_message" => "Sorry, but but you don't have access to this page :/",
    "forbidden_page_button_caption" => "Go Back!",
    /* error page */
    "error_page_title" => "Ooops. Conteúdo indispinível :/",
    "error_page_message" => "Sorry, but the page you accessed doesn't exists or has been removed :/",
    "error_page_button_caption" => "Continue navegando!",
    /* maintenance page */
//    "maintenance_page_title" => "Sorry. We are in maintenance :/",
//    "maintenance_page_message" => "We'll be back soon! For now we are working to improve our system :P",
    /* maintenance page */
    "problems_page_title" => "We are facing problems :/",
    "problems_page_message" => "Something seems to be wrong, but our team is already working on it :)",
    "problems_page_send_email" => "Send e-mail to support",
    /* optin confirmation page */
//    "optin_confirm_page_title" => "Almost there! Confirm your registration.",
//    "optin_confirm_page_desc" => "We sent a confirmation link in you e-mail. Access and follow the instructions to validated your registration",
    /* optin welcome page */
//    "optin_welcome_page_title" => "Congrats, your registration was validated :)",
//    "optin_welcome_page_desc" => "Welcome, we are so happy theat you join us!",
//    "optin_welcome_page_link_title" => "Login now!",

    /* Mensagens do processo de autenticação */
    "login_form_title" => "Login in :site",
    "login_welcome" => "Welcome back :user !",
    "login_mandatory_data" => "Inform the mandatory fields to execute the login!",
    "login_user_not_registered" => "Your user is not registered!",
    "login_user_disabled" => "The user is disabled!",
    "login_user_not_validated" => "The user not validated yet!",
    "login_pos_operation" => "Something went wrong in pos login operations!",
    "reset_form_title" => "Create a new password of :site",
    "reset_password_confirm" => "Inform and confirm your password to continue!",
    "reset_password_validation_code_invalid" => "Validation code is invalid!",
    "reset_password_success" => "Password changed successfully",
    "attempts_exceeded" => "You've exceeded the attempts limit, try again in :minutes minutes",
    "forget_form_title" => "Recover password of :site",
    "forget_mandatory_data" => "Inform your email to recover your password!",
    "forget_repeat" => "Ooops! You have already tried to recover this e-mail before!",
    "forget_email_sent" => "Access you e-mail to recover register a new password!",
    /* optin messages */
    "optin_register_form_title" => "Register in :site",
    "optin_confirm_form_title" => "Confirm you registration in :site",
    "optin_welcome_form_title" => "Welcome to :site",
    "optin_register_mandatory_data" => "Inform your data to register you user!",
    "optin_register_email_exists" => "This e-mail is already in use!",
    "optin_register_success" => "Your user was registered, check the e-mail [:email] to validate",
    "optin_register_invalid_pass" => "A valid password must have between :min and :max digits",
    /* Authentication with Facebook */
    "facebook_fail" => "The Facebook authentication fail [code: :code]",
    "facebook_linked" => "The logged user was linked to Facebook User :facebook_user",

    "password_invalid" => "The password informed is not valid!",
    "password_incorrect" => "The password informed is incorrect!",
    "password_confirm_incorrect" => "The password and the password confirmation are different!",

    /*
     * EMAIL
     */
    "mail_not_enabled" => "To perform this operation [:operation], email sending mail must be enabled!",
    "email_check_data" => "Fail to send e-mail, check your data!",
    "email_invalid_address" => "The :email e-mail is invalid!",
    "email_fail" => "Fail to send the e-mail!",
    // queue
    "email_queue_fail" => "Fail to save into the queue!",

    /*
     * NAVIGATION
     */

    /*
     * CONTROLLER
     */
    "controller_is_protected" => "The resource is protected, user must be logged!",
    "controller_access_unauthorized" => "You don't have access to this resource!",
    "controller_invalid_request" => "Request is invalid!",
    "controller_csrf" => "CSRF token invalid!",
    "controller_modal_id" => "The ID wasn't informed!",
    "controller_modal_record" => "The record was not found!",

    /*
     * MODEL
     */

    "required_field_missing" => "The :field is required!",
    "model_persist_fail" => "Fail to save the :model in database!",
    "registry_not_found_in_model" => "The :model does not exists!",
    "registers_not_found_in_model" => "None records were found!",
    "table_not_exists" => "The :table doesn't exists in database!",
]);