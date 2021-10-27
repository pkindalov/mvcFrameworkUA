//BEFORE USING THIS FUNCTION YOU MUST FIRST INCLUDE  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
//MORE INFO https://developers.google.com/chart/interactive/docs

function printChart(params) {
	try {
		const { package, options, chartContainer, data, type = 'line', button = null } = params;
		if (!package || !options || !chartContainer) {
			throw new Error('package, options or chart container or all are not valid');
		}
		google.charts.load('current', { packages: [ package ] });
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			const processedData = google.visualization.arrayToDataTable(data);
			let chart = '';
			switch (type) {
				case 'line':
					chart = new google.visualization.LineChart(document.getElementById(chartContainer));
					break;
				case 'gauge':
					chart = new google.visualization.Gauge(document.getElementById(chartContainer));
					break;
				case 'pie':
					chart = new google.visualization.PieChart(document.getElementById(chartContainer));
					break;
				default:
					chart = new google.visualization.LineChart(document.getElementById(chartContainer));
					break;
			}
			if (button && chart.getImageURI) {
				google.visualization.events.addListener(chart, 'ready', function() {
					// const url = '<a href="' + chart.getImageURI() + '">Printable version</a>';
					const downloadBtn = document.createElement('button');
					downloadBtn.classList.add('btn', 'btn-primary');
					downloadBtn.innerText = 'Image';
					downloadBtn.onclick = () => showImgForDownload(chart);
					document.getElementById(chartContainer).appendChild(downloadBtn);
				});
			}

			chart.draw(processedData, options);
		}
	} catch (ex) {
		console.log(ex.message);
	}
}

function showImgForDownload(chart) {
	const win = window.open();
	win.document.write(
		'<iframe src="' +
			chart.getImageURI() +
			'" frameborder="0" style="border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;" allowfullscreen></iframe>'
	);
	win.document.close();
}

