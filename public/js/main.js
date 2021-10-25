const notificationBox = document.getElementById('notificationBox');
const closeNotifyBox = () => notificationBox.style.visibility = 'hidden';
notificationBox.onclick = () => closeNotifyBox();

(function(){
    setTimeout(() => closeNotifyBox(), 10000);
})();
