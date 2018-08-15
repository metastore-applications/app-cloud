<?php

use MetaStore\App\Kernel\{View, MetaCR};
use MetaStore\App\Cloud\Config;

?>

<?php View::get( 'header', '_common' ); ?>

	<!-- section: main -->
	<section id="section-main" class="hero is-fullheight">
		<div class="hero-body">
			<div class="container">
				<div class="content box">
					<div class="level">
						<div class="level-left">
							<div class="level-item">
								<h1 class="title">
									Меню
								</h1>
							</div>
						</div>
						<div class="level-right">
							<div class="level-item is-size-3">
								<i class="far fa-compass"></i>
							</div>
						</div>
					</div>

					<?php if ( Config\General::getService( 'ticket' )['enable'] ): ?>
						<div class="media">
							<figure class="media-left">
								<p class="image is-64x64"><span class="fas fa-file-alt"></span></p>
							</figure>
							<div class="media-content">
								<div class="content">
									<h4><a href="?get=form.ticket.create">Заявка на загрузку файла</a></h4>
									<p>
										Создать заявку на загрузку файла.
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( Config\General::getService( 'upload' )['enable'] ): ?>
						<div class="media">
							<figure class="media-left">
								<p class="image is-64x64"><span class="fas fa-upload"></span></p>
							</figure>
							<div class="media-content">
								<div class="content">
									<h4><a href="?get=form.file.upload">Загрузить файл</a></h4>
									<p>
										Загрузить файл при помощи удобной формы.
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( Config\General::getService( 'download' )['enable'] ): ?>
						<div class="media">
							<figure class="media-left">
								<p class="image is-64x64"><span class="fas fa-download"></span></p>
							</figure>
							<div class="media-content">
								<div class="content">
									<h4><a href="?get=form.file.download">Скачать файл</a></h4>
									<p>
										Скачать файл при помощи удобной формы.
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="has-text-right">
						<?php echo MetaCR::getCR(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- / section: main -->

<?php View::get( 'footer', '_common' ); ?>