<?php use MetaStore\App\Kernel\{Cookie, View}; ?>

<?php View::get( 'header', '_common' ); ?>

	<!-- section: main -->
	<section id="section-main" class="hero is-fullheight">
		<div class="hero-body">
			<div class="container box">
				<div class="level">
					<div class="level-left">
						<div class="level-item">
							<h1 class="title">
								Заявка на загрузку в облако
							</h1>
						</div>
					</div>
					<div class="level-right">
						<div class="level-item is-size-3">
							<i class="fas fa-file-alt"></i>
						</div>
					</div>
				</div>
				<form id="formTicket" method="post" action="?get=action.ticket.send">
					<input id="_metaToken" name="_metaToken" value="<?php echo $_SESSION['_metaToken']; ?>" type="hidden" />
					<div class="field is-horizontal">
						<div class="field-body">
							<!-- user: last name -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userLastName" name="userLastName" class="input" value="<?php echo Cookie::get( 'userLastName' ) ?>" placeholder="Введите свою фамилию..." required />
									<span class="icon is-small is-left"><i class="fas fa-user"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите свою фамилию.
								</p>
							</div>
							<!-- / user: last name -->
							<!-- user: first name -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userFirstName" name="userFirstName" class="input" value="<?php echo Cookie::get( 'userFirstName' ) ?>" placeholder="Введите своё имя..." required />
									<span class="icon is-small is-left"><i class="fas fa-user"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите своё имя.
								</p>
							</div>
							<!-- / user: first name -->
							<!-- user: middle name -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userMiddleName" name="userMiddleName" class="input" value="<?php echo Cookie::get( 'userMiddleName' ) ?>" placeholder="Введите своё отчество..." required />
									<span class="icon is-small is-left"><i class="fas fa-user"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите своё отчество.
								</p>
							</div>
							<!-- / user: middle name -->
						</div>
					</div>
					<div class="field is-horizontal">
						<div class="field-body">
							<!-- user: email -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userMailFrom" name="userMailFrom" class="input" type="email" value="<?php echo Cookie::get( 'userMailFrom' ) ?>" placeholder="Укажите свой адрес электронной почты..." required />
									<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите свой адрес электронной почты.
								</p>
							</div>
							<!-- / user: email -->
							<!-- user: phone -->
							<div class="field">
								<div class="control is-expanded has-icons-left">
									<input id="userPhone" name="userPhone" class="input" type="tel" value="<?php echo Cookie::get( 'userPhone' ) ?>" placeholder="Укажите свой номер телефона..." required />
									<span class="icon is-small is-left"><i class="fas fa-phone"></i></span>
								</div>
								<p class="help has-text-grey">
									Укажите свой номер телефона.
								</p>
							</div>
							<!-- / user: phone -->
						</div>
					</div>
					<!-- file: location -->
					<div class="field">
						<div class="control has-icons-left is-expanded">
							<input id="fileLocation" name="fileLocation" class="input" placeholder="Введите адрес файла..." required />
							<span class="icon is-small is-left"><i class="fas fa-plane-departure"></i></span>
						</div>
						<p class="help has-text-grey">
							Введите адрес, где находится файл для отправки.
						</p>
					</div>
					<!-- / file: location -->
					<!-- file: destination -->
					<div class="field">
						<div class="control has-icons-left is-expanded">
							<input id="fileDestination" name="fileDestination" class="input" placeholder="Введите место назначения..." required />
							<span class="icon is-small is-left"><i class="fas fa-plane-arrival"></i></span>
						</div>
						<p class="help has-text-grey">
							Введите наименование организации, для которой предназначен файл.
						</p>
					</div>
					<!-- / file: destination -->
					<!-- info: description -->
					<div class="field">
						<div class="control">
							<textarea id="fileDescription" name="fileDescription" class="textarea" placeholder="Введите краткое описание.." required></textarea>
						</div>
						<p class="help has-text-grey">
							Введите краткое описание отправляемой работы.
						</p>
					</div>
					<!-- / info: description -->
					<!-- info: comment -->
					<div class="field">
						<div class="control">
							<textarea id="userComment" name="userComment" class="textarea" placeholder="Введите комментарий (если имеется)..."></textarea>
						</div>
						<p class="help has-text-grey">
							Введите комментарий (если имеется).
						</p>
					</div>
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
									<input id="_metaCaptcha" name="_metaCaptcha" class="input" autocomplete="off" required />
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
											<select name="fileSaveTime">
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
									<div class="control">
										<button id="buttonSendTicket" class="button is-medium is-success">
											<span class="icon is-small"><i class="fas fa-share-square"></i></span>
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
	<!-- / section: main -->

<?php View::get( 'footer', '_common' ); ?>