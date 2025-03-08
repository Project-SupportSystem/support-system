<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรายงานผลการศึกษานักศึกษา - มหาวิทยาลัยขอนแก่น</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* คง CSS เดิมทั้งหมด */
        body {
            background-color: #f5f7fa;
            font-family: 'Kanit', sans-serif;
            color: #333;
        }
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
            color: #F36D21;
        }
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
            background-color: #F36D21;
            border: none;
            font-size: 14px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
            color: #fff;
        }
        .input-group button:hover {
            background-color: #D85B1B;
        }
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
        .status-pass {
            color: #28a745;
            font-weight: 500;
        }
        .status-fail {
            color: #dc3545;
            font-weight: 500;
        }
        .semester-header {
            background-color: #e9ecef;
            color: #2c3e50;
            font-weight: 500;
            padding: 12px 15px;
            margin-top: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .semester-header:hover {
            background-color: #dfe2e5;
        }
        .semester-header .fa-chevron-down {
            transition: transform 0.3s ease;
        }
        .semester-header.active .fa-chevron-down {
            transform: rotate(180deg);
        }
        .collapse-content {
            display: none;
        }
        .collapse-content td {
            padding: 0;
        }
        .collapse {
            display: none;
        }
        .collapse.show {
            display: block;
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
        .summary-table th {
            background-color: #f1f3f5;
            color: #2c3e50;
        }
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
        .modal-body .image-preview {
            max-width: 100%;
            max-height: 400px;
            margin: 10px auto;
            display: block;
        }
        .modal-body .file-link {
            display: block;
            margin: 10px 0;
            color: #0d6efd;
            text-decoration: none;
        }
        .modal-body .file-link:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }
        .student-count-text {
            font-size: 16px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0;
        }
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1e1e1e;
                color: #d1d1d1;
            }
            .navbar, .sidebar, .table {
                background-color: #2c2c2c;
                border-color: #444444;
                box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
            }
            .sidebar a {
                color: #adb5bd;
            }
            .sidebar a:hover {
                background-color: #3c3c3c;
                color: #F36D21;
            }
            .content h2 {
                color: #e9ecef;
            }
            .input-group input {
                background-color: #3c3c3c;
                border-color: #555;
                color: #d1d1d1;
            }
            .input-group button {
                background-color: #F36D21;
                color: #fff;
            }
            .input-group button:hover {
                background-color: #D85B1B;
            }
            .table thead {
                background-color: #3c3c3c;
                color: #e9ecef;
            }
            .table tbody tr:hover {
                background-color: #3c3c3c;
            }
            .table tbody td {
                color: #adb5bd;
            }
            .gpa-link, .document-link {
                color: #0d6efd;
            }
            .gpa-link:hover, .document-link:hover {
                color: #0b5ed7;
            }
            .semester-header {
                background-color: #3c3c3c;
                color: #e9ecef;
            }
            .semester-header:hover {
                background-color: #4a4a4a;
            }
            .academic-table th {
                background-color: #3c3c3c;
                color: #e9ecef;
            }
            .academic-table td {
                color: #adb5bd;
            }
            .summary-table th {
                background-color: #3c3c3c;
                color: #e9ecef;
            }
            .modal-header {
                background-color: #3c3c3c;
                color: #e9ecef;
                border-bottom: 1px solid #555;
            }
            .modal-body .file-link {
                color: #0d6efd;
            }
            .modal-body .file-link:hover {
                color: #0b5ed7;
            }
            .student-count-text {
                color: #e9ecef;
            }
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
                        <a class="dropdown-item" href="{{ route('logout') }}">ออกจากระบบ</a>
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
        <a href="#" data-bs-toggle="modal" data-bs-target="#editAdvisorModal">ข้อมูลอาจารย์ที่ปรึกษา</a>
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
                                        <a href="#" class="gpa-link" onclick="toggleAcademicRecords('{{ $student->id }}')">
                                            {{ number_format($student->calculateGPA(), 2) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'coop_project')->isNotEmpty())
                                        <a href="#" class="document-link" 
                                           onclick="showFilesModal('{{ $student->id }}', 'coop_project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'project')->isNotEmpty())
                                        <a href="#" class="document-link" 
                                           onclick="showFilesModal('{{ $student->id }}', 'project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'internship')->isNotEmpty())
                                        <a href="#" class="document-link" 
                                           onclick="showFilesModal('{{ $student->id }}', 'internship')">ดูเอกสาร</a>
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
                                                ? ($status == 'pass' 
                                                    ? '<span class="status-pass">ผ่าน</span>' 
                                                    : ($status == 'fail' 
                                                        ? '<span class="status-fail">ไม่ผ่าน</span>' 
                                                        : '-')
                                                  )
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
                                                ? ($status == 'pass' 
                                                    ? '<span class="status-pass">ผ่าน</span>' 
                                                    : ($status == 'fail' 
                                                        ? '<span class="status-fail">ไม่ผ่าน</span>' 
                                                        : '-')
                                                  )
                                                : '-';
                                        @endphp
                                        {!! $displayStatus !!}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr id="academic-records-{{ $student->id }}" class="collapse-content">
                                <td colspan="10">
                                    @if(isset($academicRecords[$student->id]) && !$academicRecords[$student->id]->isEmpty())
                                        @php
                                            $semesters = $academicRecords[$student->id]->pluck('semester')->unique()->map(function ($semester) {
                                                [$term, $year] = explode('/', $semester);
                                                return ['term' => (int)$term, 'year' => (int)$year, 'original' => $semester];
                                            })->sortBy(function ($item) {
                                                return $item['year'] * 100 + $item['term'];
                                            })->pluck('original')->values();

                                            $accumCR = 0;
                                            $accumCP = 0;
                                            $accumCA = 0;
                                            $accumGP = 0;
                                        @endphp

                                        @foreach($semesters as $semester)
                                            @php
                                                $records = $academicRecords[$student->id]->where('semester', $semester);
                                                $totalCR = 0;
                                                $totalCP = 0;
                                                $totalCA = 0;
                                                $totalGP = 0;
                                            @endphp

                                            <div class="semester-header" onclick="toggleCollapse('semester-{{ $student->id }}-{{ str_replace('/', '-', $semester) }}')">
                                                ภาคการศึกษา {{ $semester }}
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                            <div id="semester-{{ $student->id }}-{{ str_replace('/', '-', $semester) }}" class="collapse">
                                                <table class="table table-bordered academic-table">
                                                    <thead>
                                                        <tr>
                                                            <th>รหัสวิชา</th>
                                                            <th>ชื่อวิชา</th>
                                                            <th>หน่วยกิต</th>
                                                            <th>เกรด</th>
                                                            <th>CR</th>
                                                            <th>CP</th>
                                                            <th>CA</th>
                                                            <th>GP</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($records as $record)
                                                            @php
                                                                $courseCredits = $record->course->total_credits ?? 3;
                                                                $gradeLetter = $record->grade ?? '-';
                                                                $gpaVal = $record->gpa ?? 0;

                                                                $CR = $courseCredits;
                                                                $CP = ($gradeLetter != 'F') ? $courseCredits : 0;
                                                                $CA = $courseCredits;
                                                                $GP = $gpaVal * $courseCredits;

                                                                $totalCR += $CR;
                                                                $totalCP += $CP;
                                                                $totalCA += $CA;
                                                                $totalGP += $GP;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $record->course_id }}</td>
                                                                <td>{{ $record->course->course_name }}</td>
                                                                <td>{{ $courseCredits }}</td>
                                                                <td>{{ $gradeLetter }}</td>
                                                                <td>{{ $CR }}</td>
                                                                <td>{{ $CP }}</td>
                                                                <td>{{ $CA }}</td>
                                                                <td>{{ number_format($GP, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>รวม</strong></td>
                                                            <td><strong>{{ $totalCR }}</strong></td>
                                                            <td><strong>{{ $totalCP }}</strong></td>
                                                            <td><strong>{{ $totalCA }}</strong></td>
                                                            <td><strong>{{ number_format($totalGP, 2) }}</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                @php
                                                    $semesterGPA = $totalCR > 0 ? number_format($totalGP / $totalCR, 2) : '0.00';
                                                    $accumCR += $totalCR;
                                                    $accumCP += $totalCP;
                                                    $accumCA += $totalCA;
                                                    $accumGP += $totalGP;
                                                    $accumGPA = $accumCR > 0 ? number_format($accumGP / $accumCR, 2) : '0.00';
                                                @endphp

                                                <table class="table table-bordered summary-table" style="margin-top: 10px;">
                                                    <thead>
                                                        <tr class="table-light">
                                                            <th colspan="5">เทอมนี้</th>
                                                            <th colspan="5">สะสมถึงเทอมนี้</th>
                                                        </tr>
                                                        <tr>
                                                            <th>CR</th>
                                                            <th>CP</th>
                                                            <th>CA</th>
                                                            <th>GP</th>
                                                            <th>GPA</th>
                                                            <th>CR</th>
                                                            <th>CP</th>
                                                            <th>CA</th>
                                                            <th>GP</th>
                                                            <th>GPA</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $totalCR }}</td>
                                                            <td>{{ $totalCP }}</td>
                                                            <td>{{ $totalCA }}</td>
                                                            <td>{{ number_format($totalGP, 2) }}</td>
                                                            <td>{{ $semesterGPA }}</td>
                                                            <td>{{ $accumCR }}</td>
                                                            <td>{{ $accumCP }}</td>
                                                            <td>{{ $accumCA }}</td>
                                                            <td>{{ number_format($accumGP, 2) }}</td>
                                                            <td>{{ $accumGPA }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
                        <div class="modal-body" id="filesModalBody">
                            <!-- รูปภาพหรือลิงก์จะถูกแทรกที่นี่ -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal สำหรับข้อมูลอาจารย์ที่ปรึกษา -->
            <div class="modal fade" id="editAdvisorModal" tabindex="-1" aria-labelledby="editAdvisorModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAdvisorModalLabel">ข้อมูลอาจารย์ที่ปรึกษา</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('advisor.edit.name') }}" method="POST" id="advisorForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="advisor-name-input" class="form-label">ชื่ออาจารย์</label>
                                    <input type="text" class="form-control" id="advisor-name-input" name="first_name" 
                                           value="{{ $advisor->first_name ?? Auth::user()->username }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="student-count" class="form-label">จำนวนนักศึกษาที่ดูแล</label>
                                    <p class="student-count-text">{{ $studentCount }}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFilesModal(studentId, docType) {
            Swal.fire({
                title: 'กำลังโหลด...',
                text: 'กรุณารอสักครู่ขณะที่ระบบดึงข้อมูลเอกสาร',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/get-student-documents/${studentId}/${docType}`,
                type: 'GET',
                success: function (response) {
                    Swal.close();
                    const modalBody = $('#filesModalBody');
                    modalBody.empty();

                    if (response.documents.length > 0) {
                        response.documents.forEach(function (doc) {
                            const fileExtension = doc.file_name.split('.').pop().toLowerCase();
                            const isImage = ['jpg', 'jpeg', 'png'].includes(fileExtension);

                            if (isImage) {
                                modalBody.append(`
                                    <img src="${doc.file_path}" alt="${doc.file_name}" class="image-preview">
                                `);
                            } else {
                                let statusText = ['kku_dq', 'kku_kept'].includes(docType) && doc.status
                                    ? ` (${doc.status == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน'})`
                                    : '';
                                modalBody.append(`
                                    <a href="${doc.file_path}" target="_blank" class="file-link">${doc.file_name}${statusText}</a>
                                `);
                            }
                        });
                    } else {
                        modalBody.append('<p class="text-muted">ไม่มีเอกสาร</p>');
                    }
                    $('#filesModal').modal('show');
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: 'ไม่สามารถดึงข้อมูลเอกสารได้: ' + (xhr.responseJSON?.message || xhr.responseText),
                        confirmButtonColor: '#0d6efd'
                    });
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
                    if (studentIdValue.toLowerCase().indexOf(input) > -1 
                        || nameValue.toLowerCase().indexOf(input) > -1) {
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
            var header = collapse.previousElementSibling;
            collapse.classList.toggle('show');
            header.classList.toggle('active');
        }

        // เพิ่มการยืนยันก่อนส่งฟอร์ม
        $('#advisorForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการส่งฟอร์มทันที

            Swal.fire({
                title: 'ยืนยันการเปลี่ยนชื่อ?',
                text: "คุณต้องการบันทึกการเปลี่ยนชื่อนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F36D21',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ไม่ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    // หากเลือก "ตกลง" ให้ส่งฟอร์ม
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire('สำเร็จ!', 'เปลี่ยนชื่อสำเร็จ!', 'success').then(() => {
                                location.reload(); // รีเฟรชหน้าเพื่ออัปเดตข้อมูล
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('ผิดพลาด!', 'ไม่สามารถเปลี่ยนชื่อได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>