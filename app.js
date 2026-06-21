// 1. Sanidi Firebase (Weka funguo zako hapa)
const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
    databaseURL: "https://YOUR_PROJECT_ID-default-rtdb.firebaseio.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT_ID.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

// 2. Function ya kuhifadhi data
function submitAttendance() {
    const nameInput = document.getElementById('name');
    const regNoInput = document.getElementById('regNo');
    const message = document.getElementById('message');
    const btn = document.querySelector('button');

    const name = nameInput.value;
    const regNo = regNoInput.value;

    // Angalia kama nafasi zimejazwa
    if(name === "" || regNo === "") {
        message.style.color = "red";
        message.innerText = "Tafadhali jaza nafasi zote!";
        return;
    }

    // Onyesha kuwa inafanya kazi (Loading state)
    btn.disabled = true;
    btn.innerText = "Inatuma...";

    // Tuma data kwenye Firebase
    db.ref('attendance/').push({
        jina: name,
        namba: regNo,
        muda: new Date().toLocaleString()
    })
    .then(() => {
        // Mafanikio
        message.style.color = "green";
        message.innerText = "Mahudhurio yamehifadhiwa!";
        nameInput.value = ""; // Safisha fomu
        regNoInput.value = "";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    })
    .catch((error) => {
        // Hitilafu
        message.style.color = "red";
        message.innerText = "Kuna tatizo, jaribu tena.";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    });
}
