<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษานักศึกษา</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> <!-- ลิงก์ไปยังไฟล์ custom.css -->
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
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
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
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
                
                <!-- ส่วนของตารางกรอกข้อมูลวิชา -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสวิชา</th>
                                <th>ชื่อวิชา</th>
                                <th>หน่วยกิต</th>
                                <th>เกรด</th>
                                <th></th> <!-- ปุ่มเพิ่ม/ลบ -->
                            </tr>
                        </thead>
                        <tbody id="courses-table-body">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="course_code[]" id="course_code_0" placeholder="กรอกรหัสวิชา" autocomplete="off" required maxlength="8">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="course_name[]" id="course_name_0" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_credits[]" id="total_credits_0" readonly>
                                </td>
                                <td>
                                    <select class="form-control grade-select" name="grade[]" id="grade_0" required>
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
                                    <input type="text" name="course_code" class="form-control" required maxlength="8"> <!-- เพิ่ม maxlength ที่นี่ -->
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
        $(document).ready(function() {
            let courseIndex = 1;

            // ฟังก์ชันเพิ่มแถว
            $('.btn-add-course').click(function() {
                const newRow = `<tr>
                    <td><input type="text" class="form-control" name="course_code[]" id="course_code_${courseIndex}" required maxlength="8"></td>
                    <td><input type="text" class="form-control" name="course_name[]" id="course_name_${courseIndex}" readonly></td>
                    <td><input type="number" class="form-control" name="total_credits[]" id="total_credits_${courseIndex}" readonly></td>
                    <td><select class="form-control grade-select" name="grade[]" required>
                        <option value="">เลือกเกรด</option>
                        <option value="4.0">A</option>
                        <option value="3.5">B+</option>
                        <option value="3.0">B</option>
                        <option value="2.5">C+</option>
                        <option value="2.0">C</option>
                        <option value="1.5">D+</option>
                        <option value="1.0">D</option>
                        <option value="0.0">F</option>
                    </select></td>
                    <td>
                        <button type="button" class="btn btn-success btn-add-course">+</button>
                        <button type="button" class="btn btn-danger btn-remove-course">-</button>
                    </td>
                </tr>`;
                $('#courses-table-body').append(newRow);
                courseIndex++;

                // ตั้งค่า Autocomplete ใหม่สำหรับแถวที่เพิ่ม
                setupAutocomplete();
            });

            // ฟังก์ชันตั้งค่า Autocomplete
            function setupAutocomplete() {
                $('input[name="course_code[]"]').each(function() {
                    const $input = $(this);
                    $input.autocomplete({
                        source: function(request, response) {
                            $.ajax({
                                url: '{{ route('courses.autocomplete') }}',
                                method: 'GET',
                                data: { term: request.term },
                                success: function(data) {
                                    response(data.map(course => ({
                                        label: course.course_code + ' - ' + course.course_name,
                                        value: course.course_code,
                                        course_name: course.course_name,
                                        total_credits: course.total_credits
                                    })));
                                }
                            });
                        },
                        select: function(event, ui) {
                            const rowIndex = $input.attr('id').split('_')[2]; // ดึง index ของแถว
                            $('#course_name_' + rowIndex).val(ui.item.course_name);
                            $('#total_credits_' + rowIndex).val(ui.item.total_credits);
                        }
                    });
                });
            }

            // เรียกใช้ฟังก์ชัน setupAutocomplete เมื่อโหลดหน้า
            setupAutocomplete();

            // ลบแถวพร้อม SweetAlert
            $(document).on('click', '.btn-remove-course', function() {
                const row = $(this).closest('tr');
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: 'คุณต้องการลบวิชานี้ใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        calculateGPA();
                        Swal.fire('ลบแล้ว!', 'ข้อมูลวิชาถูกลบเรียบร้อย', 'success');
                    }
                });
            });

            // คำนวณ GPA อัตโนมัติ
            $(document).on('change', '.grade-select', calculateGPA);

            function calculateGPA() {
                let totalCredits = 0, totalGradePoints = 0;
                $('#courses-table-body tr').each(function() {
                    const credits = parseFloat($(this).find('input[name="total_credits[]"]').val()) || 0;
                    const grade = parseFloat($(this).find('select[name="grade[]"]').val()) || 0;
                    totalCredits += credits;
                    totalGradePoints += credits * grade;
                });
                const gpa = totalCredits ? (totalGradePoints / totalCredits).toFixed(2) : '0.00';
                $('#gpa-total').text(gpa);
            }

            // เพิ่มการแจ้งเตือนเมื่อเพิ่มวิชา
            $('#addCourseForm').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ยืนยันการเพิ่มวิชา?',
                    text: 'คุณต้องการเพิ่มวิชานี้ใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, เพิ่มเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ถ้าผู้ใช้ยืนยันให้ทำการส่งฟอร์ม
                        this.submit();
                    }
                });
            });

            // เพิ่มการแจ้งเตือนเมื่อบันทึกผลการเรียน
            $('#reportForm').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ยืนยันการบันทึกผลการเรียน?',
                    text: 'คุณต้องการบันทึกผลการเรียนนี้ใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, บันทึกเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ถ้าผู้ใช้ยืนยันให้ทำการส่งฟอร์ม
                        this.submit();
                    }
                });
            });
        });
    </script>

</body>
</html>
