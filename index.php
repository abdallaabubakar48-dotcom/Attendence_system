<?php
include "auth.php";
include "db.php";

/* ================= USER ================= */

$user_id = $_SESSION['id'];

$stmt = $conn->prepare("
SELECT * FROM users 
WHERE id=?
");

$stmt->bind_param("i",$user_id);
$stmt->execute();

$userData = $stmt->get_result()->fetch_assoc();

/* ================= TOTALS ================= */

$totalStudents = $conn->query("
SELECT COUNT(*) as total 
FROM students
")->fetch_assoc()['total'];

$totalContributions = $conn->query("
SELECT SUM(amount) as total 
FROM contributions
")->fetch_assoc()['total'] ?? 0;

$totalExpenses = $conn->query("
SELECT SUM(amount) as total 
FROM expenses
")->fetch_assoc()['total'] ?? 0;

$balance = $totalContributions - $totalExpenses;

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Modern Dashboard</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= RESET ================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* ================= BODY ================= */

body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
    display:flex;
}

/* ================= SIDEBAR ================= */

.sidebar{
    width:270px;
    height:100vh;
    background:linear-gradient(180deg,#020617,#0f172a,#111827);
    position:fixed;
    left:0;
    top:0;
    padding:25px;
    overflow:hidden;
    transition:0.4s;
    z-index:1000;
}

.sidebar.close{
    width:85px;
}

/* ================= LOGO ================= */

.logo{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:40px;
}

.logo h2{
    color:#38bdf8;
    font-size:24px;
}

/* ================= TOGGLE ================= */

.toggle-btn{
    width:40px;
    height:40px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    cursor:pointer;
    font-size:18px;
}

/* ================= MENU ================= */

.menu a{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    margin-bottom:15px;
    border-radius:14px;
    text-decoration:none;
    color:#e2e8f0;
    transition:0.3s;
}

.menu a:hover{
    background:linear-gradient(90deg,#2563eb,#38bdf8);
    transform:translateX(5px);
}

.menu a.active{
    background:linear-gradient(90deg,#2563eb,#38bdf8);
}

.menu i{
    min-width:25px;
    font-size:18px;
}

.sidebar.close .menu span,
.sidebar.close .logo h2{
    display:none;
}

/* ================= MAIN ================= */

.main{
    margin-left:270px;
    width:calc(100% - 270px);
    transition:0.4s;
}

.main.expand{
    margin-left:85px;
    width:calc(100% - 85px);
}

/* ================= TOPBAR ================= */

.topbar{
    background:white;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.top-left h1{
    color:#0f172a;
    font-size:30px;
}

.top-left p{
    color:#64748b;
    margin-top:5px;
}

/* ================= USER PROFILE ================= */

.user-profile{
    display:flex;
    align-items:center;
    gap:15px;
    background:#f8fafc;
    padding:10px 18px;
    border-radius:18px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

.user-profile img{
    width:55px;
    height:55px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #38bdf8;
}

.user-info{
    display:flex;
    flex-direction:column;
}

.user-info h3{
    color:#0f172a;
    font-size:17px;
}

.user-info p{
    color:#64748b;
    font-size:14px;
}

/* ================= CONTENT ================= */

.content{
    padding:40px;
}

/* ================= OVERVIEW ================= */

.overview-title{
    margin-bottom:25px;
    color:#0f172a;
    font-size:28px;
}

/* ================= STATS ================= */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}

/* ================= CARD ================= */

.stat-card{
    position:relative;
    overflow:hidden;
    border-radius:25px;
    padding:30px;
    color:white;
    transition:0.4s;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.stat-card:hover{
    transform:translateY(-8px);
}

/* COLORS */

.blue{
    background:linear-gradient(135deg,#2563eb,#38bdf8);
}

.green{
    background:linear-gradient(135deg,#059669,#34d399);
}

.orange{
    background:linear-gradient(135deg,#ea580c,#fb923c);
}

.purple{
    background:linear-gradient(135deg,#7c3aed,#c084fc);
}

/* CONTENT */

.stat-card .icon{
    font-size:50px;
    opacity:0.2;
    position:absolute;
    top:20px;
    right:20px;
}

.stat-card h3{
    margin-bottom:15px;
    font-size:20px;
}

.stat-card h1{
    font-size:34px;
    margin-bottom:10px;
}

.stat-card p{
    opacity:0.9;
}

/* ================= QUICK ACTIONS ================= */

.quick-actions{
    margin-top:45px;
}

.quick-actions h2{
    margin-bottom:20px;
    color:#0f172a;
}

.action-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.action-btn{
    background:white;
    padding:30px;
    border-radius:22px;
    text-align:center;
    text-decoration:none;
    color:#0f172a;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
    transition:0.3s;
}

.action-btn:hover{
    transform:translateY(-5px);
    background:#2563eb;
    color:white;
}

.action-btn i{
    font-size:38px;
    margin-bottom:15px;
}

/* ================= MOBILE ================= */

@media(max-width:900px){

    .sidebar{
        width:85px;
    }

    .sidebar .menu span,
    .sidebar .logo h2{
        display:none;
    }

    .main{
        margin-left:85px;
        width:calc(100% - 85px);
    }

    .content{
        padding:20px;
    }

    .topbar{
        padding:20px;
        flex-direction:column;
        gap:20px;
        align-items:flex-start;
    }

}

</style>

</head>

<body>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar" id="sidebar">

    <div class="logo">

        <h2>📊 Ledger</h2>

        <button class="toggle-btn"
        onclick="toggleSidebar()">

            ☰

        </button>

    </div>

    <div class="menu">

        <a href="dashboard.php" class="active">
            <i class="fa fa-home"></i>
            <span>Dashboard</span>
        </a>

        <a href="profile.php">
            <i class="fa fa-user"></i>
            <span>Profile</span>
        </a>

        <a href="view_students.php">
            <i class="fa fa-users"></i>
            <span>Students</span>
        </a>

        <a href="view_contribution.php">
            <i class="fa fa-money-bill"></i>
            <span>Contributions</span>
        </a>

        <a href="add_expense.php">
            <i class="fa fa-wallet"></i>
            <span>Expenses</span>
        </a>

        <a href="report.php">
            <i class="fa fa-chart-line"></i>
            <span>Reports</span>
        </a>

        <a href="logout.php">
            <i class="fa fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>

    </div>

</div>

<!-- ================= MAIN ================= -->

<div class="main">

    <!-- ================= TOPBAR ================= -->

    <div class="topbar">

        <div class="top-left">

            <h1>Dashboard</h1>

            <p>
                Class Ledger Management System
            </p>

        </div>

        <!-- USER PROFILE -->

        <div class="user-profile">

            <?php if($userData['image'] != ""){ ?>

                <img src="uploads/<?php echo $userData['image']; ?>">

            <?php } else { ?>

                <img src="https://i.imgur.com/6VBx3io.png">

            <?php } ?>

            <div class="user-info">

                <h3>
                    <?php echo $userData['name']; ?>
                </h3>

                <p>
                    <?php echo ucfirst($userData['role']); ?>
                </p>

            </div>

        </div>

    </div>

    <!-- ================= CONTENT ================= -->

    <div class="content">

        <h2 class="overview-title">
            📊 Dashboard Overview
        </h2>

        <!-- ================= STATS ================= -->

        <div class="stats">

            <!-- EXPENSES -->

            <div class="stat-card orange">

                <i class="fa fa-wallet icon"></i>

                <h3>💰 Expenses</h3>

                <h1>
                    <?php echo number_format($totalExpenses); ?>
                    TZS
                </h1>

                <p>
                    Total expenses used
                </p>

            </div>

            <!-- REPORTS -->

            <div class="stat-card purple">

                <i class="fa fa-chart-line icon"></i>

                <h3>📚 Reports</h3>

                <h1>12</h1>

                <p>
                    Available reports
                </p>

            </div>

            <!-- STUDENTS -->

            <div class="stat-card blue">

                <i class="fa fa-users icon"></i>

                <h3>👨‍🎓 Students</h3>

                <h1>
                    <?php echo $totalStudents; ?>
                </h1>

                <p>
                    Registered students
                </p>

            </div>

            <!-- INCOME -->

            <div class="stat-card green">

                <i class="fa fa-money-bill-wave icon"></i>

                <h3>📈 Income</h3>

                <h1>
                    <?php echo number_format($totalContributions); ?>
                    TZS
                </h1>

                <p>
                    Total contributions
                </p>

            </div>

        </div>

        <!-- ================= QUICK ACTIONS ================= -->

        <div class="quick-actions">

            <h2>
                ⚡ Quick Actions
            </h2>

            <div class="action-grid">

                <a href="add_student.php"
                class="action-btn">

                    <i class="fa fa-user-plus"></i>

                    <h3>Add Student</h3>

                </a>

                <a href="add_contribution.php"
                class="action-btn">

                    <i class="fa fa-money-bill"></i>

                    <h3>Add Contribution</h3>

                </a>

                <a href="add_expense.php"
                class="action-btn">

                    <i class="fa fa-wallet"></i>

                    <h3>Add Expense</h3>

                </a>

                <a href="report.php"
                class="action-btn">

                    <i class="fa fa-chart-pie"></i>

                    <h3>View Reports</h3>

                </a>

            </div>

        </div>

    </div>

</div>

<script>

function toggleSidebar(){

    document
    .getElementById("sidebar")
    .classList
    .toggle("close");

    document
    .querySelector(".main")
    .classList
    .toggle("expand");

}

</script>

</body>
</html>
