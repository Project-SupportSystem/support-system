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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ปรับแต่ง Body และพื้นหลัง */
        body {
            background-color: #f5f7fa;
            font-family: 'Kanit', sans-serif;
            color: #333;
        }

        /* ปรับแต่ง Navbar */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar-logo img {
            height: 45px;
        }
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            font-size: 14px;
            padding: 8px 15px;
        }

        /* ปรับแต่ง Sidebar */
        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: 220px;
            height: calc(100% - 80px);
            background-color: #ffffff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            padding: 20px 0;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        /* ปรับแต่ง Content */
        .content {
            margin-left: 240px;
            margin-top: 100px;
            padding: 30px;
        }
        .content h2 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        /* ปรับแต่ง Input และ Search */
        .input-group {
            max-width: 500px;
            margin: 0 auto 20px;
        }
        .input-group input {
            border: 1px solid #ced4da;
            border-radius: 8px 0 0 8px;
            font-size: 14px;
            padding: 10px 15px;
        }
        .input-group button {
            border-radius: 0 8px 8px 0;
            background-color: #0d6efd;
            border: none;
            font-size: 14px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .input-group button:hover {
            background-color: #0b5ed7;
        }

        /* ปรับแต่งตาราง */
        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        .table thead {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-size: 14px;
        }
        .table th, .table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        .table tbody tr {
            transition: background-color 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table tbody td {
            font-size: 14px;
            color: #555;
        }
        .gpa-link, .document-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .gpa-link:hover, .document-link:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }

        /* ปรับแต่งสถานะ KKU DQ และ KKU KEPT */
        .status-pass {
            color: #28a745; /* สีเขียว */
            font-weight: 500;
        }
        .status-fail {
            color: #dc3545; /* สีแดง */
            font-weight: 500;
        }

        /* ปรับแต่ง Accordion */
        .semester-header {
            background-color: #e9ecef;
            color: #2c3e50;
            font-weight: 500;
            padding: 12px 15px;
            margin-top: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .semester-header:hover {
            background-color: #dfe2e5;
        }
        .collapse-content {
            display: none;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }
        .academic-table {
            font-size: 13px;
            border-radius: 8px;
            overflow: hidden;
        }
        .academic-table th, .academic-table td {
            padding: 8px 12px;
            text-align: center;
        }
        .academic-table th {
            background-color: #f1f3f5;
            color: #2c3e50;
            font-weight: 500;
        }
        .academic-table td {
            color: #555;
        }

        /* ปรับแต่ง Modal */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .modal-body ul {
            list-style: none;
            padding: 0;
        }
        .modal-body ul li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .modal-body ul li:last-child {
            border-bottom: none;
        }
        .modal-body ul li a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 14px;
        }
        .modal-body ul li a:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <div class="navbar d-flex justify-content-between align-items-center">
        <div class="navbar-logo">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ระบบ">
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

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="text-center">รายชื่อนักศึกษาที่รับผิดชอบ</h2>

            <!-- Search Input -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search-input" placeholder="กรอกรหัสนักศึกษาหรือชื่อ">
                <button class="btn btn-primary" onclick="searchStudent()">ค้นหา</button>
            </div>

            <!-- ลบ Filter Select ออก -->

            <!-- Main Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อนามสกุล</th>
                            <th>สาขา</th>
                            <th>เกรดเฉลี่ย</th>
                            <th>สหกิจศึกษา</th>
                            <th>โปรเจค</th>
                            <th>ฝึกงาน</th>
                            <th>KKU DQ</th>
                            <th>KKU KEPT</th>
                        </tr>
                    </thead>
                    <tbody id="student-table">
                        @foreach($students as $index => $student)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name ?? '' }}</td>
                                <td>วิทยาการคอมพิวเตอร์และสารสนเทศ</td>
                                <td>
                                    @if($student->calculateGPA())
                                        <a href="#" class="gpa-link" onclick="toggleAcademicRecords('{{ $student->id }}')">{{ number_format($student->calculateGPA(), 2) }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'coop_project')->isNotEmpty())
                                        <a href="#" class="document-link" onclick="showFilesModal('{{ $student->id }}', 'coop_project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'project')->isNotEmpty())
                                        <a href="#" class="document-link" onclick="showFilesModal('{{ $student->id }}', 'project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'internship')->isNotEmpty())
                                        <a href="#" class="document-link" onclick="showFilesModal('{{ $student->id }}', 'internship')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'kku_dq')->isNotEmpty())
                                        @php
                                            $doc = $student->documents->where('document_type', 'kku_dq')->first();
                                            $status = $doc->status;
                                            $displayStatus = in_array($doc->document_type, ['kku_dq']) && $status
                                                ? ($status == 'pass' ? '<span class="status-pass">ผ่าน</span>' : ($status == 'fail' ? '<span class="status-fail">ไม่ผ่าน</span>' : '-'))
                                                : '-';
                                        @endphp
                                        {!! $displayStatus !!}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'kku_kept')->isNotEmpty())
                                        @php
                                            $doc = $student->documents->where('document_type', 'kku_kept')->first();
                                            $status = $doc->status;
                                            $displayStatus = in_array($doc->document_type, ['kku_kept']) && $status
                                                ? ($status == 'pass' ? '<span class="status-pass">ผ่าน</span>' : ($status == 'fail' ? '<span class="status-fail">ไม่ผ่าน</span>' : '-'))
                                                : '-';
                                        @endphp
                                        {!! $displayStatus !!}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <!-- Accordion สำหรับแสดงผลการเรียน -->
                            <tr id="academic-records-{{ $student->id }}" class="collapse-content">
                                <td colspan="10">
                                    @if(isset($academicRecords[$student->id]) && !$academicRecords[$student->id]->isEmpty())
                                        @foreach($academicRecords[$student->id]->groupBy('semester') as $semester => $records)
                                            <div class="semester-header" onclick="toggleCollapse('semester-{{ $student->id }}-{{ $semester }}')">
                                                ภาคการศึกษา {{ $semester }}
                                            </div>
                                            <div id="semester-{{ $student->id }}-{{ $semester }}" class="collapse">
                                                <table class="table table-bordered academic-table">
                                                    <thead>
                                                        <tr>
                                                            <th>รหัสวิชา</th>
                                                            <th>ชื่อวิชา</th>
                                                            <th>หน่วยกิต</th>
                                                            <th>เกรด</th>
                                                            <th>GPA</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($records as $record)
                                                            <tr>
                                                                <td>{{ $record->course_id ?? '-' }}</td>
                                                                <td>{{ $record->course->course_name ?? 'ไม่พบชื่อวิชา' }}</td>
                                                                <td>{{ $record->course->total_credits ?? 3 }}</td>
                                                                <td>{{ $record->grade ?? '-' }}</td>
                                                                <td>{{ $record->gpa ? number_format($record->gpa, 2) : '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @php
                                                    $totalCredits = $records->sum(function($record) {
                                                        return $record->course->total_credits ?? 3;
                                                    });
                                                    $totalPoints = $records->sum(function($record) {
                                                        return ($record->gpa ?? 0) * ($record->course->total_credits ?? 3);
                                                    });
                                                    $semesterGPA = $totalCredits > 0 ? number_format($totalPoints / $totalCredits, 2) : '0.00';
                                                @endphp
                                                <p><strong>GPA เฉพาะภาคนี้: {{ $semesterGPA }}</strong></p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">ไม่มีข้อมูลผลการเรียน</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal สำหรับแสดงเอกสาร -->
            <div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filesModalLabel">ไฟล์เอกสาร</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul id="filesList"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFilesModal(studentId, docType) {
            $.ajax({
                url: `/get-student-documents/${studentId}/${docType}`,
                type: 'GET',
                success: function (response) {
                    $('#filesList').empty();
                    if (response.documents.length > 0) {
                        response.documents.forEach(function (doc) {
                            let statusText = ['kku_dq', 'kku_kept'].includes(docType) && doc.status
                                ? ` (${doc.status == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน'})`
                                : '';
                            $('#filesList').append(`<li><a href="${doc.file_path}" target="_blank">${doc.file_name}${statusText}</a></li>`);
                        });
                    } else {
                        $('#filesList').append('<li><span class="text-muted">ไม่มีเอกสาร</span></li>');
                    }
                    $('#filesModal').modal('show');
                },
                error: function (xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงข้อมูลเอกสารได้: ' + (xhr.responseJSON?.message || xhr.responseText), 'error');
                    console.log('Error Details:', xhr.responseText);
                }
            });
        }

        function searchStudent() {
            var input = document.getElementById('search-input').value.toLowerCase();
            var table = document.getElementById('student-table');
            var tr = table.getElementsByTagName('tr');

            for (var i = 0; i < tr.length; i++) {
                var tdStudentId = tr[i].getElementsByTagName('td')[1];
                var tdName = tr[i].getElementsByTagName('td')[2];
                if (tdStudentId && tdName) {
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

        function toggleAcademicRecords(studentId) {
            var row = document.getElementById('academic-records-' + studentId);
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        }

        function toggleCollapse(semesterId) {
            var collapse = document.getElementById(semesterId);
            if (collapse.style.display === 'none' || collapse.style.display === '') {
                collapse.style.display = 'block';
            } else {
                collapse.style.display = 'none';
            }
        }
    </script>
</body>
</html>