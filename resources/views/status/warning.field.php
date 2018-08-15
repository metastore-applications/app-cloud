<?php use MetaStore\App\Kernel\View; ?>

<?php View::get( 'header', '_common' ); ?>

	<section class="hero is-warning is-fullheight">
		<div class="hero-body">
			<div class="container">
				<h1 class="title is-1">Внимание!</h1>
				<div class="content box">
					<p>
						Вы не некорректно заполнили необходимые поля.
					</p>
					<ul>
						<?php
						if ( empty( $_POST['fileLocation'] ) ) {
							echo '<li>Адрес файла.</li>';
						}
						if ( empty( $_POST['fileDestination'] ) ) {
							echo '<li>Место назначения.</li>';
						}
						if ( empty( $_POST['fileDescription'] ) ) {
							echo '<li>Краткое описание отправляемой работы.</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>