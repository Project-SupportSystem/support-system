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
</head>
<body>
    <div class="navbar d-flex justify-content-between align-items-center p-2 bg-light">
        <div class="navbar-logo">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ระบบ" style="height: 50px;">
        </div>
        <div class="dropdown">
            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown" 
               data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/user-icon.png') }}" alt="User Icon" style="width: 30px; height: 30px; border-radius: 50%;"></a>
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

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- ฟอร์มการอัปโหลด -->
            <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- ฟอร์ม สหกิจศึกษา / โปรเจค -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">สหกิจศึกษา / โปรเจค</h5>
                    </div>
                    <div class="card-body">
                        <select name="titles[]" class="form-select mb-2" id="coopProjectSelect">
                            <option value="" selected>-- เลือกแผนการที่จะไป --</option>
                            <option value="สหกิจศึกษา">สหกิจศึกษา</option>
                            <option value="โปรเจค">โปรเจค</option>
                        </select>
                        <p id="coopProjectInfo" class="text-muted"></p>
                        <input type="file" name="files[0][]" id="coopProjectUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple onchange="addFiles(this, 'fileList0')" style="display: none;">
                        <div id="fileList0" class="mt-2"></div>
                    </div>
                </div>

                <!-- ฟอร์ม KKUDQ -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">KKUDQ</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <input type="radio" name="kkudq_status" value="pass" id="kkudqPass">
                            <label for="kkudqPass">ผ่าน</label>
                            <input type="radio" name="kkudq_status" value="fail" id="kkudqFail">
                            <label for="kkudqFail">ไม่ผ่าน</label>
                        </div>
                    </div>
                </div>

                <!-- ฟอร์ม KKU KEPT -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">KKU KEPT</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <input type="radio" name="kept_status" value="pass" id="keptPass">
                            <label for="keptPass">ผ่าน</label>
                            <input type="radio" name="kept_status" value="fail" id="keptFail">
                            <label for="keptFail">ไม่ผ่าน</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">อัปโหลด</button>
                </div>
            </form>

            <!-- แสดงไฟล์ที่อัปโหลดแล้ว -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">ไฟล์ที่อัปโหลดแล้ว</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($documents as $document)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <!-- แสดงประเภทเอกสาร -->
                                @php
                                    $documentType = '';
                                    if ($document->document_type == 'coop_project') {
                                        $documentType = 'สหกิจศึกษา';
                                    } elseif ($document->document_type == 'project') {
                                        $documentType = 'โปรเจค';
                                    } elseif ($document->document_type == 'kku_dq') {
                                        $documentType = 'KKUDQ';
                                    } elseif ($document->document_type == 'kku_kept') {
                                        $documentType = 'KKU KEPT';
                                    } else {
                                        $documentType = ucfirst($document->document_type); // สำหรับประเภทเอกสารอื่นๆ
                                    }
                                @endphp

                                <strong>{{ $documentType }}</strong>: 
                                <!-- แสดงชื่อไฟล์ที่ถูกอัปโหลด -->
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $document->original_file_name ?? 'File' }}</a>
                                <a href="{{ route('document.delete', $document->id) }}" class="text-danger" onclick="return confirm('คุณต้องการลบไฟล์นี้?')">ลบ</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSelection = "";

        document.getElementById("coopProjectSelect").addEventListener("change", function() {
            const selectedOption = this.value;
            const coopProjectInfo = document.getElementById("coopProjectInfo");
            const uploadInput = document.getElementById("coopProjectUpload");
            const fileList = document.getElementById("fileList0");

            if (selectedOption === "") {
                uploadInput.style.display = "none";
                uploadInput.value = "";
                fileList.innerHTML = "";
                coopProjectInfo.textContent = "";
            } else {
                if (selectedOption !== currentSelection) {
                    uploadInput.value = "";
                    fileList.innerHTML = "";
                }
                uploadInput.style.display = "block";
                coopProjectInfo.textContent = selectedOption === "สหกิจศึกษา"
                    ? "ไฟล์ประกอบด้วย 1. ใบสมัครออกฝึกปฏิบัติงานรายวิชาสหกิจศึกษา 2. ประวัติย่อ (Resume) ..."
                    : "ไฟล์ประกอบด้วย 1. รายละเอียดโครงการ ชื่อโครงการหรือชื่อโปรเจค ...";
            }
            currentSelection = selectedOption;
        });

        function toggleUpload(inputId, enable) {
            const fileInput = document.getElementById(inputId);
            fileInput.disabled = !enable;
            if (!enable) {
                fileInput.value = "";
                document.getElementById('fileList' + inputId[inputId.length - 1]).innerHTML = "";
            }
        }

        function addFiles(input, fileListId) {
            const fileList = document.getElementById(fileListId);
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
                removeIcon.innerHTML = "&times;";
                removeIcon.classList.add("text-danger", "cursor-pointer");
                removeIcon.onclick = () => fileWrapper.remove();
                fileWrapper.appendChild(removeIcon);
                fileList.appendChild(fileWrapper);
            });
        }
    </script>
</body>
</html>
