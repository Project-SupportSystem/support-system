<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลนักศึกษา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-custom {
            margin-right: 10px;
        }
        .disabled-input {
            background-color: #e9ecef;
            opacity: 0.7;
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

        <!-- Section for Student Information -->
        <div class="section-title">ข้อมูลนักศึกษา</div>
        <div class="card">
            <form action="{{ isset($student) ? route('student.update', $student->id) : route('student.store') }}" method="POST">
                @csrf
                @if (isset($student))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="student_id" class="form-label">รหัสนักศึกษา</label>
                    <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="student_id" name="student_id" 
                           value="{{ old('student_id', $student->id ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="first_name" name="first_name" 
                           value="{{ old('first_name', $student->first_name ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">เบอร์โทร</label>
                    <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="phone" name="phone" 
                           value="{{ old('phone', $student->phone ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="school_location" class="form-label">จบการศึกษาจาก</label>
                    <input type="text" class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="school_location" name="school_location" 
                           value="{{ old('school_location', $student->school_location ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="advisor_id" class="form-label">อาจารย์ที่ปรึกษา</label>
                    <select class="form-control {{ !isset($student) ? '' : 'disabled-input' }}" id="advisor_id" name="advisor_id" disabled required>
                        <option value="">-- เลือกอาจารย์ที่ปรึกษา --</option>
                        @foreach ($advisors as $advisor)
                            <option value="{{ $advisor->id }}" 
                                    {{ (old('advisor_id', $student->advisor_id ?? '') == $advisor->id) ? 'selected' : '' }}>
                                {{ $advisor->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <a href="{{ route('student-profile') }}" class="btn btn-secondary btn-custom" id="backButton" style="display:none;">ย้อนกลับ</a>
                    <button type="submit" class="btn btn-success btn-custom" id="saveButton" style="display:none;">บันทึกข้อมูลนักศึกษาที่แก้ไข</button>
                    <button type="button" class="btn btn-primary btn-custom" id="editButton">แก้ไขข้อมูลนักศึกษา</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#editButton').click(function() {
            $('input').removeAttr('readonly').removeClass('disabled-input');
            $('select').prop('disabled', false).removeClass('disabled-input');
            $(this).hide();
            $('#backButton').show();
            $('#saveButton').show();
        });

        $('#backButton').click(function() {
            window.location.href = "{{ route('student-profile') }}";
        });
    });
</script>

</body>
</html>