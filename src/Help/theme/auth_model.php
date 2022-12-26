<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Authentication Database Models</h1>
    <article class="body-item">
        <h2>Description</h2>
        <p>To activate the AUTH infrastructure, create all the tables bellow in database.</p>

    </article>
    <article class="body-item">
        <h3>auth_users</h3>
        <p>Application's users.</p>
        <code>
            CREATE TABLE `auth_users` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL DEFAULT '',
            `last_name` VARCHAR(255) NOT NULL DEFAULT '',
            `email` varchar(255) DEFAULT NULL,
            `document` varchar(11) DEFAULT NULL,
            `facebook_id` varchar(255) DEFAULT NULL,
            `mobile` varchar(11) DEFAULT NULL,
            `password` varchar(255) DEFAULT '',
            `access_level_id` int(11) NOT NULL DEFAULT 3,
            `forget` varchar(255) DEFAULT NULL,
            `genre` varchar(10) DEFAULT NULL,
            `datebirth` date DEFAULT NULL,
            `photo` varchar(255) DEFAULT NULL,
            `active` SMALLINT(1) NULL DEFAULT 0,
            `status` varchar(50) NOT NULL DEFAULT 'registered' COMMENT 'registered, confirmed',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `created_by` varchar(255) DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
            `updated_by` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            UNIQUE KEY `cpf` (`cpf`),
            UNIQUE KEY `mobile` (`mobile`),
            UNIQUE KEY `facebook_id` (`facebook_id`),
            FULLTEXT KEY `full_text` (`name`,`email`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
        </code>
        <p><strong>By default, the CHAMPSframeworks uses email and password to perform authentication, but you can change the auth key
                defining <code>CHAMPS_AUTH_FIELD_KEY</code> as <code>cpf</code> or <code>mobile</code></strong></p>
        <p>To activate Facebook login, <a href="/champs-docs/auth_facebook">read documentation</a></p>
        <p>To create the initial Authenticatuion database data and the first ADMIN User, access the route /auth_initial_data/user@domain.com/user_password</p>
    </article>

    <article class="body-item">
        <h3>auth_access_levels</h3>
        <p>There is tree user leves Admin, Operator, Client</p>
        <code>
            CREATE TABLE `auth_access_levels` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            INSERT INTO `auth_access_levels` (`id`, `name`) VALUES
            (1, 'administrator'),
            (2, 'operator'),
            (3, 'client');
        </code>

    </article>

    <article class="body-item">
        <h3>auth_permissions</h3>
        <p>Permission is the lowest granularity level used to validate the authentication. They will be grouped as roles and users,
        but at the end, the validation will always be performed by permission</p>
        <code>
            CREATE TABLE `auth_permissions` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `name_unique` (`id`),
            FULLTEXT KEY `name` (`name`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4
        </code>
    </article>

    <article class="body-item">
        <h3>auth_roles</h3>
        <p>Roles is used to group permissions (auth_role_has_permissions). Roles will be assigned to users to simplify permissions managing</p>
        <code>
            CREATE TABLE `auth_roles`(
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `access_level_id` BIGINT(20) UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            PRIMARY KEY(`id`),
            UNIQUE KEY `name_unique` (`id`),
            FULLTEXT KEY `name`(`name`)
            ) ENGINE = INNODB AUTO_INCREMENT=0 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
            INSERT INTO `auth_roles` (`id`, `access_level_id`, `name`) VALUES
            (1, 1, 'Admin Master'),
            (2, 2, 'Operador Master'),
            (3, 3, 'Usuario Master');
        </code>

    </article>

    <article class="body-item">
        <h3>auth_role_has_permissions</h3>
        <p>Relationship between roles and permission tables</p>
        <code>
            CREATE TABLE `auth_role_has_permissions` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `role_id` bigint(20) unsigned NOT NULL,
            `permission_id` bigint(20) unsigned NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `role_id` (`role_id`,`permission_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        </code>
    </article>

    <article class="body-item">
        <h3>auth_user_has_roles</h3>
        <p>Relationship between users and roles tables</p>
        <code>
            CREATE TABLE `auth_user_has_roles` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) unsigned NOT NULL,
            `role_id` bigint(20) unsigned NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        </code>
    </article>
</article>
