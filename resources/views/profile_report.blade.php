<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลนักศึกษา - มหาวิทยาลัยขอนแก่น</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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
            color: #f36d21;
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
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .disabled-input {
            background-color: #e9ecef;
            opacity: 0.7;
        }
        .btn-custom {
            margin-right: 10px;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #f36d21;
            border: none;
        }
        .btn-primary:hover {
            background-color: #d85b1b;
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
        .alert-success {
            border-radius: 8px;
            margin-bottom: 20px;
        }
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
            .form-control {
                background-color: #3c3c3c;
                border-color: #555;
                color: #d1d1d1;
            }
            .disabled-input {
                background-color: #444444;
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
            .alert-success {
                background-color: #28a745;
                color: #ffffff;
            }
        }
    </style>
</head>
<body>
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
                <li>
                    <a class="dropdown-item" href="#">
                        {{ Auth::user()->username ?? 'ไม่พบข้อมูลผู้ใช้' }} 
                        ({{ Auth::user()->role ?? 'ไม่มีสิทธิ์' }})
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
            </ul>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <div class="content flex-grow-1 p-4">
        <div class="container">
            <h2 class="text-center mb-4">บันทึกข้อมูลนักศึกษา</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <p class="mb-3">ยินดีต้อนรับ, {{ Auth::user()->username }}</p>
                <p class="mb-4">รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</p>
            @else
                <p class="mb-4">กรุณาเข้าสู่ระบบก่อนทำการบันทึกข้อมูลนักศึกษา</p>
            @endauth

            <div class="section-title">ข้อมูลนักศึกษา</div>
            <div class="card">
                <form action="{{ isset($student) ? route('student.update', $student->id) : route('student.store') }}" method="POST" id="studentForm">
                    @csrf
                    @if (isset($student))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="student_id" class="form-label">รหัสนักศึกษา</label>
                        <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="student_id" name="student_id" 
                               value="{{ old('student_id', $student->id ?? '') }}" {{ isset($student) ? 'readonly' : '' }} required>
                    </div>

                    <div class="mb-3">
                        <label for="first_name" class="form-label">ชื่อจริง</label>
                        <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="first_name" name="first_name" 
                               value="{{ old('first_name', $student->first_name ?? '') }}" {{ isset($student) ? 'readonly' : '' }} required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">เบอร์โทร</label>
                        <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="phone" name="phone" 
                               value="{{ old('phone', $student->phone ?? '') }}" {{ isset($student) ? 'readonly' : '' }} required>
                    </div>

                    <div class="mb-3">
                        <label for="school_location" class="form-label">จบการศึกษาจาก</label>
                        <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="school_location" name="school_location" 
                               value="{{ old('school_location', $student->school_location ?? '') }}" {{ isset($student) ? 'readonly' : '' }} required>
                    </div>

                    <div class="mb-3">
                        <label for="province" class="form-label">จังหวัด</label>
                        <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="province" name="province" 
                               value="{{ old('province', $student->province ?? '') }}" {{ isset($student) ? 'readonly' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="advisor_id" class="form-label">อาจารย์ที่ปรึกษา</label>
                        <select class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="advisor_id" name="advisor_id" {{ isset($student) ? 'disabled' : '' }} required>
                            <option value="">-- เลือกอาจารย์ที่ปรึกษา --</option>
                            @foreach($advisors as $advisor)
                                <option value="{{ $advisor->id }}" {{ (old('advisor_id', $student->advisor_id ?? '') == $advisor->id) ? 'selected' : '' }}>
                                    {{ $advisor->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('student-profile') }}" class="btn btn-secondary btn-custom" id="backButton" style="display:{{ isset($student) ? 'inline-block' : 'none' }}">ย้อนกลับ</a>
                        <button type="submit" class="btn btn-success btn-custom" id="saveButton" style="display:{{ isset($student) ? 'inline-block' : 'none' }}">บันทึกข้อมูลนักศึกษาที่แก้ไข</button>
                        <button type="button" class="btn btn-primary btn-custom" id="editButton" style="display:{{ isset($student) ? 'inline-block' : 'none' }}">แก้ไขข้อมูลนักศึกษา</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // ลบการเรียก loadAdvisorsForDropdown เพราะใช้ Blade ในการแสดงข้อมูลแล้ว
            $('#editButton').on('click', function() {
                $('.form-control').removeAttr('readonly').removeClass('disabled-input');
                $('#advisor_id').prop('disabled', false).removeClass('disabled-input');
                $(this).hide();
                $('#backButton').show();
                $('#saveButton').show();
                console.log('Edit button clicked');
            });

            $('#backButton').on('click', function() {
                window.location.href = "{{ route('student-profile') }}";
            });

            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: "คุณต้องการบันทึกข้อมูลนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f36d21',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, บันทึก!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('action'),
                            type: $(this).attr('method'),
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire('สำเร็จ!', 'บันทึกข้อมูลเรียบร้อยแล้ว!', 'success').then(() => {
                                    window.location.href = "{{ route('student-profile') }}";
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('ผิดพลาด!', 'ไม่สามารถบันทึกข้อมูลได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>