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
                        {{ Auth::user()->username ?? 'ไม่พบข้อมูลผู้ใช้' }} ({{ Auth::user()->role ?? 'ไม่มีสิทธิ์' }})
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
            <h2>กรอกผลการศึกษา</h2>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                เพิ่มวิชา
            </button>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="reportForm" action="{{ route('report.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="semester" class="form-label">ภาคการศึกษา</label>
                    <select class="form-control" name="semester" required>
                        <option value="">เลือกภาคการศึกษา</option>
                        <option value="1/2566">1/2566</option>
                        <option value="2/2566">2/2566</option>
                        <option value="1/2567">1/2567</option>
                        <option value="2/2567">2/2567</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสวิชา</th>
                                <th>ชื่อวิชา</th>
                                <th>หน่วยกิต</th>
                                <th>เกรด</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="courses-table-body">
                            <tr>
                                <td><input type="text" class="form-control course-code" name="course_code[]" required maxlength="8"></td>
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
                                    <button type="button" class="btn btn-danger btn-remove-course">-</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="gpa-container">
                    GPA รวม: <strong id="gpa-total">0.00</strong>
                </div>

                <button type="submit" class="btn btn-custom">บันทึกผลการเรียน</button>
            </form>

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
                                    <label for="course_code" class="form-label">รหัสวิชา</label>
                                    <input type="text" name="course_code" class="form-control" required maxlength="8">
                                </div>
                                <div class="mb-3">
                                    <label for="course_name" class="form-label">ชื่อวิชา</label>
                                    <input type="text" name="course_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="total_credits" class="form-label">หน่วยกิต</label>
                                    <input type="number" name="total_credits" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-custom">เพิ่มวิชา</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // ฟังก์ชัน Autocomplete สำหรับช่องรหัสวิชา
        function setupAutocomplete() {
            $('.course-code').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('courses.autocomplete') }}',
                        data: { term: request.term },
                        success: function (data) {
                            response(data.map(course => ({
                                label: course.course_code + ' - ' + course.course_name,
                                value: course.course_code,
                                course_name: course.course_name,
                                total_credits: course.total_credits
                            })));
                        }
                    });
                },
                select: function (event, ui) {
                    const row = $(this).closest('tr');
                    row.find('input[name="course_name[]"]').val(ui.item.course_name);
                    row.find('input[name="total_credits[]"]').val(ui.item.total_credits);
                },
                minLength: 2
            });
        }

        // ฟังก์ชันคำนวณ GPA รวม
        function calculateGPA() {
            let totalCredits = 0;
            let totalWeightedGrades = 0;

            $('#courses-table-body tr').each(function () {
                const credits = parseFloat($(this).find('input[name="total_credits[]"]').val()) || 0;
                const grade = parseFloat($(this).find('select[name="grade[]"]').val()) || 0;

                totalCredits += credits;
                totalWeightedGrades += grade * credits;
            });

            const gpa = totalCredits > 0 ? (totalWeightedGrades / totalCredits).toFixed(2) : '0.00';
            $('#gpa-total').text(gpa);
        }

        // เรียกใช้ Autocomplete
        setupAutocomplete();

        // ฟังก์ชันเพิ่มแถวใหม่
        $('#courses-table-body').on('click', '.btn-add-course', function () {
            const newRow = 
                `<tr>
                    <td><input type="text" class="form-control course-code" name="course_code[]" required maxlength="8"></td>
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
                        <button type="button" class="btn btn-danger btn-remove-course">-</button>
                    </td>
                </tr>`;
            $('#courses-table-body').append(newRow);
            setupAutocomplete(); // Re-initialize autocomplete
        });

        // ลบแถว
        $('#courses-table-body').on('click', '.btn-remove-course', function () {
            $(this).closest('tr').remove();
            calculateGPA(); // คำนวณ GPA ใหม่หลังจากลบแถว
        });

        // คำนวณ GPA เมื่อเปลี่ยนเกรด
        $('#courses-table-body').on('change', '.grade-select', function () {
            calculateGPA();
        });

        // เพิ่ม SweetAlert การยืนยันเมื่อบันทึกผลการเรียน
        $('#reportForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการส่งฟอร์มโดยตรง
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
                    this.submit(); // ส่งฟอร์มเมื่อกดตกลง
                }
            });
        });

        // แจ้งเตือนเมื่อเพิ่มวิชาใหม่สำเร็จ
        $('#addCourseForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการส่งฟอร์มโดยตรง
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
                    // ส่งฟอร์มเมื่อกดตกลง
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#addCourseModal').modal('hide'); // ซ่อนโมดัล
                            Swal.fire('สำเร็จ!', 'วิชาใหม่ถูกเพิ่มแล้ว!', 'success');
                            // เพิ่มวิชาใหม่ไปยังตารางหรือทำการอัปเดต UI ตามต้องการ
                        },
                        error: function(xhr) {
                            Swal.fire('ผิดพลาด!', 'ไม่สามารถเพิ่มวิชาใหม่ได้.', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
