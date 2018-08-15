<?php use MetaStore\App\Kernel\{Request, View}; ?>

<?php View::get( 'header', '_common' ); ?>

	<section class="hero is-warning is-fullheight">
		<div class="hero-body">
			<div class="container">
				<h1 class="title is-1">
					Внимание!
				</h1>
				<div class="content box">
					<p>
						Вы некорректно заполнили следующие поля:
					</p>
					<ul>
						<?php
						if ( empty( Request::setParam( 'userMailFrom' ) ) ) {
							echo '<li>Адрес e-mail.</li>';
						}
						if ( empty( Request::setParam( 'fileLocation' ) ) ) {
							echo '<li>Адрес файла.</li>';
						}
						if ( empty( Request::setParam( 'fileDestination' ) ) ) {
							echo '<li>Место назначения.</li>';
						}
						if ( empty( Request::setParam( 'fileDescription' ) ) ) {
							echo '<li>Краткое описание отправляемой работы.</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>