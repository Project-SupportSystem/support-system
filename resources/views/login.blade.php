<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - มหาวิทยาลัยขอนแก่น</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Kanit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ปรับพื้นหลังและฟอนต์ให้เข้ากับหน้า "กรอกผลการศึกษา" และ KKU */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column; /* เปลี่ยนเป็น column เพื่อให้สามารถวางข้อความด้านบน */
            justify-content: center;
            align-items: center;
            background-color: #f5f7fa; /* สีพื้นหลังเหมือนหน้า "กรอกผลการศึกษา" */
            font-family: 'Kanit', sans-serif;
            color: #333;
        }

        .system-title-container {
            margin-bottom: 1.5rem; /* ระยะห่างระหว่างข้อความและกล่อง Login */
            text-align: center;
        }

        .system-title {
            font-size: 28px; /* ขนาดตัวอักษรที่ใหญ่แต่ไม่มากเกินไป */
            font-weight: 600;
            color: #f36d21; /* สีส้ม KKU */
            line-height: 1.2;
        }

        .login-container {
            background-color: #ffffff; /* สีพื้นหลังของกล่อง */
            border: 1px solid #ced4da;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); /* เงาเหมือนหน้า "กรอกผลการศึกษา" */
            text-align: center;
            position: relative;
        }

        .login-header {
            margin-bottom: 1.5rem;
        }

        .login-header img {
            height: 50px;
            margin-bottom: 0.5rem;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50; /* สีน้ำตาลเข้มเหมือนหน้า "กรอกผลการศึกษา" */
            margin: 0;
        }

        .login-header p {
            font-size: 14px;
            color: #555;
            margin-top: 0.5rem;
        }

        .login-container h2 {
            font-size: 20px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .login-container p {
            font-size: 14px;
            color: #555;
            margin-bottom: 1.5rem;
        }

        .btn-login {
            background-color: #f36d21; /* สีส้มของ KKU */
            color: #ffffff;
            font-weight: 500;
            border: none;
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #d85b1b; /* สีส้มเข้มเมื่อ hover */
        }

        .btn-login img {
            width: 20px;
            margin-right: 10px;
        }

        .support-link {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .support-link a {
            color: #f36d21; /* สีส้มของ KKU */
            text-decoration: none;
        }

        .support-link a:hover {
            text-decoration: underline;
        }

        .privacy-links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .privacy-links a {
            color: #f36d21; /* สีส้มของ KKU */
            text-decoration: none;
        }

        .privacy-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .system-title {
                font-size: 22px; /* ปรับขนาดตัวอักษรให้เล็กลงสำหรับหน้าจอเล็ก */
            }

            .system-title-container {
                margin-bottom: 1rem;
            }

            .login-container {
                padding: 1.5rem;
                max-width: 90%;
            }

            .login-header img {
                height: 40px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .login-header p {
                font-size: 13px;
            }

            .login-container h2 {
                font-size: 18px;
            }

            .login-container p {
                font-size: 13px;
            }

            .btn-login {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .support-link, .privacy-links {
                font-size: 0.8rem;
            }
        }

        /* Dark Mode */
        @media (prefers-color-scheme: dark) {
            body, html {
                background-color: #1e1e1e;
                color: #d1d1d1;
            }

            .system-title {
                color: #f36d21; /* รักษาสีส้ม KKU */
            }

            .login-container {
                background-color: #2c2c2c;
                border-color: #444444;
                box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
            }

            .login-header h1 {
                color: #e9ecef;
            }

            .login-header p {
                color: #adb5bd;
            }

            .login-container h2 {
                color: #e9ecef;
            }

            .login-container p {
                color: #adb5bd;
            }

            .support-link, .privacy-links {
                color: #adb5bd;
            }

            .support-link a, .privacy-links a {
                color: #f36d21;
            }
        }
    </style>
</head>
<body>
    <div class="system-title-container">
        <div class="system-title">
            ระบบสนับสนุนหลักสูตร วิทยาการคอมพิวเตอร์และสารสนเทศ
        </div>
    </div>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('img/KKU_Logo.png') }}" alt="โลโก้ KKU">
            <h1>มหาวิทยาลัยขอนแก่น</h1>
            <p>Khon Kaen University</p>
        </div>
        <h2>เข้าสู่ระบบ</h2>
        <p>ยินดีต้อนรับสู่ระบบของมหาวิทยาลัยขอนแก่น<br>กรุณาเข้าสู่ระบบด้วย KKU Account ของคุณ</p>
        <a href="{{ url('auth/google') }}" class="btn btn-login mb-3">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Icon">
            เข้าสู่ระบบด้วย Google
        </a>

        <div class="support-link">
            <a href="https://password.kku.ac.th/">ลืมรหัสผ่าน? ติดต่อฝ่ายสนับสนุน</a>
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
            loginButton.innerHTML = '<img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Icon"> กำลังเข้าสู่ระบบ...';
            loginButton.disabled = true;
        });
    </script>
</body>
</html>