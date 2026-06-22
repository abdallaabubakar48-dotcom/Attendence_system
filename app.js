// 1. Sanidi Firebase (Kumbuka kuweka config zako hapa)
const firebaseConfig = {
  apiKey: "AIzaSyDxeh9-9_3y7HuI77jpubKnbU39xcbojs0",
  authDomain: "attendancesystem-ecd0a.firebaseapp.com",
  databaseURL: "https://attendancesystem-ecd0a-default-rtdb.firebaseio.com",
  projectId: "attendancesystem-ecd0a",
  storageBucket: "attendancesystem-ecd0a.firebasestorage.app",
  messagingSenderId: "505261139314",
  appId: "1:505261139314:web:102548d4bc808ad31fa9eb"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

// 2. Kazi ya kuhifadhi mahudhurio (Submit Attendance)
function submitAttendance() {
    const nameInput = document.getElementById('name');
    const regNoInput = document.getElementById('regNo');
    const message = document.getElementById('message');
    const btn = document.getElementById('submitBtn');

    const name = nameInput.value;
    const regNo = regNoInput.value;

    if(name === "" || regNo === "") {
        alert("Tafadhali jaza nafasi zote!");
        return;
    }

    btn.disabled = true;
    btn.innerText = "Inatuma...";

    // Tuma data kwenye Firebase
    db.ref('attendance/').push({
        jina: name,
        namba: regNo,
        muda: new Date().toLocaleString()
    })
    .then(() => {
        message.innerText = "Imefanikiwa!";
        message.style.color = "green";
        nameInput.value = "";
        regNoInput.value = "";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    })
    .catch((error) => {
        message.innerText = "Kuna tatizo, jaribu tena.";
        btn.disabled = false;
        btn.innerText = "Hifadhi Mahudhurio";
    });
}

// 3. Kazi ya kusoma data zote (Display Attendance)
// Hii inafanya kazi moja kwa moja bila wewe kugusa kitu
const tableBody = document.getElementById('tableBody');

db.ref('attendance/').on('value', (snapshot) => {
    tableBody.innerHTML = ""; // Safisha jedwali kwanza
    
    // Angalia kila mwanafunzi aliyeko kwenye database
    snapshot.forEach((childSnapshot) => {
        const data = childSnapshot.val();
        
        // Ongeza mstari mpya kwenye jedwali lako
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${data.jina}</td>
            <td>${data.namba}</td>
            <td>${data.muda}</td>
        `;
        tableBody.appendChild(row);
    });
});
