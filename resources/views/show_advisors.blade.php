<!DOCTYPE html>
<html>
<head>
    <title>Advisors List</title>
</head>
<body>
    <h1>Advisors List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Students</th>
            </tr>
        </thead>
        <tbody>
            @foreach($advisors as $advisor)
                <tr>
                    <td>{{ $advisor->id }}</td>
                    <td>{{ $advisor->first_name }}</td>
                    <td>
                        @foreach($advisor->students as $student)
                            <div>{{ $student->first_name }}</div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
