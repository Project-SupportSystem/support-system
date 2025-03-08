<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษานักศึกษา - มหาวิทยาลัยขอนแก่น</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
            color: #f36d21; /* เปลี่ยนเป็นสีส้มของ KKU */
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
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #2c3e50;
        }
        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .btn-custom {
            margin-right: 10px;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #f36d21; /* เปลี่ยนเป็นสีส้มของ KKU */
            border: none;
        }
        .btn-primary:hover {
            background-color: #d85b1b; /* สีส้มเข้มเมื่อ hover */
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5c636a;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .alert-success, .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-add-course, .btn-remove-course {
            margin: 0 2px;
            border-radius: 8px;
        }
        .gpa-container {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .gpa-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .total-credits-added {
            font-size: 1rem;
            color: #f36d21; /* เปลี่ยนเป็นสีส้มของ KKU */
        }
        .total-credits-added strong {
            color: #f36d21; /* เปลี่ยนเป็นสีส้มของ KKU */
        }
        .grade-details-tab {
            margin-top: 30px;
        }
        .semester-select-container {
            display: flex;
            gap: 15px;
            align-items: center;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .semester-select-container label {
            margin-bottom: 0;
            font-weight: 500;
            color: #2c3e50;
        }
        .semester-select-container select {
            width: 150px;
        }
        .summary-table {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .summary-table th, .summary-table td {
            text-align: center;
            padding: 8px;
        }
        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .summary-table .highlight {
            color: #dc3545;
            font-weight: bold;
        }
        .abbreviations {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .abbreviations p {
            margin: 0;
            line-height: 1.5;
        }
        .modal-content {
            border-radius: 8px;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        /* Dark Mode */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1e1e1e;
                color: #d1d1d1;
            }
            .navbar, .sidebar, .card {
                background-color: #2c2c2c;
                border-color: #444444;
                box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
            }
            .sidebar a {
                color: #adb5bd;
            }
            .sidebar a:hover {
                background-color: #3c3c3c;
                color: #f36d21;
            }
            .content h2, .section-title, .form-label {
                color: #e9ecef;
            }
            .form-control, .form-select {
                background-color: #3c3c3c;
                border-color: #555;
                color: #d1d1d1;
            }
            .btn-primary {
                background-color: #f36d21;
            }
            .btn-primary:hover {
                background-color: #d85b1b;
            }
            .btn-secondary {
                background-color: #6c757d;
            }
            .btn-secondary:hover {
                background-color: #5c636a;
            }
            .btn-success {
                background-color: #28a745;
            }
            .btn-success:hover {
                background-color: #218838;
            }
            .btn-danger {
                background-color: #dc3545;
            }
            .btn-danger:hover {
                background-color: #c82333;
            }
            .alert-success {
                background-color: #28a745;
                color: #ffffff;
            }
            .alert-danger {
                background-color: #dc3545;
                color: #ffffff;
            }
            .total-credits-added {
                color: #f36d21;
            }
            .total-credits-added strong {
                color: #f36d21;
            }
            .summary-table th {
                background-color: #3c3c3c;
            }
            .abbreviations {
                background-color: #2c2c2c;
            }
        }
    </style>
</head>
<body>
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
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="text-center mb-4">กรอกผลการศึกษา</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @auth
                <p class="mb-3">ยินดีต้อนรับ, {{ Auth::user()->username }}</p>
                <p class="mb-3">รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</p>
                <p class="mb-4">GPA รวมเกรด = <strong id="total-gpa">0.00</strong></p> <!-- เพิ่มส่วนนี้ -->
            @else
                <p class="mb-4">กรุณาเข้าสู่ระบบก่อนทำการบันทึกผลการเรียน</p>
            @endauth

            <!-- Section for Adding New Courses -->
            <div class="section-title">เพิ่มรายวิชาใหม่</div>
            <div class="card p-3 mb-4">
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        เพิ่มวิชาใหม่
                    </button>
                    <button type="button" class="btn btn-secondary btn-custom" id="resetTable">รีเซ็ตตาราง</button>
                </div>

                <!-- Semester Selection -->
                @auth
                    <div class="semester-select-container">
                        <div>
                            <label for="semester_term" class="form-label">ภาคการศึกษา</label>
                            <select class="form-control" name="semester_term" id="semester_term" required>
                                <option value="">เลือกภาค</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div>
                            <label for="semester_year" class="form-label">ปีการศึกษา</label>
                            <select class="form-control" name="semester_year" id="semester_year" required>
                                <option value="">เลือกปี</option>
                                @for ($year = 2565; $year <= 2570; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @endauth

                <form id="reportForm" action="{{ route('report.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="semester" id="semester_input">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th>
                                    <th>ชื่อวิชา</th>
                                    <th>หน่วยกิต</th>
                                    <th>เกรด</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody id="courses-table-body">
                                <tr>
                                    <td><input type="text" class="form-control course-id" name="id[]" required maxlength="8"></td>
                                    <td><input type="text" class="form-control" name="course_name[]" readonly></td>
                                    <td><input type="number" class="form-control" name="total_credits[]" readonly></td>
                                    <td>
                                        <select class="form-control grade-select" name="grade[]" required>
                                            <option value="">เลือกเกรด</option>
                                            <option value="4.0">A</option>
                                            <option value="3.5">B+</option>
                                            <option value="3.0">B</option>
                                            <option value="2.5">C+</option>
                                            <option value="2.0">C</option>
                                            <option value="1.5">D+</option>
                                            <option value="1.0">D</option>
                                            <option value="0.0">F</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-add-course btn-custom">+</button>
                                        <button type="button" class="btn btn-danger btn-remove-course btn-custom">ลบ</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="gpa-container">
                        <div class="gpa-info">
                            <div class="total-credits-added">
                                หน่วยกิตรวมที่เพิ่ม: <strong id="total-credits-added">0</strong>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-custom">บันทึกผลการเรียน</button>
                    </div>
                </form>
            </div>

            <!-- Section for Registered Courses -->
            <div class="grade-details-tab">
                <div class="section-title">รายวิชาที่ลงทะเบียนแล้ว</div>
                <div class="card p-3">
                    <ul class="nav nav-tabs" id="gradeTabs" role="tablist">
                        @for ($year = 2565; $year <= 2570; $year++)
                            <li class="nav-item">
                                <a class="nav-link {{ $year == 2565 ? 'active' : '' }}" id="tab-1-{{ $year }}" data-bs-toggle="tab" href="#semester-1-{{ $year }}" role="tab">1/{{ $year }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-2-{{ $year }}" data-bs-toggle="tab" href="#semester-2-{{ $year }}" role="tab">2/{{ $year }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-3-{{ $year }}" data-bs-toggle="tab" href="#semester-3-{{ $year }}" role="tab">3/{{ $year }}</a>
                            </li>
                        @endfor
                    </ul>
                    <div class="tab-content" id="gradeTabsContent">
                        @for ($year = 2565; $year <= 2570; $year++)
                            <!-- Tab for Semester 1 -->
                            <div class="tab-pane fade {{ $year == 2565 ? 'show active' : '' }}" id="semester-1-{{ $year }}" role="tabpanel">
                                <h5 class="text-center mt-3">ภาคการศึกษา 1/{{ $year }}</h5>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>รหัสวิชา</th>
                                            <th>ชื่อวิชา</th>
                                            <th>หน่วยกิต</th>
                                            <th>เกรด</th>
                                            <th>การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grade-table-1-{{ $year }}"></tbody>
                                </table>
                                <div class="summary-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>CR</th>
                                                <th>CP</th>
                                                <th>CA</th>
                                                <th>GP</th>
                                                <th class="highlight">GPA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="semester-cr-1-{{ $year }}">0</td>
                                                <td id="semester-cp-1-{{ $year }}">0</td>
                                                <td id="semester-ca-1-{{ $year }}">0</td>
                                                <td id="semester-gp-1-{{ $year }}">0.00</td>
                                                <td id="semester-gpa-1-{{ $year }}" class="highlight">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="abbreviations">
                                    <p><strong>คำย่อ:</strong></p>
                                    <p>CR = หน่วยกิตที่ลงทะเบียน (Credit Registered)</p>
                                    <p>CP = หน่วยกิตที่ผ่าน (Credit Passed)</p>
                                    <p>CA = หน่วยกิตที่ใช้คำนวณ GPA (Credit Attempt for GPA Calculation)</p>
                                    <p>GP = คะแนนรวม (สำหรับคำนวณ GPA) (Grade Point for GPA Calculation)</p>
                                    <p>GPA = เกรดเฉลี่ย (GP / CA) (Grade Point Average)</p>
                                    <p>(T) = เกรดที่โอนมา (Transfer Grade)</p>
                                </div>
                            </div>
                            <!-- Tab for Semester 2 -->
                            <div class="tab-pane fade" id="semester-2-{{ $year }}" role="tabpanel">
                                <h5 class="text-center mt-3">ภาคการศึกษา 2/{{ $year }}</h5>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>รหัสวิชา</th>
                                            <th>ชื่อวิชา</th>
                                            <th>หน่วยกิต</th>
                                            <th>เกรด</th>
                                            <th>การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grade-table-2-{{ $year }}"></tbody>
                                </table>
                                <div class="summary-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>CR</th>
                                                <th>CP</th>
                                                <th>CA</th>
                                                <th>GP</th>
                                                <th class="highlight">GPA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="semester-cr-2-{{ $year }}">0</td>
                                                <td id="semester-cp-2-{{ $year }}">0</td>
                                                <td id="semester-ca-2-{{ $year }}">0</td>
                                                <td id="semester-gp-2-{{ $year }}">0.00</td>
                                                <td id="semester-gpa-2-{{ $year }}" class="highlight">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="abbreviations">
                                    <p><strong>คำย่อ:</strong></p>
                                    <p>CR = หน่วยกิตที่ลงทะเบียน (Credit Registered)</p>
                                    <p>CP = หน่วยกิตที่ผ่าน (Credit Passed)</p>
                                    <p>CA = หน่วยกิตที่ใช้คำนวณ GPA (Credit Attempt for GPA Calculation)</p>
                                    <p>GP = คะแนนรวม (สำหรับคำนวณ GPA) (Grade Point for GPA Calculation)</p>
                                    <p>GPA = เกรดเฉลี่ย (GP / CA) (Grade Point Average)</p>
                                    <p>(T) = เกรดที่โอนมา (Transfer Grade)</p>
                                </div>
                            </div>
                            <!-- Tab for Semester 3 -->
                            <div class="tab-pane fade" id="semester-3-{{ $year }}" role="tabpanel">
                                <h5 class="text-center mt-3">ภาคการศึกษา 3/{{ $year }}</h5>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>รหัสวิชา</th>
                                            <th>ชื่อวิชา</th>
                                            <th>หน่วยกิต</th>
                                            <th>เกรด</th>
                                            <th>การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grade-table-3-{{ $year }}"></tbody>
                                </table>
                                <div class="summary-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>CR</th>
                                                <th>CP</th>
                                                <th>CA</th>
                                                <th>GP</th>
                                                <th class="highlight">GPA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="semester-cr-3-{{ $year }}">0</td>
                                                <td id="semester-cp-3-{{ $year }}">0</td>
                                                <td id="semester-ca-3-{{ $year }}">0</td>
                                                <td id="semester-gp-3-{{ $year }}">0.00</td>
                                                <td id="semester-gpa-3-{{ $year }}" class="highlight">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="abbreviations">
                                    <p><strong>คำย่อ:</strong></p>
                                    <p>CR = หน่วยกิตที่ลงทะเบียน (Credit Registered)</p>
                                    <p>CP = หน่วยกิตที่ผ่าน (Credit Passed)</p>
                                    <p>CA = หน่วยกิตที่ใช้คำนวณ GPA (Credit Attempt for GPA Calculation)</p>
                                    <p>GP = คะแนนรวม (สำหรับคำนวณ GPA) (Grade Point for GPA Calculation)</p>
                                    <p>GPA = เกรดเฉลี่ย (GP / CA) (Grade Point Average)</p>
                                    <p>(T) = เกรดที่โอนมา (Transfer Grade)</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Modal for Adding Course -->
            <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCourseModalLabel">เพิ่มวิชาใหม่</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addCourseForm" action="{{ route('courses.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">รหัสวิชา</label>
                                    <input type="text" id="course_id" name="id" class="form-control" required maxlength="8">
                                    <small id="idError" class="text-danger"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="course_name" class="form-label">ชื่อวิชา</label>
                                    <input type="text" id="course_name" name="course_name" class="form-control" required>
                                    <small id="nameError" class="text-danger"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="total_credits" class="form-label">หน่วยกิต</label>
                                    <input type="number" id="total_credits" name="total_credits" class="form-control" required min="1">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-custom">เพิ่มวิชา</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // อัปเดตค่า semester เมื่อเลือกภาคและปี
        function updateSemester() {
            const term = $('#semester_term').val();
            const year = $('#semester_year').val();
            if (term && year) {
                $('#semester_input').val(`${term}/${year}`);
                loadAcademicRecords(`${term}/${year}`, `#grade-table-${term}-${year}`);
            } else {
                $('#semester_input').val('');
                $('#courses-table-body').empty();
                addEmptyRow();
                calculateTotalCredits();
            }
        }

        $('#semester_term, #semester_year').on('change', updateSemester);

        // ฟังก์ชันดึงข้อมูลประวัติการลงทะเบียนและคำนวณสรุป
        function loadAcademicRecords(semester, targetTable) {
            const tableBody = $(targetTable);
            tableBody.empty();
            $.ajax({
                url: '{{ route("report.academic.records") }}',
                method: 'GET',
                data: { semester: semester },
                success: function (data) {
                    const semesterRecords = data.semester_records;

                    // แสดงข้อมูลรายวิชาในเทอมนี้
                    if (semesterRecords.length > 0) {
                        $.each(semesterRecords, function (index, record) {
                            const row = `
                                <tr data-record-id="${record.id}">
                                    <td>${record.course.id}</td>
                                    <td>${record.course.course_name}</td>
                                    <td>${record.course.total_credits}</td>
                                    <td>${record.grade}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-remove-course btn-custom">ลบ</button>
                                    </td>
                                </tr>`;
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>');
                    }

                    // คำนวณสรุปเทอมนี้
                    let semesterCR = 0, semesterCP = 0, semesterCA = 0, semesterGP = 0;
                    $.each(semesterRecords, function (index, record) {
                        const credits = record.course.total_credits;
                        const gpa = record.gpa;

                        semesterCR += credits; // หน่วยกิตที่ลง
                        if (gpa >= 1.0) semesterCP += credits; // หน่วยกิตที่ผ่าน (เกรด D ขึ้นไป)
                        semesterCA += credits; // หน่วยกิตที่คำนวณ
                        semesterGP += gpa * credits; // คะแนนรวม
                    });
                    const semesterGPA = semesterCA > 0 ? (semesterGP / semesterCA).toFixed(2) : '0.00';

                    $(`#semester-cr-${semester.replace('/', '-')}`).text(semesterCR);
                    $(`#semester-cp-${semester.replace('/', '-')}`).text(semesterCP);
                    $(`#semester-ca-${semester.replace('/', '-')}`).text(semesterCA);
                    $(`#semester-gp-${semester.replace('/', '-')}`).text(semesterGP.toFixed(2));
                    $(`#semester-gpa-${semester.replace('/', '-')}`).text(semesterGPA);
                },
                error: function (xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงข้อมูลผลการเรียนได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                    tableBody.append('<tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>');
                    $(`#semester-cr-${semester.replace('/', '-')}`).text('0');
                    $(`#semester-cp-${semester.replace('/', '-')}`).text('0');
                    $(`#semester-ca-${semester.replace('/', '-')}`).text('0');
                    $(`#semester-gp-${semester.replace('/', '-')}`).text('0.00');
                    $(`#semester-gpa-${semester.replace('/', '-')}`).text('0.00');
                }
            });
        }

        // โหลดข้อมูลเริ่มต้นสำหรับทุกภาคการศึกษา
        @for ($year = 2565; $year <= 2570; $year++)
            loadAcademicRecords('1/{{ $year }}', '#grade-table-1-{{ $year }}');
            loadAcademicRecords('2/{{ $year }}', '#grade-table-2-{{ $year }}');
            loadAcademicRecords('3/{{ $year }}', '#grade-table-3-{{ $year }}');
        @endfor

        // ฟังก์ชันคำนวณ GPA รวมจากทุกเทอม
        function calculateTotalGPA() {
            let totalGradePoints = 0;
            let totalCreditsAttempted = 0;

            @for ($year = 2565; $year <= 2570; $year++)
                ['1/{{ $year }}', '2/{{ $year }}', '3/{{ $year }}'].forEach(function(semester) {
                    $.ajax({
                        url: '{{ route("report.academic.records") }}',
                        method: 'GET',
                        data: { semester: semester },
                        async: false, // รอผลลัพธ์ก่อนคำนวณต่อ
                        success: function(data) {
                            const semesterRecords = data.semester_records;
                            $.each(semesterRecords, function(index, record) {
                                const credits = record.course.total_credits;
                                const gpa = record.gpa;
                                totalCreditsAttempted += credits;
                                totalGradePoints += gpa * credits;
                            });
                        },
                        error: function(xhr) {
                            console.error('ไม่สามารถดึงข้อมูลผลการเรียนสำหรับ ' + semester + ': ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'));
                        }
                    });
                });
            @endfor

            const totalGPA = totalCreditsAttempted > 0 ? (totalGradePoints / totalCreditsAttempted).toFixed(2) : '0.00';
            $('#total-gpa').text(totalGPA); // อัปเดตค่า GPA รวมใน UI
        }

        // เรียกคำนวณ GPA รวมเมื่อโหลดหน้า
        calculateTotalGPA();

        // ฟังก์ชันเพิ่มแถวเปล่าสำหรับกรอกข้อมูลใหม่
        function addEmptyRow() {
            const newRow = `
                <tr>
                    <td><input type="text" class="form-control course-id" name="id[]" required maxlength="8"></td>
                    <td><input type="text" class="form-control" name="course_name[]" readonly></td>
                    <td><input type="number" class="form-control" name="total_credits[]" readonly></td>
                    <td>
                        <select class="form-control grade-select" name="grade[]" required>
                            <option value="">เลือกเกรด</option>
                            <option value="4.0">A</option>
                            <option value="3.5">B+</option>
                            <option value="3.0">B</option>
                            <option value="2.5">C+</option>
                            <option value="2.0">C</option>
                            <option value="1.5">D+</option>
                            <option value="1.0">D</option>
                            <option value="0.0">F</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-success btn-add-course btn-custom">+</button>
                        <button type="button" class="btn btn-danger btn-remove-course btn-custom">ลบ</button>
                    </td>
                </tr>`;
            $('#courses-table-body').append(newRow);
            setupAutocomplete();
            calculateTotalCredits();
        }

        // ฟังก์ชัน Autocomplete สำหรับช่องรหัสวิชา
        function setupAutocomplete() {
            $('.course-id').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('courses.autocomplete') }}',
                        data: { term: request.term },
                        success: function (data) {
                            response(data.map(course => ({
                                label: course.id + ' - ' + course.course_name,
                                value: course.id,
                                course_name: course.course_name,
                                total_credits: course.total_credits
                            })));
                        },
                        error: function () {
                            Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงรายการวิชาได้', 'error');
                        }
                    });
                },
                select: function (event, ui) {
                    const row = $(this).closest('tr');
                    row.find('input[name="course_name[]"]').val(ui.item.course_name);
                    row.find('input[name="total_credits[]"]').val(ui.item.total_credits);
                    calculateTotalCredits();
                },
                minLength: 1
            });
        }

        // ฟังก์ชันคำนวณหน่วยกิตรวม
        function calculateTotalCredits(targetTable = '#courses-table-body') {
            let totalCredits = 0;
            $(targetTable + ' tr').each(function () {
                const credits = parseFloat($(this).find('input[name="total_credits[]"]').val()) || 0;
                totalCredits += credits;
            });
            $('#total-credits-added').text(totalCredits);
            return totalCredits;
        }

        // ฟังก์ชันลบแถว
        $(document).on('click', '.btn-remove-course', function () {
            const row = $(this).closest('tr');
            const recordId = row.data('record-id');

            if (recordId) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณต้องการลบวิชานี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f36d21',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'ใช่, ลบ!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/report/delete-course/${recordId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    row.remove();
                                    Swal.fire('สำเร็จ!', response.message, 'success');
                                    const semester = row.closest('.tab-pane').attr('id').replace('semester-', '');
                                    loadAcademicRecords(semester, `#grade-table-${semester}`);
                                    calculateTotalGPA(); // อัปเดต GPA หลังลบ
                                } else {
                                    Swal.fire('ผิดพลาด!', response.message, 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('ผิดพลาด!', 'ไม่สามารถลบวิชาได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                            }
                        });
                    }
                });
            } else {
                row.remove();
                calculateTotalCredits();
                if ($('#courses-table-body tr').length === 0) {
                    addEmptyRow();
                }
            }
        });

        // รีเซ็ตตาราง
        $('#resetTable').on('click', function () {
            $('#courses-table-body').empty();
            addEmptyRow();
            calculateTotalCredits();
        });

        // เพิ่มวิชาใหม่ใน Modal
        $('#addCourseForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'เพิ่มวิชาใหม่!',
                text: "คุณต้องการเพิ่มวิชาใหม่หรือไม่?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#f36d21',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'ใช่, เพิ่ม!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#addCourseModal').modal('hide');
                            $('#addCourseForm')[0].reset();
                            Swal.fire('สำเร็จ!', 'วิชาใหม่ถูกเพิ่มแล้ว!', 'success');
                            $('.course-id').autocomplete('destroy');
                            setupAutocomplete();
                            calculateTotalGPA(); // อัปเดต GPA หลังเพิ่มวิชา
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON?.errors;
                            if (errors) {
                                $('#idError').text(errors.id || '');
                                $('#nameError').text(errors.course_name || '');
                            } else {
                                Swal.fire('ผิดพลาด!', 'ไม่สามารถเพิ่มวิชาได้ อาจมีรหัสวิชาหรือชื่อวิชาซ้ำ', 'error');
                            }
                        }
                    });
                }
            });
        });

        // ยืนยันการบันทึกผลการเรียน
        $('#reportForm').on('submit', function(e) {
            e.preventDefault();
            const semester = $('#semester_input').val();
            if (!semester) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกภาคการศึกษาและปีการศึกษาก่อน', 'warning');
                return;
            }

            const courseIds = [];
            let hasDuplicate = false;
            const totalCredits = calculateTotalCredits();

            $('#courses-table-body tr').each(function() {
                const courseId = $(this).find('input[name="id[]"]').val();
                if (courseId) {
                    if (courseIds.includes(courseId)) {
                        hasDuplicate = true;
                        return false;
                    }
                    courseIds.push(courseId);
                }
            });

            if (hasDuplicate) {
                Swal.fire('คำเตือน!', 'มีวิชาที่ซ้ำกันในรายการเพิ่มวิชาใหม่ กรุณาตรวจสอบ', 'warning');
                return;
            }

            if (totalCredits > 22) {
                Swal.fire('คำเตือน!', 'หน่วยกิตรวมเกิน 22 หน่วยกิต ไม่สามารถบันทึกได้', 'warning');
                return;
            }

            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "คุณต้องการบันทึกผลการเรียนนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f36d21',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'ใช่, บันทึก!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                    $('#courses-table-body').empty();
                    addEmptyRow();
                    calculateTotalGPA(); // อัปเดต GPA หลังบันทึก
                }
            });
        });

        // เรียกใช้ Autocomplete ครั้งแรก
        setupAutocomplete();

        // เพิ่มแถวใหม่เมื่อกดปุ่ม +
        $(document).on('click', '.btn-add-course', function () {
            addEmptyRow();
        });

        // เรียกคำนวณ GPA รวมทุกครั้งที่โหลดหน้าใหม่หรืออัปเดตข้อมูล
        $('a[data-bs-toggle="tab"]').on('click', function () {
            calculateTotalGPA();
        });
    });
    </script>
</body>
</html>