<?php use MetaStore\App\{Kernel\View, Cloud\System}; ?>

<?php View::get( 'header', '_common' ); ?>

	<!-- section: main -->
	<section id="section-main" class="hero is-fullheight">
		<div class="hero-body">
			<div class="container box">
				<div class="level">
					<div class="level-left">
						<div class="level-item">
							<h1 class="title">
								Загрузка в облако
							</h1>
						</div>
					</div>
					<div class="level-right">
						<div class="level-item is-size-3">
							<i class="fas fa-upload"></i>
						</div>
					</div>
				</div>
				<form id="formUpload" method="post" enctype="multipart/form-data" action="?get=action.file.upload">
					<input id="_metaToken" name="_metaToken" value="<?php echo $_SESSION['_metaToken']; ?>" type="hidden" />
					<!-- file: select -->
					<div class="field">
						<div class="control has-icons-left">
							<div class="file">
								<input id="getFile" name="getFile" class="ext-reset" type="file" accept="<?php echo System::getFormFileAccept(); ?>" required />
							</div>
						</div>
						<p class="help has-text-grey">
							Выберите файл для загрузки.
						</p>
					</div>
					<!-- / file: select -->
					<div class="field is-horizontal">
						<div class="field-body">
							<!-- user: ticket ID -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="ticketID" name="ticketID" class="input ext-reset" value="" placeholder="Укажите Ticket ID..." autocomplete="off" />
									<span class="icon is-small is-left"><i class="fas fa-anchor"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите Ticket ID (если имеется).
								</p>
							</div>
							<!-- / user: ticket ID -->
							<!-- user: email -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userMailTo" name="userMailTo" class="input ext-reset" type="email" value="" placeholder="Укажите адрес электронной почты..." required />
									<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите адрес электронной почты, на который будет отправлена ссылка. Вам придёт дубликат письма.
								</p>
							</div>
							<!-- / user: email -->
						</div>
					</div>
					<!-- info: comment -->
					<div class="field">
						<div class="control">
							<textarea id="userComment" name="userComment" class="textarea ext-reset" placeholder="Введите комментарий (если имеется)..."></textarea>
						</div>
						<p class="help has-text-grey">
							Введите комментарий (если имеется).
						</p>
					</div>
					<!-- file: url type -->
					<div class="field">
						<div class="control">
							<label class="checkbox">
								<input id="urlType" name="urlType" type="checkbox" />
								Создать внутреннюю ссылку
							</label>
						</div>
						<p class="help has-text-grey">
							Установите галочку, если хотите, чтобы сформировалась <strong>внутренняя</strong> ссылка на скачивание файла.<br/>
							Ссылка на скачивание файла будет работать только внутри сети предприятия, а работники не будут расходовать лимит своего трафика.
							Это полезно, если Вы работаете из дома и обмениваетесь файлами с коллегами на работе.
						</p>
					</div>
					<!-- / file: url type -->
					<!-- / info: comment -->
					<div class="field is-horizontal">
						<div class="field-body">
							<!-- captcha: generator -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="_<?php echo $_SESSION['_metaCaptcha'][0] ?>" name="_metaCaptcha_<?php echo $_SESSION['_metaCaptcha'][0] ?>" class="input" value="<?php echo $_SESSION['_metaCaptcha'][1] ?>" readonly />
									<span class="icon is-small is-left"><i class="fas fa-robot"></i></span>
								</div>
								<p class="help has-text-grey">
									Генератор кода.
								</p>
							</div>
							<!-- / captcha: generator -->
							<!-- captcha: value -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="_metaCaptcha" name="_metaCaptcha" class="input ext-reset" autocomplete="off" required />
									<span class="icon is-small is-left"><i class="fas fa-robot"></i></span>
								</div>
								<p class="help has-text-grey">
									Введите число генератора кода.
								</p>
							</div>
							<!-- / captcha: value -->
						</div>
					</div>
					<div class="level">
						<div class="level-left">
							<div class="level-item">
								<!-- file: save time -->
								<div class="field">
									<div class="control has-icons-left">
										<div class="select">
											<select id="fileSaveTime" name="fileSaveTime">
												<option value="days_03" selected>3 дня</option>
												<option value="days_10">10 дней</option>
											</select>
										</div>
										<div class="icon is-small is-left">
											<i class="far fa-clock"></i>
										</div>
									</div>
									<p class="help has-text-grey">
										Выберите количество дней, в течение которых файлы будут хранится в облаке.
									</p>
								</div>
								<!-- / file: save time -->
							</div>
						</div>
						<div class="level-right">
							<div class="level-item">
								<!-- buttons -->
								<div class="field has-addons">
									<div class="control">
										<a id="buttonStatus" class="button is-medium is-static">
											<span class="icon is-small"><i class="fas fa-hand-point-right"></i></span>
										</a>
									</div>
									<div class="control uploadProgress">
										<a class="button is-medium is-static">
											<span id="uploadPercent"></span>
											<span class="icon is-small"><i class="fas fa-percentage"></i></span>
										</a>
									</div>
									<div class="control">
										<button id="buttonSendUpload" class="button is-success is-medium" title="Загрузить">
											<span class="icon is-small"><i class="fas fa-upload"></i></span>
										</button>
									</div>
								</div>
								<!-- / buttons -->
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

<?php View::get( 'footer', '_common' ); ?>