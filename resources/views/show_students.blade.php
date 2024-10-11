<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <!-- เพิ่ม Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Students List</h1>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Phone</th>
                    <th>School Location</th>
                    <th>Advisor</th>
                    <th>Academic Records</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->school_location }}</td>
                        <td>{{ $student->advisor ? $student->advisor->first_name : 'N/A' }}</td>
                        <td>
                            @foreach($student->academicRecords as $record)
                                <div>
                                    <strong>{{ $record->course->course_name }}</strong> - Grade: {{ $record->grade }} (GPA: {{ $record->gpa }})
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- เพิ่ม Bootstrap JS และ dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
