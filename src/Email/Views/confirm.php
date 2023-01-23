<?php
/** @var \BetoCampoy\ChampsFramework\Email\EmailView $v */
/** @var string $name */
/** @var string $confirm_link */
$v->layout("_theme", ["title" => "Ative sua conta no ".CHAMPS_SEO_SITE_NAME]); ?>

<h2><strong><?= $name; ?></strong> Seja bem-vindo(a) ao <?=CHAMPS_SEO_SITE_NAME?>. Vamos ativar seu cadastro?</h2>
<p>Você está recebendo essa mensagem, pois seu endereço de e-mail foi cadastrado no <?=CHAMPS_SEO_SITE_NAME?>.</p>
<p>Para finalizar sem cadastro basta clicar no botão abaixo. Você será direcionado para uma página para cadastrar uma de acesso</p>
<p>Importante: Não compartilhe sua senha com ninguém.</p>
<p><a class="btn btn-primary" title='Confirmar Cadastro' href='<?= $confirm_link; ?>'>CLIQUE AQUI PARA CONFIRMAR SEU CADASTRO</a></p>