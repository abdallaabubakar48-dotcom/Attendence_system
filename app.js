// 1. Sanidi Firebase
const firebaseConfig = {
  apiKey: "AIzaSyDxeh9-9_3y7HuI77jpubKnbU39xcbojs0",
  authDomain: "attendancesystem-ecd0a.firebaseapp.com",
  databaseURL: "https://attendancesystem-ecd0a-default-rtdb.firebaseio.com",
  projectId: "attendancesystem-ecd0a",
  storageBucket: "attendancesystem-ecd0a.firebasestorage.app",
  messagingSenderId: "505261139314",
  appId: "1:505261139314:web:102548d4bc808ad31fa9eb"
};

// Initialize
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

// 2. Function ya kutuma data
function submitAttendance() {
    const name = document.getElementById('name').value;
    const regNo = document.getElementById('regNo').value;
    const btn = document.getElementById('btn');
    const msg = document.getElementById('message');

    if(name === "" || regNo === "") {
        msg.innerText = "Tafadhali jaza nafasi zote!";
        msg.style.color = "red";
        return;
    }

    btn.disabled = true;
    btn.innerText = "Inatuma...";

    // Kutuma kwenye Realtime Database
    db.ref('attendance/').push({
        jina: name,
        namba: regNo,
        muda: new Date().toLocaleString()
    })
    .then(() => {
        msg.innerText = "Imefanikiwa!";
        msg.style.color = "green";
        document.getElementById('name').value = "";
        document.getElementById('regNo').value = "";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    })
    .catch((error) => {
        msg.innerText = "Hitilafu imetokea!";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    });
}
