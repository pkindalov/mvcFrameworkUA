let areUsersShown = true;
let usersLoaded = false;
document.addEventListener('DOMContentLoaded', domLoaded);
//fields and containers declarations
const adminUsersContainer = document.getElementById('admin_users_container');

//buttons
const showHideAdminUsersContBtn = document.getElementById('showHideAllAdmins');

function domLoaded() {
	//hide users container in beginning
	hideContainer(adminUsersContainer);

	showHideAdminUsersContBtn.addEventListener('click', showHideUsers);
}

addEventListener('unload', () => {
	document.removeEventListener('DOMContentLoaded', domLoaded);
	showHideAdminUsersContBtn.removeEventListener('click', showHideUsers);
});

//helper functions to domLoaded here
const hideContainer = (container) => (container.style.visibility = 'hidden');
const showContainer = (container) => (container.style.visibility = 'inherit');
const confirmBeforeDelete = (msg) => (confirm(msg) ? true : false);

function showHideUsers() {
	areUsersShown = !areUsersShown;
	if (areUsersShown) {
		hideContainer(adminUsersContainer);
		return;
	}
	loadAdminUsers(adminUsersContainer);
	showContainer(adminUsersContainer);
}

function loadAdminUsers(container) {
	if (usersLoaded) return;
	const url = baseURL + '/users/getAllAdminUsers';
	const xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			const data = JSON.parse(this.responseText);
			if (data.length < 1) {
                const noUsersInfo = document.createElement('h4');
                noUsersInfo.innerText = 'No admin users found';
                container.innerHTML = '';
				container.appendChild(noUsersInfo);
				return;
			}
			data.forEach((user, index) => {
				// console.log(user);
				const link = document.createElement('a');
				// const linkHref = baseURL + '/users/showProfile/' + user.id;
				const removeAdminLink = document.createElement('a');
				removeAdminLink.innerText = 'Remove Admin ' + user.name;
				removeAdminLink.classList.add('btn', 'btn-danger');
				removeAdminLink.onclick = () =>
					confirmBeforeDelete('Are you sure remove admin ' + user.name + ' with id ' + user.id);
				const removeAdminHref = baseURL + '/users/removeAdmin/' + user.id;
				removeAdminLink.setAttribute('href', removeAdminHref);
				link.innerText = user.name + ' - ' + user.email;
				// link.setAttribute('href', linkHref);
				link.classList.add('list-group-item', 'list-group-item-action');
				if (index === 0) link.classList.add('active');
				container.appendChild(link);
				container.appendChild(removeAdminLink);
			});
			usersLoaded = true;
		}
	};

	xhttp.open('GET', url, true);
	xhttp.send();
}
