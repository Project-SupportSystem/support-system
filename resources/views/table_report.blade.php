<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรายงานผลการศึกษานักศึกษา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

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
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
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
        <a href="{{ route('table_report') }}">ค้นหานักศึกษา</a>
    </div>

    <div class="content p-4" style="margin-left: 250px; margin-top: 80px;">
        <div class="container">
            <h2 class="text-center mb-4">รายชื่อนักศึกษาที่รับผิดชอบ</h2>

            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search-input" placeholder="กรอกรหัสนักศึกษาหรือชื่อ">
                <button class="btn btn-primary" onclick="searchStudent()">ค้นหา</button>
            </div>

            <div class="mb-3">
                <select class="form-select" id="filter-select" onchange="filterTable()">
                    <option value="all">ทั้งหมด</option>
                    <option value="grade">เกรดเฉลี่ย</option>
                    <option value="kept">KKU KEPT</option>
                    <option value="dq">KKU DQ</option>
                    <option value="internship">ฝึกงาน</option>
                    <option value="coop">สหกิจศึกษา</option>
                    <option value="project">โปรเจค</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อนามสกุล</th>
                            <th>สาขา</th>
                            <th>เกรดเฉลี่ย</th>
                            <th>สหกิจศึกษา</th>
                            <th>โปรเจค</th>
                            <th>KKU KEPT</th>
                            <th>KKU DQ</th>
                            <th>ฝึกงาน</th>
                        </tr>
                    </thead>
                    <tbody id="student-table">
                        @foreach($students as $index => $student)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->first_name }}</td>
                                <td>วิทยาการคอมพิวเตอร์และสารสนเทศ</td>
                                <td>{{ $student->calculateGPA() ? number_format($student->calculateGPA(), 2) : '-' }}</td>
                                <td>
                                    @if($student->documents->where('document_type', 'coop_project')->isNotEmpty())
                                        <a href="#" onclick="showFilesModal('{{ $student->id }}', 'coop_project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'project')->isNotEmpty())
                                        <a href="#" onclick="showFilesModal('{{ $student->id }}', 'project')">ดูเอกสาร</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'kku_kept')->isNotEmpty())
                                        @php
                                            $doc = $student->documents->where('document_type', 'kku_kept')->first();
                                            $status = $doc->status;
                                            $displayStatus = in_array($doc->document_type, ['kku_kept']) && $status
                                                ? ($status == 'pass' ? 'ผ่าน' : ($status == 'fail' ? 'ไม่ผ่าน' : '-'))
                                                : '-';
                                        @endphp
                                        {{ $displayStatus }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'kku_dq')->isNotEmpty())
                                        @php
                                            $doc = $student->documents->where('document_type', 'kku_dq')->first();
                                            $status = $doc->status;
                                            $displayStatus = in_array($doc->document_type, ['kku_dq']) && $status
                                                ? ($status == 'pass' ? 'ผ่าน' : ($status == 'fail' ? 'ไม่ผ่าน' : '-'))
                                                : '-';
                                        @endphp
                                        {{ $displayStatus }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($student->documents->where('document_type', 'internship')->isNotEmpty())
                                        @php
                                            $doc = $student->documents->where('document_type', 'internship')->first();
                                            $status = $doc->status;
                                            $displayStatus = in_array($doc->document_type, ['internship']) && $status
                                                ? ($status == 'pass' ? 'ผ่าน' : ($status == 'fail' ? 'ไม่ผ่าน' : '-'))
                                                : '-';
                                        @endphp
                                        {{ $displayStatus }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filesModalLabel">ไฟล์เอกสาร</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul id="filesList"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFilesModal(studentId, docType) {
            $.ajax({
                url: `/get-student-documents/${studentId}/${docType}`,
                type: 'GET',
                success: function (response) {
                    $('#filesList').empty();
                    response.documents.forEach(function (doc) {
                        let statusText = ['kku_dq', 'kku_kept'].includes(doc.document_type) && doc.status
                            ? ` (${doc.status == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน'})`
                            : '';
                        $('#filesList').append(`<li><a href="${doc.file_path}" target="_blank">${doc.file_name}${statusText}</a></li>`);
                    });
                    $('#filesModal').modal('show');
                },
                error: function (xhr) {
                    alert('ไม่สามารถดึงข้อมูลเอกสารได้: ' + xhr.responseText);
                }
            });
        }

        function searchStudent() {
            var input = document.getElementById('search-input').value.toLowerCase();
            var table = document.getElementById('student-table');
            var tr = table.getElementsByTagName('tr');

            for (var i = 0; i < tr.length; i++) {
                var tdStudentId = tr[i].getElementsByTagName('td')[1];
                var tdName = tr[i].getElementsByTagName('td')[2];
                if (tdStudentId && tdName) {
                    var studentIdValue = tdStudentId.textContent || tdStudentId.innerText;
                    var nameValue = tdName.textContent || tdName.innerText;
                    if (studentIdValue.toLowerCase().indexOf(input) > -1 || nameValue.toLowerCase().indexOf(input) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function filterTable() {
            var filter = document.getElementById("filter-select").value;
            var table = document.getElementById("student-table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var tdGrade = tr[i].getElementsByTagName("td")[4];
                var tdCoop = tr[i].getElementsByTagName("td")[5];
                var tdProject = tr[i].getElementsByTagName("td")[6];
                var tdKept = tr[i].getElementsByTagName("td")[7];
                var tdDQ = tr[i].getElementsByTagName("td")[8];
                var tdInternship = tr[i].getElementsByTagName("td")[9];

                if (filter === "all") {
                    tr[i].style.display = "";
                } else if (filter === "grade" && tdGrade && tdGrade.textContent !== "-") {
                    tr[i].style.display = "";
                } else if (filter === "kept" && tdKept && tdKept.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else if (filter === "dq" && tdDQ && tdDQ.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else if (filter === "internship" && tdInternship && tdInternship.textContent === "ผ่าน") {
                    tr[i].style.display = "";
                } else if (filter === "coop" && tdCoop && tdCoop.textContent.includes("ดูเอกสาร")) {
                    tr[i].style.display = "";
                } else if (filter === "project" && tdProject && tdProject.textContent.includes("ดูเอกสาร")) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>