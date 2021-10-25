let toggle = false;
let toggleBtn = document.getElementById('toggleBtn');
let toggleCont = document.getElementById('toggleCont');
if (toggleBtn) {
	toggleBtn.onclick = function() {
		toggle = !toggle;
		if (toggle) {
			toggleCont.style.display = 'none';
			return;
		}
		toggleCont.style.display = 'block';
	};
}
