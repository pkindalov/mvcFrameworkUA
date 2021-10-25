document.addEventListener('DOMContentLoaded', () => {
	try {
		//fields
		const oldPassword = document.getElementsByName('oldPassword')[0];
		const newPasswordField = document.getElementsByName('newPassword')[0];
		const confirmPasswordField = document.getElementsByName('confirmNewPassword')[0];
		const emailField = document.getElementsByName('email')[0];
		const changed_email = document.getElementsByName('changed_email')[0];
		const userAccPasswordField = document.getElementsByName('user_acc_password')[0];
		const fileInput = document.getElementsByClassName('custom-file-input')[0];
		const previewImg = document.getElementsByName('current_user_profile_img')[0];
		const currentProfileImg = document.getElementsByName('current_user_profile_img')[0];
		const make_admin_select = document.getElementsByName('make_admin')[0];
		const remove_admin_select = document.getElementsByName('remove_admin')[0];

		//buttons
		const changePassBtn = document.getElementsByName('changePwdBtn')[0];
		const resetBtn = document.getElementsByName('resetBtn')[0];
		const remAccBtn = document.getElementsByName('remAccBtn')[0];
		const changeEmailBtn = document.getElementsByName('changeEmailBtn')[0];
		const uploadBtn = document.getElementsByName('uploadBtn')[0];
		const makeAdminBtn = document.getElementsByName('makeAdminBtn')[0];
		const removeAdminBtn = document.getElementsByName('removeAdminBtn')[0];

		const passMinSymbolsCount = 6;
		const fieldsStatus = {
			oldPassword: false,
			newPassword: false,
			confirmNewPassword: false
		};

		//disabling buttons
		if (uploadBtn) uploadBtn.disabled = true;
		// if (!fileInput || !uploadBtn) return;
		if (changePassBtn) changePassBtn.disabled = true;
		if (resetBtn) resetBtn.disabled = true;
		if (remAccBtn) remAccBtn.disabled = true;
		if (changeEmailBtn) changeEmailBtn.disabled = true;
		if (makeAdminBtn) makeAdminBtn.disabled = true;
		if (removeAdminBtn) removeAdminBtn.disabled = true;

		//add events to fields
		fileInput.addEventListener('change', showFileName);
		userAccPasswordField.addEventListener('keyup', validatePassRemAccount);
		userAccPasswordField.addEventListener('blur', validatePassRemAccount);
		userAccPasswordField.data = { spanId: 'user_acc_password_span_err' };
		oldPassword.addEventListener('keyup', validatePasswordField);
		oldPassword.addEventListener('blur', validatePasswordField);
		oldPassword.data = { spanId: 'old_password_span_err' };
		emailField.addEventListener('keyup', validateEmailField);
		emailField.addEventListener('blur', validateEmailField);
		emailField.data = { spanId: 'email_span_err', btn: resetBtn };
		changed_email.addEventListener('keyup', validateEmailField);
		changed_email.addEventListener('blur', validateEmailField);
		changed_email.data = { spanId: 'changed_email_span_err', btn: changeEmailBtn };
		newPasswordField.addEventListener('keyup', validatePasswordField);
		newPasswordField.addEventListener('blur', validatePasswordField);
		newPasswordField.data = { spanId: 'new_password_span_err' };
		confirmPasswordField.addEventListener('keyup', validateConfPasswordField);
		confirmPasswordField.addEventListener('blur', validateConfPasswordField);

		if(make_admin_select){
			make_admin_select.addEventListener('change', validateUsers);
			make_admin_select.addEventListener('blur', validateUsers);
			make_admin_select.data = { spanId: 'make_admin_err', btn: makeAdminBtn };
		}
		
		if(remove_admin_select){
			remove_admin_select.addEventListener('change', validateUsers);
			remove_admin_select.addEventListener('blur', validateUsers);
			remove_admin_select.data = { spanId: 'remove_admin_err', btn: removeAdminBtn };
		}

		if (currentProfileImg) {
			currentProfileImg.addEventListener('click', changeCurrentPhotoPrevImg);
		}

		//helper functions about validations
		function showFileName() {
			const rawValue = this.value;
			const fileName = rawValue.split('\\').pop();
			const imgLabelCont = document.getElementsByClassName('custom-file-label')[0];
			const [ file ] = this.files;
			if (file && previewImg) {
				previewImg.src = URL.createObjectURL(file);
				previewImg.style.border = '6px solid #0069D9';
			}
			if (!imgLabelCont) return;
			if (!imgLabelCont.classList.contains('selected')) {
				imgLabelCont.classList.add('selected');
			}
			imgLabelCont.innerHTML = fileName;
			uploadBtn.disabled = false;
		}

		function changeCurrentPhotoPrevImg() {
			fileInput.click();
		}

		function validatePasswordField(event) {
			const { spanId } = event.currentTarget.data;
			const inputValue = this.value;
			const inputName = this.name;
			if (!inputValue || inputValue.length < passMinSymbolsCount) {
				markInvalid({
					fieldName: inputName,
					msg: `Invalid password. At least ${passMinSymbolsCount}  symbols`,
					// spanErrId: 'new_password_span_err'
					spanErrId: spanId
				});
				return;
			}
			markValid({ fieldName: inputName, spanErrId: spanId });
			formValidator();
		}

		function validateConfPasswordField() {
			const inputValue = this.value;
			const inputName = this.name;
			if (!inputValue || inputValue.length < passMinSymbolsCount || inputValue !== confirmPasswordField.value) {
				markInvalid({
					fieldName: inputName,
					msg: `Invalid password. Both passwords don't match or not enought symbols. At least ${passMinSymbolsCount}  symbols`,
					spanErrId: 'confirm_new_password_span_err'
				});
				return;
			}
			markValid({ fieldName: inputName, spanErrId: 'confirm_new_password_span_err' });
			formValidator();
		}

		function validateEmailField(event) {
			//event.currentTarget - current element, in this case input
			const { spanId, btn } = event.currentTarget.data;
			const inputValue = this.value;
			const spanEl = document.getElementById(spanId);
			if (emailValidator(inputValue)) {
				btn.disabled = false;
				spanEl.innerText = '';
				event.currentTarget.classList.remove('is-invalid');
				return;
			}

			if (!event.currentTarget.classList.contains('is-invalid')) {
				event.currentTarget.classList.add('is-invalid');
			}
			spanEl.innerText = 'Invalid email';
			btn.disabled = true;
		}

		function validateUsers(event){
			const { spanId, btn } = event.currentTarget.data;
			const selectedUser = this.value;
			const spanEl = document.getElementById(spanId);
			if(selectedUser){
				btn.disabled = false;
				spanEl.innerText = '';
				event.currentTarget.classList.remove('is-invalid');
				return;
			}
			if (!event.currentTarget.classList.contains('is-invalid')) {
				event.currentTarget.classList.add('is-invalid');
				btn.disabled = true;
			}
			spanEl.innerText = 'Invalid or not availabel user';
		}

		function validatePassRemAccount(event) {
			const { spanId } = event.currentTarget.data;
			const inputValue = this.value;
			const spanEl = document.getElementById(spanId);
			if (inputValue) {
				remAccBtn.disabled = false;
				spanEl.innerText = '';
				event.currentTarget.classList.remove('is-invalid');
				return;
			}
			if (!event.currentTarget.classList.contains('is-invalid')) {
				event.currentTarget.classList.add('is-invalid');
				remAccBtn.disabled = true;
			}
			spanEl.innerText = 'Invalid password';
		}

		function markInvalid(data) {
			const { fieldName, msg, spanErrId } = data;
			const field = document.getElementsByName(fieldName)[0];
			if (!field.classList.contains('is-invalid')) {
				field.classList.add('is-invalid');
			}
			const span = document.getElementById(spanErrId);
			span.innerText = msg;
			fieldsStatus[fieldName] = false;
			disableChangePwdBtn();
		}

		function markValid(data) {
			const { fieldName, spanErrId } = data;
			const field = document.getElementsByName(fieldName)[0];
			if (field.classList.contains('is-invalid')) {
				field.classList.remove('is-invalid');
			}
			const span = document.getElementById(spanErrId);
			span.innerText = '';
			fieldsStatus[fieldName] = true;
			enableChangePwdBtn();
		}

		function isFormValid() {
			isValid = true;
			for (let field of Object.keys(fieldsStatus)) {
				if (!fieldsStatus[field]) {
					isValid = false;
					break;
				}
			}
			return isValid;
		}

		function enableChangePwdBtn() {
			changePassBtn.disabled = false;
		}

		function disableChangePwdBtn() {
			changePassBtn.disabled = true;
		}

		function formValidator() {
			if (isFormValid()) {
				enableChangePwdBtn();
				return;
			}
			disableChangePwdBtn();
		}

		function emailValidator(email) {
			const re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
			return re.test(String(email).toLowerCase());
		}

		addEventListener('unload', () => {
			fileInput.removeEventListener('change', showFileName);
			currentProfileImg.removeEventListener('click', changeCurrentPhotoPrevImg);
			oldPassword.removeEventListener('keyup', validatePasswordField);
			oldPassword.removeEventListener('blur', validatePasswordField);
			userAccPasswordField.removeEventListener('keyup', validatePassRemAccount);
			userAccPasswordField.removeEventListener('blur', validatePassRemAccount);
			newPasswordField.removeEventListener('keyup', validatePasswordField);
			newPasswordField.removeEventListener('blur', validatePasswordField);
			confirmPasswordField.removeEventListener('keyup', validateConfPasswordField);
			confirmPasswordField.removeEventListener('blur', validateConfPasswordField);
			emailField.removeEventListener('keyup', validateEmailField);
			emailField.removeEventListener('blur', validateEmailField);
			changed_email.removeEventListener('blur', validateEmailField);
			changed_email.removeEventListener('keyup', validateEmailField);
			if(make_admin_select){
				make_admin_select.removeEventListener('change', validateUsers);
				make_admin_select.removeEventListener('blur', validateUsers);
			}
			if(remove_admin_select){
				remove_admin_select.removeEventListener('change', validateUsers);
				remove_admin_select.removeEventListener('blur', validateUsers);
			}

		});
	} catch (ex) {
		console.log(ex.message);
		notifyMe(ex.message, 'bg-danger');
	}
});

const notifyMe = (msg, notifyClass) => {
	const notBox = document.getElementById('notificationBox');
	notBox.style.visibility = 'block';
	notBox.classList.add(notifyClass);
	notBox.innerText = msg;
};

// Add the following code if you want the name of the file appear on select
// $(".custom-file-input").on("change", function() {
//   var fileName = $(this).val().split("\\").pop();
//   $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
// });
