document.addEventListener('DOMContentLoaded', function() {
	const emailField = document.getElementsByName('email')[0];
	const passwordField = document.getElementsByName('password')[0];
	const loginBtn = document.getElementsByName('loginBtn')[0];
	const minTextFieldSymbols = 2;
	const passMinSymbolsCount = 6;
	const fieldsStatus = {
		email: false,
		password: false
	};
	loginBtn.disabled = true;
	emailField.addEventListener('keyup', validateEmailField);
	emailField.addEventListener('blur', validateEmailField);
	passwordField.addEventListener('keyup', validatePasswordField);
	passwordField.addEventListener('blur', validatePasswordField);

	function validateEmailField() {
		const inputValue = this.value;
		const inputName = this.name;
		if (!inputValue || inputValue.length < minTextFieldSymbols || !emailValidator(inputValue)) {
			markInvalid({ fieldName: inputName, msg: 'Invalid email', spanErrId: 'email_span_err' });
			return;
		}
		markValid({ fieldName: inputName, spanErrId: 'email_span_err' });
		formValidator();
	}

	function emailValidator(email) {
		const re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
		return re.test(String(email).toLowerCase());
	}

	function validatePasswordField() {
		const inputValue = this.value;
		const inputName = this.name;
		if (!inputValue || inputValue.length < passMinSymbolsCount) {
			markInvalid({
				fieldName: inputName,
				msg: `Invalid password. At least ${passMinSymbolsCount}  symbols`,
				spanErrId: 'password_span_err'
			});
			return;
		}
		markValid({ fieldName: inputName, spanErrId: 'password_span_err' });
		formValidator();
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
		disableLoginBtn();
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
		enableLoginBtn();
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

	function enableLoginBtn() {
		loginBtn.disabled = false;
	}

	function disableLoginBtn() {
		loginBtn.disabled = true;
	}

	function formValidator() {
		if (isFormValid()) {
			enableLoginBtn();
			return;
		}
		disableLoginBtn();
	}

	addEventListener('unload', (event) => {
		emailField.removeEventListener('keyup', validateEmailField);
		emailField.removeEventListener('blur', validateEmailField);
		passwordField.removeEventListener('keyup', validatePasswordField);
		passwordField.removeEventListener('blur', validatePasswordField);
	});
});
