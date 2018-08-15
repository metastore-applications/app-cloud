<?php use MetaStore\App\Kernel\View; ?>

<?php View::get( 'header', '_common' ); ?>

	<section class="hero is-warning is-fullheight">
		<div class="hero-body">
			<div class="container">
				<h1 class="title is-1">Внимание!</h1>
				<div class="content box">
					<p>
						Вы указали недопустимый адрес e-mail, либо этот адрес заблокирован.
					</p>
				</div>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>