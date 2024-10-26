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
            <h2 class="text-center">อัปโหลดเอกสาร</h2>
            <form action="{{ route('upload.doc') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- Form 1: สหกิจศึกษา / โปรเจค -->
                <div class="mb-4">
                    <label class="form-label">สหกิจศึกษา / โปรเจค</label>
                    <select name="titles[]" class="form-select mb-2" id="coopProjectSelect">
                        <option value="" selected>-- เลือกแผนการที่จะไป --</option>
                        <option value="สหกิจศึกษา">สหกิจศึกษา</option>
                        <option value="โปรเจค">โปรเจค</option>
                    </select>
                    <p id="coopProjectInfo" class="text-muted"></p>
                    <!-- ปิด input อัปโหลดไฟล์เมื่อไม่ได้เลือกแผน -->
                    <input type="file" name="files[0][]" id="coopProjectUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple onchange="addFiles(this, 'fileList0')" style="display: none;">
                    <div id="fileList0" class="mt-2"></div>
                </div>

                <!-- Form 2: KKUDQ -->
                <div class="mb-4">
                    <label class="form-label">KKUDQ</label>
                    <div>
                        <input type="radio" name="kkudq_status" value="pass" id="kkudqPass" onclick="toggleUpload('kkudqUpload', true)">
                        <label for="kkudqPass">ผ่าน</label>
                        <input type="radio" name="kkudq_status" value="fail" id="kkudqFail" onclick="toggleUpload('kkudqUpload', false)">
                        <label for="kkudqFail">ไม่ผ่าน</label>
                    </div>
                    <input type="file" name="files[1][]" id="kkudqUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple disabled onchange="addFiles(this, 'fileList1')">
                    <div id="fileList1" class="mt-2"></div>
                </div>

                <!-- Form 3: KKU KEPT -->
                <div class="mb-4">
                    <label class="form-label">KKU KEPT</label>
                    <div>
                        <input type="radio" name="kept_status" value="pass" id="keptPass" onclick="toggleUpload('keptUpload', true)">
                        <label for="keptPass">ผ่าน</label>
                        <input type="radio" name="kept_status" value="fail" id="keptFail" onclick="toggleUpload('keptUpload', false)">
                        <label for="keptFail">ไม่ผ่าน</label>
                    </div>
                    <input type="file" name="files[2][]" id="keptUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple disabled onchange="addFiles(this, 'fileList2')">
                    <div id="fileList2" class="mt-2"></div>
                </div>

                <!-- Form 4: ฝึกงาน -->
                <div class="mb-4">
                    <label class="form-label">ฝึกงาน</label>
                    <p class="text-muted">1). ไฟล์ประกอบด้วยเอกสารบันทึกและแบบประเมิน ให้รวมเป็นไฟล์เดียว <br>2). เอกสารแผนงานการฝึกงาน (ตัวเลือก)</p>
                    <div>
                        <input type="radio" name="internship_status" value="pass" id="internshipPass" onclick="toggleUpload('internshipUpload', true)">
                        <label for="internshipPass">ผ่าน</label>
                        <input type="radio" name="internship_status" value="fail" id="internshipFail" onclick="toggleUpload('internshipUpload', false)">
                        <label for="internshipFail">ไม่ผ่าน</label>
                    </div>
                    <input type="file" name="files[3][]" id="internshipUpload" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple disabled onchange="addFiles(this, 'fileList3')">
                    <div id="fileList3" class="mt-2"></div>
                </div>

                <!-- Form 5: ผลการศึกษา -->
                <div class="mb-4">
                    <label class="form-label">ผลการศึกษา</label>
                    <input type="file" name="files[4][]" class="form-control" accept=".pdf,.docx,.jpeg,.jpg,.png" multiple onchange="addFiles(this, 'fileList4')">
                    <div id="fileList4" class="mt-2"></div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">อัปโหลด</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ตั้งค่าแผนเริ่มต้นของ select option
        let currentSelection = "";

        document.getElementById("coopProjectSelect").addEventListener("change", function() {
            const selectedOption = this.value;
            const coopProjectInfo = document.getElementById("coopProjectInfo");
            const uploadInput = document.getElementById("coopProjectUpload");
            const fileList = document.getElementById("fileList0");
            
            if (selectedOption === "") {
                // ซ่อน input และล้างไฟล์หากเลือก "-- เลือกแผนการที่จะไป --"
                uploadInput.style.display = "none";
                uploadInput.value = "";
                fileList.innerHTML = "";
                coopProjectInfo.textContent = "";
            } else {
                // หากมีการเปลี่ยนแผนจากที่เคยเลือกไว้ ให้ล้างไฟล์ทั้งหมดที่อัปโหลดไปก่อนหน้า
                if (selectedOption !== currentSelection) {
                    uploadInput.value = ""; // รีเซ็ตไฟล์ที่อัปโหลด
                    fileList.innerHTML = ""; // ล้างรายการไฟล์ที่อัปโหลด
                }
                // แสดง input และแสดงข้อความรายละเอียดเมื่อเลือกแผน
                uploadInput.style.display = "block";
                coopProjectInfo.textContent = selectedOption === "สหกิจศึกษา"
                    ? "ไฟล์ประกอบด้วย 1. ใบสมัครออกฝึกปฏิบัติงานรายวิชาสหกิจศึกษา 2. ประวัติย่อ (Resume) 3. ใบรายงานผลการศึกษาฉบับจริง 1 ฉบับ 4. สำเนาบัตรประจำตัวประชาชน นศ. หรือสำเนาบัตรนักศึกษา (รับรองสำเนาถูกต้อง) 1 ฉบับ 5. หนังสือยินยอมจากผู้ปกครอง 6. สำเนาบัตรประชาชนของผู้ปกครอง (ผู้ปกครองลงชื่อและรับรองสํานําถูกต้อง) 1 ฉบับ โดยให้รวมเป็นไฟล์เดียว"
                    : "ไฟล์ประกอบด้วย 1. รายละเอียดโครงการ โปรเจค ...";
            }
            currentSelection = selectedOption; // เก็บแผนที่เลือกปัจจุบัน
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