<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษานักศึกษา</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #fdf6e3;
            margin: 0;
            padding: 0;
        }

        .navbar {
            width: 100%;
            background-color: #ffe0b2;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            height: 60px;
            z-index: 1000;
        }

        .navbar-logo img {
            height: 40px;
        }

        .navbar-user {
            font-size: 1rem;
            margin-right: 20px;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #fff;
            padding-top: 80px;
            position: fixed;
            left: 0;
            top: 60px;
            border-right: 1px solid #ddd;
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 1rem;
        }

        .sidebar a:hover {
            background-color: #ffe0b2;
            color: #333;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            margin-top: 80px;
            background-color: #ffffff;
        }

        .title-search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .title-section {
            width: 30%;
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .search-section {
            width: 70%;
            display: flex;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .search-button {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #ffe0b2;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #ffcc80;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #ffe0b2;
            color: #333;
        }

        table td {
            background-color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-logo">
            <img src="C:\Users\ACER\Desktop\example-app\img\KKU_Logo.png" alt="โลโก้ระบบ">
        </div>
        <div class="navbar-user">ชื่อผู้ใช้: อาจารย์</div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#">ค้นหานักศึกษา</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="title-search-container">
            <!-- Left section: Title -->
            <div class="title-section">
                รายชื่อนักศึกษาที่รับผิดชอบ
            </div>

            <!-- Right section: Search bar -->
            <div class="search-section">
                <input type="text" class="search-input" id="search-input" placeholder="กรอกรหัสนักศึกษาหรือชื่อ">
                <button class="search-button" onclick="searchStudent()">ค้นหา</button>
            </div>
        </div>

        <!-- Filter Dropdown -->
        <div class="filter-section" style="margin-bottom: 20px;">
            <select id="filter-select" onchange="filterTable()">
                <option value="all">ทั้งหมด</option>
                <option value="grade">เกรดเฉลี่ย</option>
                <option value="kept">KEPT Exits</option>
                <option value="dq">DQ</option>
                <option value="internship">ฝึกงาน</option>
            </select>
        </div>

        <!-- Table of students -->
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อนามสกุล</th>
                    <th>คณะสาขา</th>
                    <th>เกรดเฉลี่ย</th>
                    <th>KEPT Exits</th>
                    <th>DQ</th>
                    <th>ฝึกงาน</th>
                </tr>
            </thead>
            <tbody id="student-table">
                <tr>
                    <td>1</td>
                    <td>643450067-0</td>
                    <td>เจษฎา ลีรัตน์</td>
                    <td>วิทยาการคอมพิวเตอร์และสารสนเทศ</td>
                    <td>3.50</td>
                    <td>ผ่าน</td>
                    <td>ผ่าน</td>
                    <td>ผ่าน</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>643450067-1</td>
                    <td>สมชาย ใจดี</td>
                    <td>วิศวกรรมศาสตร์</td>
                    <td>3.20</td>
                    <td>ไม่ผ่าน</td>
                    <td>ผ่าน</td>
                    <td>ไม่ผ่าน</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function searchStudent() {
            var input = document.getElementById('search-input').value.toLowerCase();
            var table = document.getElementById('student-table');
            var tr = table.getElementsByTagName('tr');
            
            for (var i = 0; i < tr.length; i++) {
                var tdStudentId = tr[i].getElementsByTagName('td')[1];
                var tdName = tr[i].getElementsByTagName('td')[2];
                if (tdStudentId || tdName) {
                    var studentIdValue = tdStudentId.textContent || tdStudentId.innerText;
                    var nameValue = tdName.textContent || tdName.innerText;
                    
                    if (studentIdValue.toLowerCase().indexOf(input) > -1 || nameValue.toLowerCase().indexOf(input) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function filterTable() {
            var filter = document.getElementById("filter-select").value;
            var table = document.getElementById("student-table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var tdGrade = tr[i].getElementsByTagName("td")[4];
                var tdKept = tr[i].getElementsByTagName("td")[5];
                var tdDQ = tr[i].getElementsByTagName("td")[6];
                var tdInternship = tr[i].getElementsByTagName("td")[7];
                
                if (filter === "all") {
                    tr[i].style.display = "";
                } else if (filter === "grade" && tdGrade && tdGrade.textContent !== "") {
                    tr[i].style.display = "";
                } else if (filter === "kept" && tdKept && tdKept.textContent !== "") {
                    tr[i].style.display = "";
                } else if (filter === "dq" && tdDQ && tdDQ.textContent !== "") {
                    tr[i].style.display = "";
                } else if (filter === "internship" && tdInternship && tdInternship.textContent !== "") {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>

</body>
</html>
