<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลนักศึกษา</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> <!-- ลิงก์ไปยัง custom.css -->
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>

    <!-- Navbar -->
    <div class="navbar d-flex justify-content-between align-items-center p-2 bg-light">
        <div class="navbar-logo">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ระบบ" style="height: 50px;">
        </div>

        <!-- User Icon Dropdown -->
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
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                </li>
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
        <div class="container mt-5">
            <h2 class="text-center">บันทึกข้อมูลนักศึกษา</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('student.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">เบอร์โทร</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>

                <div class="mb-3">
                    <label for="school_location" class="form-label">จบการศึกษาจาก</label>
                    <input type="text" class="form-control" id="school_location" name="school_location">
                </div>

                <div class="mb-3">
                    <label for="advisor_id" class="form-label">อาจารย์ที่ปรึกษา</label>
                    <select class="form-control" id="advisor_id" name="advisor_id" required>
                        <option value="">-- เลือกอาจารย์ที่ปรึกษา --</option>
                        @foreach ($advisors as $advisor)
                            <option value="{{ $advisor->id }}">{{ $advisor->first_name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-custom">บันทึกข้อมูล</button>
            </form>
        </div>
    </div>

    <script>
        // การแจ้งเตือนด้วย SweetAlert เมื่อทำการบันทึกข้อมูล
        $('form').on('submit', function(e) {
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
    </script>

</body>
</html>
