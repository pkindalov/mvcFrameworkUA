const genSpreadsheatBtn = document.getElementById('writeDataOnSpreadsheat');
genSpreadsheatBtn.onclick = () => writeDataOnSpreadsheat();

function writeDataOnSpreadsheat() {
	showSpinner();
	const total_games = document.getElementById('total_games').innerText;
	const wins_count = document.getElementById('wins_count').innerText;
	const my_efficiency_percent = document.getElementById('my_efficiency_percent').innerText;
	const avg_coef = document.getElementById('avg_coef').innerText;
	const success_efficienty_percent = document.getElementById('success_efficienty_percent').innerText;
	const finances = document.getElementById('finances').innerText;
	const my_efficiency_label = document.getElementById('my_efficiency_label').innerText;
	const long_streak_text = document.getElementById('long_streak_text').innerText;
	const longest_streak_count = document.getElementById('longest_streak_count').innerText;
	const strategy_id = document.getElementById('strategy_id').innerText;
	const date_from = document.getElementById('date_from').value;
	const date_to = document.getElementById('date_to').value;
	const endpoint = 'genSpreadsheet';

	const dataObj = {
		total_games: total_games,
		wins_count: wins_count,
		my_efficiency_percent: my_efficiency_percent,
		avg_coef: avg_coef,
		success_efficienty_percent: success_efficienty_percent,
		finances: finances,
		my_efficiency_label: my_efficiency_label,
		long_streak_text: long_streak_text,
		longest_streak_count: longest_streak_count,
		strategy_id: strategy_id,
		date_from: date_from,
		date_to: date_to
	};

	data = validateData(dataObj);
	sendData(data, endpoint);
}

function sendData(data, endpoint) {
	const url = baseUrl + '/bets/' + endpoint;
	const formData = createFormData(data);
	try {
		const request = new XMLHttpRequest();
		request.open('POST', url);
		request.responseType = 'blob';
		request.onload = (event) => {
            console.log(request.getResponseHeader('Content-Disposition'));
			const blob = request.response;
			const fileName = request.getResponseHeader('Content-Disposition'); //if you have the fileName header available
			const link = document.createElement('a');
			link.href = window.URL.createObjectURL(blob);
			link.download = fileName;
			link.click();
            hideSpinner();
			notifyMe('File created successful', 'bg-success');
		};

		request.send(formData);
	} catch (ex) {
		notifyMe(ex.message, 'bg-danger');
	}
}
