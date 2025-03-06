<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดเอกสาร</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <style>
        .document-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .document-type {
            width: 150px;
            flex-shrink: 0;
        }
        .document-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }
        .status-section {
            margin-top: 20px;
        }
        .status-section .form-control {
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }
        .status-section .status-text {
            margin-left: 10px;
            font-weight: bold;
            color: #28a745;
        }
        .status-section .btn {
            margin-top: 10px;
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
                <img src="{{ asset('img/user-icon.png') }}" alt="User Icon" style="width: 30px; height: 30px; border-radius: 50%;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#">{{ Auth::user()->username ?? 'ไม่พบข้อมูลผู้ใช้' }} ({{ Auth::user()->role ?? 'ไม่มีสิทธิ์' }})</a></li>
                <li><a class="dropdown-item" href="#">รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">ออกจากระบบ</a></li>
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
            <h2 class="text-center mb-4">อัปโหลดเอกสาร</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">เลือกประเภทเอกสาร</h5>
                    </div>
                    <div class="card-body">
                        <select name="titles[]" class="form-select mb-2" id="docTypeSelect">
                            <option value="" selected>-- เลือกประเภทเอกสาร --</option>
                            <option value="สหกิจศึกษา">สหกิจศึกษา</option>
                            <option value="โปรเจค">โปรเจค</option>
                            <option value="internship">ฝึกงาน</option>
                            <option value="transcript">ผลการศึกษา</option>
                        </select>
                        <p id="docInfo" class="text-muted"></p>
                        <input type="file" name="files[0][]" id="docUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple onchange="addFiles(this, 'fileList0')" style="display: none;">
                        <div id="fileList0" class="mt-2"></div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary" id="submitButton">อัปโหลด</button>
                </div>
            </form>

            <div class="card mt-5 status-section">
                <div class="card-header">
                    <h5 class="card-title">บันทึกสถานะ</h5>
                </div>
                <div class="card-body">
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
                            @endphp
                            @if($kkudqStatusText)
                                <span class="status-text"> (สถานะปัจจุบัน: {{ $kkudqStatusText }})</span>
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
                            @endphp
                            @if($kkuKeptStatusText)
                                <span class="status-text"> (สถานะปัจจุบัน: {{ $kkuKeptStatusText }})</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success">บันทึกสถานะ</button>
                    </form>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">ไฟล์ที่อัปโหลดแล้ว</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @php
                            $uploadedFiles = Cache::get('uploaded_files', []);
                        @endphp
                        @foreach ($documents as $document)
                            @if (in_array($document->document_type, ['coop_project', 'project', 'internship', 'transcript']))
                                <li class="list-group-item document-item">
                                    @php
                                        $documentType = match ($document->document_type) {
                                            'coop_project' => 'สหกิจศึกษา',
                                            'project' => 'โปรเจค',
                                            'internship' => 'ฝึกงาน',
                                            'transcript' => 'ผลการศึกษา',
                                            default => ucfirst($document->document_type),
                                        };
                                        $fileName = $uploadedFiles[$document->id] ?? basename($document->file_path);
                                        $statusText = '';
                                    @endphp
                                    <strong class="document-type">{{ $documentType }}</strong>
                                    <div class="document-actions">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $fileName }}{{ $statusText }}</a>
                                        <a href="{{ route('document.delete', $document->id) }}" class="text-danger" onclick="return confirm('คุณต้องการลบไฟล์นี้?')">ลบ</a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSelection = "";
        document.getElementById("docTypeSelect").addEventListener("change", function() {
            const selectedOption = this.value;
            const docInfo = document.getElementById("docInfo");
            const uploadInput = document.getElementById("docUpload");
            const fileList = document.getElementById("fileList0");

            if (selectedOption === "") {
                uploadInput.style.display = "none";
                uploadInput.value = "";
                fileList.innerHTML = "";
                docInfo.textContent = "";
            } else {
                if (selectedOption !== currentSelection) {
                    uploadInput.value = "";
                    fileList.innerHTML = "";
                }
                uploadInput.style.display = "block";
                if (selectedOption === "สหกิจศึกษา") {
                    docInfo.textContent = "ไฟล์ประกอบด้วย 1. ใบสมัครออกฝึกปฏิบัติงานรายวิชาสหกิจศึกษา 2. ประวัติย่อ (Resume) ...";
                } else if (selectedOption === "โปรเจค") {
                    docInfo.textContent = "ไฟล์ประกอบด้วย 1. รายละเอียดโครงการ ชื่อโครงการหรือชื่อโปรเจค ...";
                } else if (selectedOption === "internship") {
                    docInfo.textContent = "ไฟล์ประกอบด้วย รายงานฝึกงาน ...";
                } else if (selectedOption === "transcript") {
                    docInfo.textContent = "ไฟล์ประกอบด้วย ผลการเรียน ...";
                } else {
                    docInfo.textContent = "กรุณาเลือกไฟล์ที่ต้องการอัปโหลด";
                }
            }
            currentSelection = selectedOption;
        });

        document.getElementById("uploadForm").addEventListener("submit", function(e) {
            const selectedType = document.getElementById("docTypeSelect").value;
            const filesSelected = document.getElementById("docUpload").files.length > 0;

            if (!selectedType) {
                alert('กรุณาเลือกประเภทเอกสาร');
                e.preventDefault();
                return;
            }

            if (!filesSelected) {
                alert('กรุณาเลือกไฟล์ที่ต้องการอัปโหลด');
                e.preventDefault();
                return;
            }
        });

        document.getElementById("statusForm").addEventListener("submit", function(e) {
            const kkudqStatus = document.getElementById("kkudqStatus").value;
            const kkuKeptStatus = document.getElementById("kkuKeptStatus").value;

            if (!kkudqStatus && !kkuKeptStatus) {
                alert('กรุณาเลือกสถานะอย่างน้อยหนึ่งรายการ (KKUDQ หรือ KKU KEPT)');
                e.preventDefault();
                return;
            }
        });

        function addFiles(input, fileListId) {
            const fileList = document.getElementById(fileListId);
            fileList.innerHTML = "";
            Array.from(input.files).forEach(file => {
                const fileWrapper = document.createElement("div");
                fileWrapper.classList.add("d-flex", "align-items-center", "mb-1");
                const link = document.createElement("a");
                link.href = URL.createObjectURL(file);
                link.target = "_blank";
                link.textContent = file.name;
                link.className = "me-2";
                fileWrapper.appendChild(link);

                const removeIcon = document.createElement("span");
                removeIcon.innerHTML = "×";
                removeIcon.classList.add("text-danger", "cursor-pointer");
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