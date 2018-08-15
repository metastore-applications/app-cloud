<?php use MetaStore\App\Kernel\View; ?>

<?php View::get( 'header', '_common' ); ?>

	<section class="hero is-warning is-fullheight">
		<div class="hero-body">
			<div class="container">
				<h1 class="title is-1">Внимание!</h1>
				<div class="content box">
					<p>Во время отправки сообщения произошла ошибка. Просьба обратиться к администратору сервиса.</p>
				</div>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>