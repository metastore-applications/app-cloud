/**
 *
 * @param data
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_buttonStatus(data) {
	const log = $('#buttonStatus');
	log.find('span').html(data);
}

/**
 *
 * @param data
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_uploadPercent(data) {
	const progress = $('.uploadProgress');
	const percent = $('#uploadPercent');
	progress.show().find(percent).html(data);
}

/**
 *
 * @param data
 * @param removeClass
 * @param addClass
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_messageLog(data, removeClass, addClass) {
	const log = $('#messageLog');
	log.removeClass(removeClass).addClass(addClass).show().find('.message-body').html(data);
	log.click(function () {
		$(this).hide();
	});
}

/**
 *
 * @param xhr
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_uploadProgress(xhr) {
	const file = $('input[type=file]').val();

	xhr.upload.addEventListener('progress', function (event) {
		if (file && event.lengthComputable) {
			let percentComplete;
			percentComplete = event.loaded / event.total;

			percentComplete = parseInt(percentComplete * 100);
			extJS_uploadPercent(percentComplete);
			extJS_buttonStatus('<i class="fas fa-hourglass-half"></i>');

			if (percentComplete === 100) {
				//extJS_buttonStatus('<i class="fas fa-check"></i>');
			}
		}
	}, false);
}

/**
 *
 * @param data
 * @returns {*}
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_parseJSON(data) {
	let out;
	if (data.length > 0) {
		out = $.parseJSON(data);
	}
	return out;
}

/**
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_resetInput() {
	$('form').find('.ext-reset').val('');
}

/**
 *
 * @param form
 * @param button
 * @param url
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_sendForm(form, button, url) {
	form.on('submit', (function (e) {
		e.preventDefault();
		button.prop('disabled', true);

		$.ajax({
			url: url,
			type: 'POST',
			data: new FormData(this),
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function () {
			},
			success: function (data) {
				if (data.length > 0) {
					let resp = extJS_parseJSON(data);
					extJS_messageLog(resp.error.msg, 'is-success', 'is-danger');
					extJS_buttonStatus('<i class="fas fa-exclamation-triangle"></i>');
				} else {
					//form[0].reset();
					extJS_resetInput();
					extJS_messageLog('Done!', 'is-danger', 'is-success');
					extJS_buttonStatus('<i class="fas fa-check"></i>');
				}
				button.prop('disabled', false);
			},
			error: function (e) {
				extJS_messageLog(e);
			},
			xhr: function () {
				let xhr = new window.XMLHttpRequest();
				extJS_uploadProgress(xhr);
				return xhr;
			},
		});
	}));
}

/**
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_sendTicket() {
	const form = $('#formTicket');
	const button = $('#buttonSendTicket');

	extJS_sendForm(form, button, '?get=action.ticket.send');
}

/**
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */
function extJS_uploadFile() {
	const form = $('#formUpload');
	const button = $('#buttonSendUpload');

	extJS_sendForm(form, button, '?get=action.file.upload');
}

/**
 * Loading functions.
 * ---------------------------------------------------------------------------------------------------------------------
 */

$(function () {
	extJS_sendTicket();
	extJS_uploadFile();
});