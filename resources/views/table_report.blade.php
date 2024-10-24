<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรายงานผลการศึกษานักศึกษา</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <div class="navbar d-flex justify-content-between align-items-center p-2 bg-light">
        <div class="navbar-logo">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ระบบ" style="height: 50px;">
        </div>

        <div class="dropdown">
            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown" 
               data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/user-icon.png') }}" alt="User Icon" 
                     style="width: 30px; height: 30px; border-radius: 50%;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                @auth
                    <li>
                        <a class="dropdown-item" href="#">
                            {{ Auth::user()->username ?? 'ไม่พบข้อมูลผู้ใช้' }} ({{ Auth::user()->role ?? 'ไม่มีสิทธิ์' }})
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                    </li>
                @else
                    <li>
                        <a class="dropdown-item" href="{{ route('login') }}">เข้าสู่ระบบ</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('table_report') }}">ค้นหานักศึกษา</a>
    </div>

    <!-- Main content -->
    <div class="content p-4" style="margin-left: 250px; margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-4">รายชื่อนักศึกษาที่รับผิดชอบ</h2>

            <!-- Search Bar -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search-input" placeholder="กรอกรหัสนักศึกษาหรือชื่อ">
                <button class="btn btn-primary" onclick="searchStudent()">ค้นหา</button>
            </div>

            <!-- Filter Dropdown -->
            <div class="mb-3">
                <select class="form-select" id="filter-select" onchange="filterTable()">
                    <option value="all">ทั้งหมด</option>
                    <option value="grade">เกรดเฉลี่ย</option>
                    <option value="kept">KEPT Exits</option>
                    <option value="dq">DQ</option>
                    <option value="internship">ฝึกงาน</option>
                </select>
            </div>

            <!-- Table of Students -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อนามสกุล</th>
                            <th>เกรดเฉลี่ย</th>
                            <th>คณะสาขา</th>
                            <th>KEPT Exits</th>
                            <th>DQ</th>
                            <th>ฝึกงาน</th>
                        </tr>
                    </thead>
                    <tbody id="student-table">
                        @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->first_name }}</td>
                                <!-- <td>{{ $student->faculty ?? 'ไม่ระบุ' }}</td> -->
                                <td>{{ $student->calculateGPA() ? number_format($student->calculateGPA(), 2) : '-' }}</td>
                                <!-- <td>{{ $student->kept_status ?? '-' }}</td>
                                <td>{{ $student->dq_status ?? '-' }}</td>
                                <td>{{ $student->internship_status ?? '-' }}</td> -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                } else if (filter === "kept" && tdKept && tdKept.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else if (filter === "dq" && tdDQ && tdDQ.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else if (filter === "internship" && tdInternship && tdInternship.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>

</body>
</html>
