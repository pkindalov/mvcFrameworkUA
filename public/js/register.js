document.addEventListener('DOMContentLoaded', function() {
	const nameField = document.getElementsByName('name')[0];
	const emailField = document.getElementsByName('email')[0];
	const passwordField = document.getElementsByName('password')[0];
	const confirmPasswordField = document.getElementsByName('confirm_password')[0];
	const regBtn = document.getElementsByName('regBtn')[0];
	const minTextFieldSymbols = 2;
	const passMinSymbolsCount = 6;
	const fieldsStatus = {
		name: false,
		email: false,
		password: false,
		confirm_password: false
	};
	regBtn.disabled = true;

	nameField.addEventListener('keyup', validateTextInputField);
	nameField.addEventListener('blur', validateTextInputField);
	emailField.addEventListener('keyup', validateEmailField);
	emailField.addEventListener('blur', validateEmailField);
	passwordField.addEventListener('keyup', validatePasswordField);
	passwordField.addEventListener('blur', validatePasswordField);
	confirmPasswordField.addEventListener('keyup', validateConfPasswordField);
	confirmPasswordField.addEventListener('blur', validateConfPasswordField);

	function validateTextInputField() {
		const inputValue = this.value;
		const inputName = this.name;
		if (!inputValue || inputValue.length < minTextFieldSymbols) {
			markInvalid({
				fieldName: inputName,
				msg: `Invalid input. At least ${minTextFieldSymbols} symbols`,
				spanErrId: 'name_span_err'
			});
			return;
		}
		markValid({ fieldName: inputName, spanErrId: 'name_span_err' });
		formValidator();
	}

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

	function validateConfPasswordField() {
		const inputValue = this.value;
		const inputName = this.name;
		if (!inputValue || inputValue.length < passMinSymbolsCount || inputValue !== confirmPasswordField.value) {
			markInvalid({
				fieldName: inputName,
				msg: `Invalid password. Both passwords don't match or not enought symbols. At least ${passMinSymbolsCount}  symbols`,
				spanErrId: 'confirm_password_span_err'
			});
			return;
		}
		markValid({ fieldName: inputName, spanErrId: 'confirm_password_span_err' });
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
		disableRegBtn();
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
		enableRegBtn();
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

	function enableRegBtn() {
		regBtn.disabled = false;
	}

	function disableRegBtn() {
		regBtn.disabled = true;
	}

	function formValidator() {
		if (isFormValid()) {
			enableRegBtn();
			return;
		}
		disableRegBtn();
	}

	addEventListener('unload', (event) => {
		nameField.removeEventListener('keyup', validateTextInputField);
		nameField.removeEventListener('blur', validateTextInputField);
		emailField.removeEventListener('keyup', validateEmailField);
		emailField.removeEventListener('blur', validateEmailField);
		passwordField.removeEventListener('keyup', validatePasswordField);
		passwordField.removeEventListener('blur', validatePasswordField);
		confirmPasswordField.removeEventListener('keyup', validateConfPasswordField);
		confirmPasswordField.removeEventListener('blur', validateConfPasswordField);
	});
});
