<!DOCTYPE html>
<html>
<head>
    <title>Courses List</title>
</head>
<body>
    <h1>Courses List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Total Credits</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_name }}</td>
                    <td>{{ $course->total_credits }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
