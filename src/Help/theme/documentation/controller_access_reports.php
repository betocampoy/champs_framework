<?php
$v->layout("documentation/controller_theme");
?>

<div class="card mb-3 text-center">
    <div class="card-header">
        <h5>History access and Online users Reports</h5>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Overview
    </div>
    <div class="card-body">
        <p class="card-text">The controller can automaticaly log in database the online users and the amount of access by day! 
        All you have to do, is activate the feature!</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Activating the reports
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">You must create 2 tables in database, <a href="<?=url("/champs-docs/report_access_model")?>" target="_blank"></a> for details.</div>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">In the controllers you need log access, set the class attribute <code>reports</code>.</div>
                    all, access, online
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">Follow the steps bellow.</div>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Activating the reports
    </div>
    <div class="card-body">
        <p class="card-text">The controller can automaticaly log in database the online users and the amount of access!</p>
    </div>
</div>


