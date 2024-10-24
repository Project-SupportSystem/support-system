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
                    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h2 class="text-center">บันทึกข้อมูลนักศึกษา</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('student.update', $student->id ?? '') }}" method="POST" id="studentForm">
                @csrf
                @method('PUT') <!-- ใช้ PUT สำหรับการอัปเดตข้อมูล -->

                <div class="mb-3">
                    <label for="student_id" class="form-label">รหัสนักศึกษา</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" 
                           value="{{ old('student_id', $student->id ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="{{ old('first_name', $student->first_name ?? '') }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">เบอร์โทร</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone', $student->phone ?? '') }}" readonly required >
                </div>

                <div class="mb-3">
                    <label for="school_location" class="form-label">จบการศึกษาจาก</label>
                    <input type="text" class="form-control" id="school_location" name="school_location" 
                           value="{{ old('school_location', $student->school_location ?? '') }}" readonly required >
                </div>

                <div class="mb-3">
                    <label for="advisor_id" class="form-label">อาจารย์ที่ปรึกษา</label>
                    <select class="form-control" id="advisor_id" name="advisor_id" readonly required>
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
                    <button type="button" class="btn btn-primary" id="editButton">แก้ไข</button>
                    <button type="submit" class="btn btn-success" style="display:none;">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#editButton').click(function() {
                // เปิดการแก้ไข
                $('input, select').removeAttr('readonly').removeAttr('disabled');

                // ซ่อนปุ่มแก้ไข และแสดงปุ่มบันทึก
                $(this).hide();
                $('button[type=submit]').show();
            });

            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: 'คุณต้องการบันทึกข้อมูลนักศึกษานี้หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, บันทึกเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>

</body>
</html>
