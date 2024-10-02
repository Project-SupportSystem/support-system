<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
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
            height: 80px;
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
            padding-top: 10px;
            position: fixed;
            left: 0;
            top: 80px;
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

        h2 {
            color: black; /* เปลี่ยนสีเป็นสีดำ */
        }

        .btn-custom {
            background-color: #007bff;
            color: #ffffff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-logo">
            <img src="C:\Users\ACER\Desktop\example-app\img\KKU_Logo.png" alt="โลโก้ระบบ">
        </div>
        <div class="navbar-user">ชื่อผู้ใช้: นักศึกษา</div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#">อัปโหลดเอกสาร</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container mt-5">
            <h2>อัปโหลดเอกสาร</h2>

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

                <div class="mb-3">
                    <h5>อัปโหลดไฟล์</h5>
                </div>

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
