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
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
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
            color: #28a745; /* สีเขียวสำหรับ "ผ่าน" */
        }
        .status-text.fail {
            color: #dc3545; /* สีแดงสำหรับ "ไม่ผ่าน" */
        }
        .status-section .btn {
            margin-top: 10px;
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
        <div class="container mt-5">
            <h2 class="text-center mb-4">อัปโหลดเอกสาร</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @auth
                <p>ยินดีต้อนรับ, {{ Auth::user()->username }}</p>
                <p>รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</p>
            @else
                <p>กรุณาเข้าสู่ระบบก่อนทำการอัปโหลดเอกสาร</p>
            @endauth

            <!-- Section for Uploading Documents -->
            <div class="section-title">อัปโหลดเอกสาร</div>
            <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="card p-3 mb-4">
                    <div class="mb-3">
                        <label for="docTypeSelect" class="form-label">เลือกประเภทเอกสาร</label>
                        <select name="titles[]" class="form-select" id="docTypeSelect">
                            <option value="" selected>-- เลือกประเภทเอกสาร --</option>
                            <option value="สหกิจศึกษา">สหกิจศึกษา</option>
                            <option value="โปรเจค">โปรเจค</option>
                            <option value="internship">ฝึกงาน</option>
                            <option value="transcript">ผลการศึกษา</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="docUpload" class="form-label">เลือกไฟล์</label>
                        <input type="file" name="files[0][]" id="docUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple>
                    </div>
                    <div id="fileList0" class="file-list"></div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">อัปโหลด</button>
                    </div>
                </div>
            </form>

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
                        <button type="submit" class="btn btn-success">บันทึกสถานะ</button>
                    </div>
                </form>
            </div>

            <!-- Section for Uploaded Files -->
            <div class="section-title">ไฟล์ที่อัปโหลดแล้ว</div>
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ประเภทเอกสาร</th>
                                <th>ชื่อไฟล์</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $uploadedFiles = Cache::get('uploaded_files', []);
                            @endphp
                            @foreach ($documents as $document)
                                @if (in_array($document->document_type, ['coop_project', 'project', 'internship', 'transcript']))
                                    <tr>
                                        @php
                                            $documentType = match ($document->document_type) {
                                                'coop_project' => 'สหกิจศึกษา',
                                                'project' => 'โปรเจค',
                                                'internship' => 'ฝึกงาน',
                                                'transcript' => 'ผลการศึกษา',
                                                default => ucfirst($document->document_type),
                                            };
                                            $fileName = $uploadedFiles[$document->id] ?? basename($document->file_path);
                                        @endphp
                                        <td>{{ $documentType }}</td>
                                        <td><a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $fileName }}</a></td>
                                        <td>
                                            <a href="{{ route('document.delete', $document->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบไฟล์นี้?')">ลบ</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSelection = "";
        document.getElementById("docTypeSelect").addEventListener("change", function() {
            const selectedOption = this.value;
            const uploadInput = document.getElementById("docUpload");
            const fileList = document.getElementById("fileList0");

            if (selectedOption === "") {
                uploadInput.style.display = "none";
                uploadInput.value = "";
                fileList.innerHTML = "";
            } else {
                if (selectedOption !== currentSelection) {
                    uploadInput.value = "";
                    fileList.innerHTML = "";
                }
                uploadInput.style.display = "block";
            }
            currentSelection = selectedOption;
        });

        document.getElementById("uploadForm").addEventListener("submit", function(e) {
            const selectedType = document.getElementById("docTypeSelect").value;
            const filesSelected = document.getElementById("docUpload").files.length > 0;

            if (!selectedType) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกประเภทเอกสาร', 'warning');
                e.preventDefault();
                return;
            }

            if (!filesSelected) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด', 'warning');
                e.preventDefault();
                return;
            }
        });

        document.getElementById("statusForm").addEventListener("submit", function(e) {
            const kkudqStatus = document.getElementById("kkudqStatus").value;
            const kkuKeptStatus = document.getElementById("kkuKeptStatus").value;

            if (!kkudqStatus && !kkuKeptStatus) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกสถานะอย่างน้อยหนึ่งรายการ (KKUDQ หรือ KKU KEPT)', 'warning');
                e.preventDefault();
                return;
            }
        });

        function addFiles(input, fileListId) {
            const fileList = document.getElementById(fileListId);
            fileList.innerHTML = "";
            Array.from(input.files).forEach(file => {
                const fileWrapper = document.createElement("div");
                fileWrapper.classList.add("file-item");
                const link = document.createElement("a");
                link.href = URL.createObjectURL(file);
                link.target = "_blank";
                link.textContent = file.name;
                fileWrapper.appendChild(link);

                const removeIcon = document.createElement("span");
                removeIcon.classList.add("remove-icon");
                removeIcon.innerHTML = "×";
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
    </script>
</body>
</html>