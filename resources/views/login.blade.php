<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ปรับพื้นหลังและฟอนต์ */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fef7ec; /* สีพื้นหลังโทนเบจอ่อน */
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background-color: #fff7f0; /* สีขาวผสมส้มอ่อน */
            border: 1px solid #e0d8cc;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .login-container h2 {
            font-size: 1.6rem;
            font-weight: bold;
            color: #a15f3e; /* สีน้ำตาล */
            margin-bottom: 1.5rem;
        }

        .btn-login {
            background-color: #f36d21; /* สีส้ม KKU */
            color: #ffffff;
            font-weight: bold;
            border: none;
            width: 100%;
            padding: 0.75rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #d85b1b; /* สีส้มเข้ม */
        }

        .btn-login img {
            width: 20px;
            margin-right: 10px;
        }

        .privacy-links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #8d6e63; /* สีน้ำตาลอ่อน */
        }

        .privacy-links a {
            color: #a15f3e; /* สีน้ำตาล */
            text-decoration: none;
        }

        .privacy-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-container h2 {
                font-size: 1.4rem;
            }

            .btn-login {
                font-size: 0.9rem;
                padding: 0.6rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            body, html {
                background-color: #1e1e1e;
                color: #ffffff;
            }

            .login-container {
                background-color: #2c2c2c;
                border-color: #444444;
            }

            .privacy-links a {
                color: #f36d21;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <p>กรุณาเข้าสู่ระบบด้วย KKU Account ของคุณ</p>
        <a href="{{ url('auth/google') }}" class="btn btn-login mb-3">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Icon">
            Login with Google
        </a>

        <div class="mt-3">
            <a href="https://password.kku.ac.th/" class="text-muted" style="font-size: 0.85rem;">ลืมรหัสผ่าน? ติดต่อฝ่ายสนับสนุน</a>
        </div>

        <div class="privacy-links">
            <p>
                <a href="https://pdp.kku.ac.th/policy/680656708694016000" target="_blank">นโยบายคุ้มครองข้อมูลส่วนบุคคล</a><br>
                <a href="https://pdp.kku.ac.th/policy/891598222989471744" target="_blank">ประกาศการคุ้มครองข้อมูลส่วนบุคคล</a>
            </p>
        </div>
    </div>

    <!-- Import Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loginButton = document.querySelector('.btn-login');
        loginButton.addEventListener('click', () => {
            loginButton.innerHTML = 'กำลังเข้าสู่ระบบ...';
            loginButton.disabled = true;
        });
    </script>
</body>
</html>
