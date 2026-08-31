let time = 30;
document.getElementById("index-button").onclick = () => {
    time = 30;
    let id001 = setInterval(function () {
        if (time >= 1) {
            time -= 1;
        }
        else {
            window.location.href = "public/login.html";
            time = 30;
            clearInterval(id001);
        }
    }, 10);
} // Brings you to the log-in page after a small delay.