<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดเอกสาร</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container mt-5">
            <h2 class="text-center">อัปโหลดเอกสาร</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <a href="{{ asset('storage/uploads/' . session('file')) }}" target="_blank">ดาวน์โหลด</a>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <p>ประเภทไฟล์ที่อนุญาต: PDF, DOCX, JPEG</p>

            <form action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                @for ($i = 1; $i <= 5; $i++)
                    <div class="row mb-3">
                        <div class="col">
                            <label for="title{{ $i }}" class="form-label">ชื่อไฟล์ {{ $i }}</label>
                            <input type="text" name="titles[]" class="form-control" id="title{{ $i }}" 
                            placeholder="กรอกชื่อ" 
                            value="{{ 
                                $i == 1 ? 'สหกิจ' : 
                                ($i == 2 ? 'KKUDQ' : 
                                ($i == 3 ? 'KKU KEPT' : 
                                ($i == 4 ? 'ฝึกงาน' : 
                                'ผลการศึกษา'))) 
                            }}" readonly required>
                        </div>
                        <div class="col">
                            <label for="file{{ $i }}" class="form-label">เลือกไฟล์ {{ $i }}</label>
                            <input type="file" name="files[]" class="form-control" id="file{{ $i }}" 
                            accept=".pdf,.docx,.jpeg,.jpg,.png" required onchange="validateFile(this)">
                            <div class="feedback-wrapper">
                                <div class="valid-feedback">ไฟล์ถูกต้อง</div>
                                <div class="invalid-feedback">ประเภทไฟล์ไม่ถูกต้อง</div>
                            </div>
                            @error('files.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endfor

                <div class="text-end">
                    <button type="submit" class="btn btn-custom">อัปโหลด</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateFile(input) {
            const filePath = input.value;
            const allowedExtensions = /(\.pdf|\.docx|\.jpeg|\.jpg|\.png)$/i;
            const feedbackValid = input.nextElementSibling.querySelector('.valid-feedback');
            const feedbackInvalid = input.nextElementSibling.querySelector('.invalid-feedback');

            feedbackValid.style.display = 'none';
            feedbackInvalid.style.display = 'none';

            if (allowedExtensions.exec(filePath)) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
                feedbackValid.style.display = 'block';
                feedbackInvalid.style.display = 'none';
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                feedbackValid.style.display = 'none';
                feedbackInvalid.style.display = 'block';
                input.value = ''; // Clear the input if invalid
            }
        }
    </script>

</body>
</html>
