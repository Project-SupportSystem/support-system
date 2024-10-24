<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* เพิ่มสไตล์ให้เนื้อหากลางหน้า */
        body, html {
            height: 100%;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- กล่องสำหรับฟอร์ม login -->
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h2 class="card-title">Login</h2>
                <p class="mt-3">เข้าสู่ระบบด้วย Google</p>
                <a href="{{ url('auth/google') }}" class="btn btn-danger btn-block">
                    Login with Google
                </a>
            </div>
        </div>
    </div>

    <!-- Import Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
