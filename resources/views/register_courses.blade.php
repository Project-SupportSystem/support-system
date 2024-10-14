<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกผลการศึกษานักศึกษา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
            color: black;
        }

        .btn-custom {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .gpa-container {
            text-align: right;
            margin-top: 20px;
        }

        .gpa-container strong {
            font-size: 1.5rem;
            color: #000;
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
        <a href="{{ route('report') }}">รายงานผลการศึกษา</a>
        <a href="{{ route('upload.document') }}">อัปโหลดเอกสาร</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container mt-5">
            <h2>กรอกผลการศึกษา</h2>

            <!-- ปุ่ม "เพิ่มวิชา" -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                เพิ่มวิชา
            </button>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('report.submit') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสวิชา</th>
                                <th>ชื่อวิชา</th>
                                <th>หน่วยกิต</th>
                                <th>เกรด</th>
                                <th></th> <!-- ปุ่ม "+" -->
                            </tr>
                        </thead>
                        <tbody id="courses-table-body">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="course_code[]" id="course_code_0" placeholder="กรอกรหัสวิชา" autocomplete="off" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="course_name[]" id="course_name_0" placeholder="ชื่อวิชา" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="total_credits[]" id="total_credits_0" placeholder="หน่วยกิต" readonly>
                                </td>
                                <td>
                                    <select class="form-control" name="grade[]" id="grade_0" required>
                                        <option value="">เลือกเกรด</option>
                                        <option value="A">A</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="D+">D+</option>
                                        <option value="D">D</option>
                                        <option value="F">F</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-add-course">+</button> <!-- ปุ่มเพิ่มแถว -->
                                    <button type="button" class="btn btn-danger btn-remove-course">-</button> <!-- ปุ่มลบ -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- GPA รวม -->
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('courses.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="course_code" class="form-label">รหัสวิชา</label>
                                    <input type="text" name="course_code" class="form-control" id="course_code" placeholder="กรอกรหัสวิชา" required>
                                </div>

                                <div class="mb-3">
                                    <label for="course_name" class="form-label">ชื่อวิชา</label>
                                    <input type="text" name="course_name" class="form-control" id="course_name" placeholder="กรอกชื่อวิชา" required>
                                </div>

                                <div class="mb-3">
                                    <label for="total_credits" class="form-label">หน่วยกิต</label>
                                    <input type="number" name="total_credits" class="form-control" id="total_credits" placeholder="กรอกจำนวนหน่วยกิต" required>
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
            let courseIndex = 1; // เริ่มต้นจาก index 1 สำหรับแถวใหม่

            // Autocomplete สำหรับรหัสวิชา
            $(document).on('input', 'input[name="course_code[]"]', function() {
                const courseCode = $(this).val();
                const rowIndex = $(this).attr('id').split('_')[2]; // ดึง index ของแถว

                // เพิ่มเงื่อนไขเพื่อตรวจสอบความยาวของ courseCode
                if (courseCode.length > 0) {
                    $.ajax({
                        url: '{{ route('courses.autocomplete') }}',
                        method: 'GET',
                        data: { term: courseCode },
                        success: function(response) {
                            const courseCodes = response.map(course => {
                                return {
                                    label: course.course_code + ' - ' + course.course_name,
                                    value: course.course_code,
                                    course_name: course.course_name,
                                    total_credits: course.total_credits
                                };
                            });

                            $('#course_code_' + rowIndex).autocomplete({
                                source: courseCodes,
                                select: function(event, ui) {
                                    $('#course_name_' + rowIndex).val(ui.item.course_name);
                                    $('#total_credits_' + rowIndex).val(ui.item.total_credits);
                                }
                            });
                        }
                    });
                }
            });

            // ปุ่มเพิ่มแถว
            $('.btn-add-course').click(function() {
                const newRow = `
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="course_code[]" id="course_code_${courseIndex}" placeholder="กรอกรหัสวิชา" autocomplete="off" required>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="course_name[]" id="course_name_${courseIndex}" placeholder="ชื่อวิชา" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="total_credits[]" id="total_credits_${courseIndex}" placeholder="หน่วยกิต" readonly>
                        </td>
                        <td>
                            <select class="form-control" name="grade[]" id="grade_${courseIndex}" required>
                                <option value="">เลือกเกรด</option>
                                <option value="A">A</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="C+">C+</option>
                                <option value="C">C</option>
                                <option value="D+">D+</option>
                                <option value="D">D</option>
                                <option value="F">F</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-add-course">+</button>
                            <button type="button" class="btn btn-danger btn-remove-course">-</button>
                        </td>
                    </tr>`;
                $('#courses-table-body').append(newRow);
                courseIndex++;
            });

            // ปุ่มลบแถว
            $(document).on('click', '.btn-remove-course', function() {
                if ($('#courses-table-body tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert('ต้องมีอย่างน้อยหนึ่งแถวในตาราง!');
                }
            });
        });
    </script>

</body>
</html>
