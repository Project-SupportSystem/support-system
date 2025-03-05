<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษานักศึกษา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-add-course, .btn-remove-course, .btn-edit-course, .btn-save-course {
            margin: 0 2px;
        }
        .gpa-container {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .grade-details-tab {
            margin-top: 30px;
        }
        .semester-select-container {
            display: flex;
            gap: 10px;
            align-items: center;
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
            <h2 class="text-center mb-4">กรอกผลการศึกษา</h2>

            <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    เพิ่มวิชาใหม่
                </button>
                <button type="button" class="btn btn-secondary" id="refreshCourses">รีเฟรชรายการวิชา</button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="reportForm" action="{{ route('report.submit') }}" method="POST">
                @csrf

                @auth
                    <p>ยินดีต้อนรับ, {{ Auth::user()->username }}</p>
                    <div class="mb-3 semester-select-container">
                        <div>
                            <label for="semester_term" class="form-label">ภาคการศึกษา</label>
                            <select class="form-control" name="semester_term" id="semester_term" required>
                                <option value="">เลือกภาค</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        <div>
                            <label for="semester_year" class="form-label">ปีการศึกษา</label>
                            <select class="form-control" name="semester_year" id="semester_year" required>
                                <option value="">เลือกปี</option>
                                @for ($year = 2565; $year <= 2570; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <p>รหัสนักศึกษา: {{ Auth::user()->student->id ?? 'ไม่พบรหัสนักศึกษา' }}</p>
                    <input type="hidden" name="semester" id="semester">
                @else
                    <p>กรุณาเข้าสู่ระบบก่อนทำการบันทึกผลการเรียน</p>
                @endauth

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสวิชา</th>
                                <th>ชื่อวิชา</th>
                                <th>หน่วยกิต</th>
                                <th>เกรด</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody id="courses-table-body">
                            <tr>
                                <td><input type="text" class="form-control course-id" name="id[]" required maxlength="8"></td>
                                <td><input type="text" class="form-control" name="course_name[]" readonly></td>
                                <td><input type="number" class="form-control" name="total_credits[]" readonly></td>
                                <td>
                                    <select class="form-control grade-select" name="grade[]" required>
                                        <option value="">เลือกเกรด</option>
                                        <option value="4.0">A</option>
                                        <option value="3.5">B+</option>
                                        <option value="3.0">B</option>
                                        <option value="2.5">C+</option>
                                        <option value="2.0">C</option>
                                        <option value="1.5">D+</option>
                                        <option value="1.0">D</option>
                                        <option value="0.0">F</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-add-course">+</button>
                                    <button type="button" class="btn btn-danger btn-remove-course">ลบ</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="gpa-container">
                    GPA รวม: <strong id="gpa-total">0.00</strong>
                </div>

                <button type="submit" class="btn btn-primary mt-3">บันทึกผลการเรียน</button>
            </form>

            <!-- Grade Details Tabs -->
            <div class="grade-details-tab">
                <h3 class="mt-5">รายละเอียดเกรด</h3>
                <ul class="nav nav-tabs" id="gradeTabs" role="tablist">
                    @for ($year = 2565; $year <= 2570; $year++)
                        <li class="nav-item">
                            <a class="nav-link" id="tab-1-{{ $year }}" data-bs-toggle="tab" href="#semester-1-{{ $year }}" role="tab">1/{{ $year }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-2-{{ $year }}" data-bs-toggle="tab" href="#semester-2-{{ $year }}" role="tab">2/{{ $year }}</a>
                        </li>
                    @endfor
                </ul>
                <div class="tab-content" id="gradeTabsContent">
                    @for ($year = 2565; $year <= 2570; $year++)
                        <div class="tab-pane fade" id="semester-1-{{ $year }}" role="tabpanel">
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>รหัสวิชา</th>
                                        <th>ชื่อวิชา</th>
                                        <th>หน่วยกิต</th>
                                        <th>เกรด</th>
                                        <th>GPA</th>
                                    </tr>
                                </thead>
                                <tbody id="grade-table-1-{{ $year }}"></tbody>
                            </table>
                            <div class="gpa-container">
                                GPA รวม: <strong id="gpa-total-1-{{ $year }}">0.00</strong>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="semester-2-{{ $year }}" role="tabpanel">
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>รหัสวิชา</th>
                                        <th>ชื่อวิชา</th>
                                        <th>หน่วยกิต</th>
                                        <th>เกรด</th>
                                        <th>GPA</th>
                                    </tr>
                                </thead>
                                <tbody id="grade-table-2-{{ $year }}"></tbody>
                            </table>
                            <div class="gpa-container">
                                GPA รวม: <strong id="gpa-total-2-{{ $year }}">0.00</strong>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Modal for Adding Course -->
            <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCourseModalLabel">เพิ่มวิชาใหม่</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addCourseForm" action="{{ route('courses.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">รหัสวิชา</label>
                                    <input type="text" id="course_id" name="id" class="form-control" required maxlength="8">
                                    <small id="idError" class="text-danger"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="course_name" class="form-label">ชื่อวิชา</label>
                                    <input type="text" id="course_name" name="course_name" class="form-control" required>
                                    <small id="nameError" class="text-danger"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="total_credits" class="form-label">หน่วยกิต</label>
                                    <input type="number" id="total_credits" name="total_credits" class="form-control" required min="1">
                                </div>
                                <button type="submit" class="btn btn-primary">เพิ่มวิชา</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // อัปเดตค่า semester เมื่อเลือกภาคและปี
        function updateSemester() {
            const term = $('#semester_term').val();
            const year = $('#semester_year').val();
            if (term && year) {
                $('#semester').val(`${term}/${year}`);
                loadAcademicRecords(`${term}/${year}`);
            } else {
                $('#semester').val('');
                $('#courses-table-body').empty();
                addEmptyRow();
                calculateGPA();
            }
        }

        $('#semester_term, #semester_year').on('change', updateSemester);

        // ฟังก์ชันดึงข้อมูลประวัติการลงทะเบียน
        function loadAcademicRecords(semester, targetTable = '#courses-table-body', targetGpa = '#gpa-total') {
            const tableBody = $(targetTable);
            tableBody.empty();
            $.ajax({
                url: '{{ route("report.academic.records") }}',
                method: 'GET',
                data: { semester: semester },
                success: function (data) {
                    if (data.length > 0) {
                        $.each(data, function (index, record) {
                            const row = `
                                <tr data-record-id="${record.id}">
                                    <td><input type="text" class="form-control course-id" name="id[]" value="${record.course.id}" readonly></td>
                                    <td><input type="text" class="form-control" name="course_name[]" value="${record.course.course_name}" readonly></td>
                                    <td><input type="number" class="form-control" name="total_credits[]" value="${record.course.total_credits}" readonly></td>
                                    <td>
                                        <select class="form-control grade-select" name="grade[]" required>
                                            <option value="">เลือกเกรด</option>
                                            <option value="4.0" ${record.gpa == 4.0 ? 'selected' : ''}>A</option>
                                            <option value="3.5" ${record.gpa == 3.5 ? 'selected' : ''}>B+</option>
                                            <option value="3.0" ${record.gpa == 3.0 ? 'selected' : ''}>B</option>
                                            <option value="2.5" ${record.gpa == 2.5 ? 'selected' : ''}>C+</option>
                                            <option value="2.0" ${record.gpa == 2.0 ? 'selected' : ''}>C</option>
                                            <option value="1.5" ${record.gpa == 1.5 ? 'selected' : ''}>D+</option>
                                            <option value="1.0" ${record.gpa == 1.0 ? 'selected' : ''}>D</option>
                                            <option value="0.0" ${record.gpa == 0.0 ? 'selected' : ''}>F</option>
                                        </select>
                                    </td>
                                    <td>
                                        ${targetTable === '#courses-table-body' ? `
                                            <button type="button" class="btn btn-warning btn-edit-course">แก้ไข</button>
                                            <button type="button" class="btn btn-danger btn-remove-course">ลบ</button>
                                            <button type="button" class="btn btn-success btn-add-course">+</button>
                                        ` : `
                                            <span>${record.grade}</span>
                                        `}
                                    </td>
                                    ${targetTable !== '#courses-table-body' ? `<td>${record.gpa}</td>` : ''}
                                </tr>`;
                            tableBody.append(row);
                        });
                    } else if (targetTable === '#courses-table-body') {
                        addEmptyRow();
                    } else {
                        tableBody.append('<tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>');
                    }
                    if (targetTable === '#courses-table-body') {
                        setupAutocomplete();
                    }
                    calculateGPA(targetTable, targetGpa);
                },
                error: function (xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงข้อมูลผลการเรียนได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                    if (targetTable === '#courses-table-body') {
                        addEmptyRow();
                    } else {
                        tableBody.append('<tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>');
                    }
                }
            });
        }

        // โหลดเกรดย้อนหลังสำหรับทุกภาคการศึกษา
        @for ($year = 2565; $year <= 2570; $year++)
            loadAcademicRecords('1/{{ $year }}', '#grade-table-1-{{ $year }}', '#gpa-total-1-{{ $year }}');
            loadAcademicRecords('2/{{ $year }}', '#grade-table-2-{{ $year }}', '#gpa-total-2-{{ $year }}');
        @endfor

        // ฟังก์ชันเพิ่มแถวเปล่าสำหรับกรอกข้อมูลใหม่
        function addEmptyRow() {
            const newRow = `
                <tr>
                    <td><input type="text" class="form-control course-id" name="id[]" required maxlength="8"></td>
                    <td><input type="text" class="form-control" name="course_name[]" readonly></td>
                    <td><input type="number" class="form-control" name="total_credits[]" readonly></td>
                    <td>
                        <select class="form-control grade-select" name="grade[]" required>
                            <option value="">เลือกเกรด</option>
                            <option value="4.0">A</option>
                            <option value="3.5">B+</option>
                            <option value="3.0">B</option>
                            <option value="2.5">C+</option>
                            <option value="2.0">C</option>
                            <option value="1.5">D+</option>
                            <option value="1.0">D</option>
                            <option value="0.0">F</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-success btn-add-course">+</button>
                        <button type="button" class="btn btn-danger btn-remove-course">ลบ</button>
                    </td>
                </tr>`;
            $('#courses-table-body').append(newRow);
            setupAutocomplete();
            console.log('Added new row'); // เพิ่มการ debug
        }

        // ฟังก์ชัน Autocomplete สำหรับช่องรหัสวิชา
        function setupAutocomplete() {
            $('.course-id').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('courses.autocomplete') }}',
                        data: { term: request.term },
                        success: function (data) {
                            response(data.map(course => ({
                                label: course.id + ' - ' + course.course_name,
                                value: course.id,
                                course_name: course.course_name,
                                total_credits: course.total_credits
                            })));
                        },
                        error: function () {
                            Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงรายการวิชาได้', 'error');
                        }
                    });
                },
                select: function (event, ui) {
                    const row = $(this).closest('tr');
                    row.find('input[name="course_name[]"]').val(ui.item.course_name);
                    row.find('input[name="total_credits[]"]').val(ui.item.total_credits);
                    calculateGPA();
                },
                minLength: 1
            });
        }

        // ฟังก์ชันคำนวณ GPA รวม
        function calculateGPA(targetTable = '#courses-table-body', targetGpa = '#gpa-total') {
            let totalCredits = 0;
            let totalWeightedGrades = 0;

            $(targetTable + ' tr').each(function () {
                const credits = parseFloat($(this).find('input[name="total_credits[]"]').val()) || 0;
                const grade = parseFloat($(this).find('select[name="grade[]"]').val()) || parseFloat($(this).find('td:last-child').prev().text()) || 0;

                totalCredits += credits;
                totalWeightedGrades += grade * credits;
            });

            const gpa = totalCredits > 0 ? (totalWeightedGrades / totalCredits).toFixed(2) : '0.00';
            $(targetGpa).text(gpa);
        }

        // ฟังก์ชันแก้ไขข้อมูล
        $(document).on('click', '.btn-edit-course', function () {
            const row = $(this).closest('tr');
            const recordId = row.data('record-id');

            if (!recordId) {
                Swal.fire('ผิดพลาด!', 'ไม่พบข้อมูลสำหรับการแก้ไข', 'error');
                return;
            }

            row.find('input[name="id[]"]').removeAttr('readonly');
            row.find('input[name="course_name[]"]').val('');
            row.find('input[name="total_credits[]"]').val('');
            setupAutocomplete();

            $(this).replaceWith('<button type="button" class="btn btn-success btn-save-course">บันทึก</button>');
        });

        // ฟังก์ชันบันทึกการแก้ไข
        $(document).on('click', '.btn-save-course', function () {
            const row = $(this).closest('tr');
            const recordId = row.data('record-id');
            const courseId = row.find('input[name="id[]"]').val();
            const grade = row.find('select[name="grade[]"]').val();

            if (!courseId || !grade) {
                Swal.fire('ผิดพลาด!', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
                return;
            }

            $.ajax({
                url: `/report/update-course/${recordId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    course_id: courseId,
                    grade: grade
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('สำเร็จ!', response.message, 'success');
                        row.find('input[name="id[]"]').attr('readonly', true);
                        row.find('.btn-save-course').replaceWith('<button type="button" class="btn btn-warning btn-edit-course">แก้ไข</button>');
                        const semester = $('#semester').val();
                        if (semester) {
                            loadAcademicRecords(semester);
                            @for ($year = 2565; $year <= 2570; $year++)
                                loadAcademicRecords('1/{{ $year }}', '#grade-table-1-{{ $year }}', '#gpa-total-1-{{ $year }}');
                                loadAcademicRecords('2/{{ $year }}', '#grade-table-2-{{ $year }}', '#gpa-total-2-{{ $year }}');
                            @endfor
                        }
                    } else {
                        Swal.fire('ผิดพลาด!', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถบันทึกการแก้ไขได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                }
            });
        });

        // ฟังก์ชันเพิ่มแถวใหม่
        $(document).on('click', '.btn-add-course', function () {
            console.log('Add course button clicked'); // Debug
            addEmptyRow();
        });

        // ฟังก์ชันลบแถว
        $(document).on('click', '.btn-remove-course', function () {
            const row = $(this).closest('tr');
            const recordId = row.data('record-id');

            if (recordId) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณต้องการลบวิชานี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ลบ!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/report/delete-course/${recordId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    row.remove();
                                    calculateGPA();
                                    Swal.fire('สำเร็จ!', response.message, 'success');
                                    if ($('#courses-table-body tr').length === 0) {
                                        addEmptyRow();
                                    }
                                    const semester = $('#semester').val();
                                    if (semester) {
                                        @for ($year = 2565; $year <= 2570; $year++)
                                            loadAcademicRecords('1/{{ $year }}', '#grade-table-1-{{ $year }}', '#gpa-total-1-{{ $year }}');
                                            loadAcademicRecords('2/{{ $year }}', '#grade-table-2-{{ $year }}', '#gpa-total-2-{{ $year }}');
                                        @endfor
                                    }
                                } else {
                                    Swal.fire('ผิดพลาด!', response.message, 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('ผิดพลาด!', 'ไม่สามารถลบวิชาได้: ' + (xhr.responseJSON?.message || 'เกิดข้อผิดพลาด'), 'error');
                            }
                        });
                    }
                });
            } else {
                row.remove();
                calculateGPA();
                if ($('#courses-table-body tr').length === 0) {
                    addEmptyRow();
                }
            }
        });

        // คำนวณ GPA เมื่อเปลี่ยนเกรด
        $(document).on('change', '.grade-select', function () {
            calculateGPA();
        });

        // รีเฟรชรายการวิชา
        $('#refreshCourses').on('click', function () {
            const semester = $('#semester').val();
            if (semester) {
                loadAcademicRecords(semester);
            } else {
                Swal.fire('คำเตือน!', 'กรุณาเลือกภาคการศึกษาและปีการศึกษาก่อน', 'warning');
            }
        });

        // เพิ่มวิชาใหม่ใน Modal
        $('#addCourseForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'เพิ่มวิชาใหม่!',
                text: "คุณต้องการเพิ่มวิชาใหม่หรือไม่?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เพิ่ม!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#addCourseModal').modal('hide');
                            $('#addCourseForm')[0].reset();
                            Swal.fire('สำเร็จ!', 'วิชาใหม่ถูกเพิ่มแล้ว!', 'success');
                            $('.course-id').autocomplete('destroy');
                            setupAutocomplete();
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON?.errors;
                            if (errors) {
                                $('#idError').text(errors.id || '');
                                $('#nameError').text(errors.course_name || '');
                            } else {
                                Swal.fire('ผิดพลาด!', 'ไม่สามารถเพิ่มวิชาได้ อาจมีรหัสวิชาหรือชื่อวิชาซ้ำ', 'error');
                            }
                        }
                    });
                }
            });
        });

        // เพิ่มการยืนยันเมื่อบันทึกผลการเรียน
        $('#reportForm').on('submit', function(e) {
            e.preventDefault();
            const semester = $('#semester').val();
            if (!semester) {
                Swal.fire('คำเตือน!', 'กรุณาเลือกภาคการศึกษาและปีการศึกษาก่อน', 'warning');
                return;
            }
            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "คุณต้องการบันทึกผลการเรียนนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, บันทึก!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // เรียกใช้ Autocomplete ครั้งแรก
        setupAutocomplete();
    });
    </script>
</body>
</html>