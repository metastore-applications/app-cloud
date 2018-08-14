<?php use MetaStore\App\Kernel\View; ?>

<?php View::get( 'header', '_common' ); ?>

<!-- section: main -->
<section id="section-main" class="hero is-fullheight">
	<div class="hero-body">
		<div class="container">
			<div class="content box">
				<div class="level">
					<div class="level-left">
						<div class="level-item">
							<h1 class="title">Меню</h1>
						</div>
					</div>
					<div class="level-right">
						<div class="level-item is-size-3">
							<i class="far fa-compass"></i>
						</div>
					</div>
				</div>
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
			</div>
		</div>
	</div>
</section>
<!-- / section: main -->

<?php View::get( 'footer', '_common' ); ?>