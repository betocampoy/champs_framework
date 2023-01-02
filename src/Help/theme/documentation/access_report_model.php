<?php
$v->layout("_theme")
?>

<div class="card mb-3 text-center">
    <div class="card-header">
        <h5>Access Reports Database Models</h5>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Table name: <strong>report_access</strong>
    </div>
    <div class="card-body">
        <p class="card-text">This table is used to log access history.</p>
<card class="card border-secondary m-3">
        <pre>
        <code>
            CREATE TABLE `report_access` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `users` int(11) NOT NULL DEFAULT 1,
            `views` int(11) NOT NULL DEFAULT 1,
            `pages` int(11) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
        </code>
            </pre>
</card>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Table name: <strong>report_online</strong>
    </div>
    <div class="card-body">
        <p class="card-text">This table is used to log the online users.</p>
<card class="card border-secondary m-3">
        <pre>
        <code>
            CREATE TABLE `report_online` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(11) unsigned DEFAULT NULL,
            `ip` varchar(50) NOT NULL DEFAULT '',
            `url` varchar(255) NOT NULL DEFAULT '',
            `agent` varchar(255) NOT NULL DEFAULT '',
            `pages` int(11) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
        </code>
            </pre>
</card>
    </div>
</div>
