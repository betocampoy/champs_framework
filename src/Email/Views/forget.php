<?php
/** @var \BetoCampoy\ChampsFramework\Email\EmailView $v */
/** @var string $name */
/** @var string $forget_link */
$v->layout("_theme", ["title" => "Recupere/troque sua senha para acessar o ".CHAMPS_SITE_NAME]); ?>

<h2><?= $name; ?>,</h2>
<p>Você está recebendo esse e-mail pois acessou o <?=CHAMPS_SITE_NAME?> e solicitou a troca ou a recuperação de sua senha de acesso.</p>
<p>Agora, é só clicar no botão abaixo e cadastrar/alterar sua senha, e voltar a utilizar o <strong><?=CHAMPS_SITE_NAME?></strong>.</p>
<p><a class="btn btn-primary" title='Recuperar Senha' href='<?= $forget_link; ?>'>CLIQUE AQUI PARA ALTERAR SENHA</a></p>
<p><b>IMPORTANTE:</b> Se não foi você quem solicitou a recuperação da senha, ignore o e-mail. Seus dados permanecem seguros.</p>