<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดเอกสาร - มหาวิทยาลัยขอนแก่น</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
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
            margin-bottom: 30px; /* เพิ่มระยะห่างระหว่างกรอบ */
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
            background-color: #f36d21;
            border: none;
        }
        .btn-primary:hover {
            background-color: #d85b1b;
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
        .status-section {
            margin-top: 20px;
        }
        .status-section .form-control {
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }
        .status-text {
            margin-left: 10px;
            font-weight: bold;
        }
        .status-text.pass {
            color: #28a745;
        }
        .status-text.fail {
            color: #dc3545;
        }
        .file-list {
            margin-top: 10px;
        }
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .file-item a {
            margin-right: 10px;
        }
        .file-item .remove-icon {
            cursor: pointer;
            color: #dc3545;
        }
        .file-hint {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
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
            .file-hint {
                color: #adb5bd;
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

    <div class="sidebar">
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
        <a href="{{ route('student-profile') }}">ประวัตินักศึกษา</a>
    </div>

    <div class="content flex-grow-1 p-4">
        <div class="container">
            <h2 class="text-center mb-4">อัปโหลดเอกสาร</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @auth
                <p class="mb-3">ยินดีต้อนรับ, {{ Auth::user()->username }}</p>
                <p class="mb-4">รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</p>
            @else
                <p class="mb-4">กรุณาเข้าสู่ระบบก่อนทำการอัปโหลดเอกสาร</p>
            @endauth

            <!-- Section for Uploading Documents -->
            <div class="section-title">อัปโหลดเอกสาร</div>

            <!-- กรอบสำหรับสหกิจศึกษา -->
            <div class="card">
                <h5 class="mb-3">สหกิจศึกษา</h5>
                <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" class="upload-form" data-type="coop">
                    @csrf
                    <input type="hidden" name="titles[]" value="สหกิจศึกษา">
                    <div class="mb-3">
                        <label class="form-label">อัปโหลดไฟล์</label>
                        <input type="file" name="files[0][]" class="form-control doc-upload" accept=".pdf,.docx" multiple>
                        <div class="file-hint">
                            <p>ไฟล์ที่ยอมรับได้: <strong>.pdf, .docx</strong> (รายงานสหกิจศึกษา)</p>
                            <p>ขนาดสูงสุด: <strong>10 MB</strong></p>
                        </div>
                    </div>
                    <div id="fileListCoop" class="file-list"></div>
                    <!-- ตารางแสดงไฟล์ที่อัปโหลดแล้วสำหรับสหกิจศึกษา -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อไฟล์</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $uploadedFiles = Cache::get('uploaded_files', []);
                                @endphp
                                @foreach ($documents->where('document_type', 'coop_project') as $document)
                                    <tr>
                                        <td><a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $uploadedFiles[$document->id] ?? basename($document->file_path) }}</a></td>
                                        <td>
                                            <a href="{{ route('document.delete', $document->id) }}" class="btn btn-danger btn-sm btn-custom delete-btn" data-id="{{ $document->id }}">ลบ</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($documents->where('document_type', 'coop_project')->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">ไม่มีเอกสาร</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-custom">อัปโหลดสหกิจศึกษา</button>
                    </div>
                </form>
            </div>

            <!-- กรอบสำหรับโปรเจค -->
            <div class="card">
                <h5 class="mb-3">โปรเจค</h5>
                <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" class="upload-form" data-type="project">
                    @csrf
                    <input type="hidden" name="titles[]" value="โปรเจค">
                    <div class="mb-3">
                        <label class="form-label">อัปโหลดไฟล์</label>
                        <input type="file" name="files[0][]" class="form-control doc-upload" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple>
                        <div class="file-hint">
                            <p>ไฟล์ที่ยอมรับได้: <strong>.pdf, .docx, .jpeg, .jpg, .png</strong> (เอกสารหรือภาพประกอบ)</p>
                            <p>ขนาดสูงสุด: <strong>10 MB</strong></p>
                        </div>
                    </div>
                    <div id="fileListProject" class="file-list"></div>
                    <!-- ตารางแสดงไฟล์ที่อัปโหลดแล้วสำหรับโปรเจค -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อไฟล์</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents->where('document_type', 'project') as $document)
                                    <tr>
                                        <td><a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $uploadedFiles[$document->id] ?? basename($document->file_path) }}</a></td>
                                        <td>
                                            <a href="{{ route('document.delete', $document->id) }}" class="btn btn-danger btn-sm btn-custom delete-btn" data-id="{{ $document->id }}">ลบ</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($documents->where('document_type', 'project')->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">ไม่มีเอกสาร</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-custom">อัปโหลดโปรเจค</button>
                    </div>
                </form>
            </div>

            <!-- กรอบสำหรับฝึกงาน -->
            <div class="card">
                <h5 class="mb-3">ฝึกงาน</h5>
                <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" class="upload-form" data-type="internship">
                    @csrf
                    <input type="hidden" name="titles[]" value="internship">
                    <div class="mb-3">
                        <label class="form-label">อัปโหลดไฟล์</label>
                        <input type="file" name="files[0][]" class="form-control doc-upload" accept=".pdf,.docx" multiple>
                        <div class="file-hint">
                            <p>ไฟล์ที่ยอมรับได้: <strong>.pdf, .docx</strong> (รายงานฝึกงาน)</p>
                            <p>ขนาดสูงสุด: <strong>10 MB</strong></p>
                        </div>
                    </div>
                    <div id="fileListInternship" class="file-list"></div>
                    <!-- ตารางแสดงไฟล์ที่อัปโหลดแล้วสำหรับฝึกงาน -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อไฟล์</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents->where('document_type', 'internship') as $document)
                                    <tr>
                                        <td><a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $uploadedFiles[$document->id] ?? basename($document->file_path) }}</a></td>
                                        <td>
                                            <a href="{{ route('document.delete', $document->id) }}" class="btn btn-danger btn-sm btn-custom delete-btn" data-id="{{ $document->id }}">ลบ</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($documents->where('document_type', 'internship')->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">ไม่มีเอกสาร</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-custom">อัปโหลดฝึกงาน</button>
                    </div>
                </form>
            </div>

            <!-- Section for Saving Status -->
            <div class="section-title">บันทึกสถานะ</div>
            <div class="card p-3 status-section">
                <form action="{{ route('save.status') }}" method="POST" id="statusForm">
                    @csrf
                    <div class="mb-3">
                        <label for="kkudqStatus" class="form-label">สถานะ KKUDQ</label>
                        <select name="kkudq_status" id="kkudqStatus" class="form-control">
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="pass" {{ $documents->where('document_type', 'kku_dq')->first()?->status == 'pass' ? 'selected' : '' }}>ผ่าน</option>
                            <option value="fail" {{ $documents->where('document_type', 'kku_dq')->first()?->status == 'fail' ? 'selected' : '' }}>ไม่ผ่าน</option>
                        </select>
                        @php
                            $kkudqStatus = $documents->where('document_type', 'kku_dq')->first()?->status;
                            $kkudqStatusText = $kkudqStatus ? ($kkudqStatus == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน') : '';
                            $kkudqStatusClass = $kkudqStatus == 'fail' ? 'fail' : ($kkudqStatus == 'pass' ? 'pass' : '');
                        @endphp
                        @if($kkudqStatusText)
                            <span class="status-text {{ $kkudqStatusClass }}"> (สถานะปัจจุบัน: {{ $kkudqStatusText }})</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="kkuKeptStatus" class="form-label">สถานะ KKU KEPT</label>
                        <select name="kku_kept_status" id="kkuKeptStatus" class="form-control">
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="pass" {{ $documents->where('document_type', 'kku_kept')->first()?->status == 'pass' ? 'selected' : '' }}>ผ่าน</option>
                            <option value="fail" {{ $documents->where('document_type', 'kku_kept')->first()?->status == 'fail' ? 'selected' : '' }}>ไม่ผ่าน</option>
                        </select>
                        @php
                            $kkuKeptStatus = $documents->where('document_type', 'kku_kept')->first()?->status;
                            $kkuKeptStatusText = $kkuKeptStatus ? ($kkuKeptStatus == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน') : '';
                            $kkuKeptStatusClass = $kkuKeptStatus == 'fail' ? 'fail' : ($kkuKeptStatus == 'pass' ? 'pass' : '');
                        @endphp
                        @if($kkuKeptStatusText)
                            <span class="status-text {{ $kkuKeptStatusClass }}"> (สถานะปัจจุบัน: {{ $kkuKeptStatusText }})</span>
                        @endif
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-custom">บันทึกสถานะ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.doc-upload').forEach(input => {
            input.addEventListener('change', function(e) {
                const formType = this.closest('form').dataset.type;
                addFiles(this, `fileList${formType.charAt(0).toUpperCase() + formType.slice(1)}`);
            });
        });

        function addFiles(input, fileListId) {
            const fileList = document.getElementById(fileListId);
            fileList.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const fileWrapper = document.createElement('div');
                fileWrapper.classList.add('file-item');
                const link = document.createElement('a');
                link.href = URL.createObjectURL(file);
                link.target = '_blank';
                link.textContent = file.name;
                fileWrapper.appendChild(link);

                const removeIcon = document.createElement('span');
                removeIcon.classList.add('remove-icon');
                removeIcon.innerHTML = '×';
                removeIcon.onclick = () => {
                    fileWrapper.remove();
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach(f => {
                        if (f !== file) dt.items.add(f);
                    });
                    input.files = dt.files;
                };
                fileWrapper.appendChild(removeIcon);
                fileList.appendChild(fileWrapper);
            });
        }

        document.querySelectorAll('.upload-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const filesInput = this.querySelector('.doc-upload');
                if (filesInput.files.length === 0) {
                    Swal.fire('คำเตือน!', 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด', 'warning');
                    return;
                }

                // แปลง data-type เป็นชื่อที่เหมาะสมสำหรับ SweetAlert
                const formType = this.dataset.type;
                let formTitle = '';
                switch (formType) {
                    case 'coop':
                        formTitle = 'สหกิจศึกษา';
                        break;
                    case 'project':
                        formTitle = 'โปรเจค';
                        break;
                    case 'internship':
                        formTitle = 'ฝึกงาน';
                        break;
                    default:
                        formTitle = 'เอกสาร';
                }

                Swal.fire({
                    title: 'ยืนยันการอัปโหลด?',
                    text: `คุณต้องการอัปโหลดไฟล์สำหรับ${formTitle}หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f36d21',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, อัปโหลด!',
                    cancelButtonText: 'ยกเลิก'
                }).then(result => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        document.getElementById("statusForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const kkudqStatus = document.getElementById("kkudqStatus").value;
            const kkuKeptStatus = document.getElementById("kkuKeptStatus").value;

            if (!kkudqStatus && !kkuKeptStatus) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกสถานะอย่างน้อยหนึ่งรายการ (KKUDQ หรือ KKU KEPT)', 'warning');
                return;
            }

            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "คุณต้องการบันทึกสถานะนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f36d21',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, บันทึก!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const documentId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณต้องการลบไฟล์นี้หรือไม่? การกระทำนี้ไม่สามารถกู้คืนได้",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, ลบ!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = this.href; // รีเฟรชหน้าเมื่อลบสำเร็จ
                    }
                });
            });
        });
    </script>
</body>
</html>