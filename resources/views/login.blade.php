<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ปรับสีพื้นหลังและจัดตำแหน่งเนื้อหากลางหน้า */
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f5f5f5; /* สีเทาอ่อน */
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff; /* สีขาวเพื่อความคมชัด */
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: bold;
            color: #333;
        }

        .btn-google {
            background-color: #db4437; /* สีแดงของ Google */
            color: #fff;
            font-weight: bold;
            width: 100%;
            padding: 0.75rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-google:hover {
            background-color: #c23321; /* สีแดงเข้มขึ้นเมื่อ hover */
        }

        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- กล่องสำหรับฟอร์ม login -->
        <div class="card text-center">
            <div class="card-body">
                <h2 class="card-title">ระบบสนับสนุนหลักสูตร</h2>
                <p class="mt-3">เข้าสู่ระบบด้วย Google KKU Account</p>
                <a href="{{ url('auth/google') }}" class="btn btn-google">
                    Login with Google
                </a>
            </div>
        </div>
    </div>

    <!-- Import Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
