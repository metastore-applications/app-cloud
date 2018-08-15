<?php use MetaStore\App\Kernel\View; ?>

<?php View::get( 'header', '_common' ); ?>

	<section class="hero is-success is-fullheight">
		<div class="hero-body">
			<div class="container">
				<h1 class="title is-1">Отправлено!</h1>
				<div class="content box">
					<p>
						Ваше сообщение успешно отправлено!
					</p>
				</div>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>