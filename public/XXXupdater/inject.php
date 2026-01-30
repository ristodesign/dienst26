<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updater – Step Completed</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body {
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;
        }

        .update-box {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            width: 500px;
            overflow: hidden;
            animation: fadeIn 0.4s ease;
        }

        .update-body {
            padding: 25px 30px;
            text-align: center;
        }

        .update-body p {
            font-size: 15px;
            margin-bottom: 6px;
        }

        .update-footer {
            padding: 25px 0px;
            text-align: center;
            background: #fff;
        }

        #upgradeBtn {
            padding: 7px 30px;
            font-size: 17px;
            font-weight: 600;
            transition: 0.2s;
        }

        #upgradeBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .spinner-border {
            margin-left: 7px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <?php
    $base = __DIR__ . '/../';

    // replace web.php
    unlink($base . './../routes/web.php');
    copy("web.php", $base . './../routes/web.php');

    // place UpdaterController.php
    if (file_exists($base . './../app/Http/Controllers/UpdateController.php')) {

        unlink($base . './../app/Http/Controllers/UpdateController.php');
    }
    copy("UpdateController.php",  $base . '../app/Http/Controllers/UpdateController.php');
    ?>
    <div class="update-box">

        <div class="update-body">
            <div class="alert alert-success">
                <strong>Success!</strong> All required updater files have been injected into your project.
            </div>

            <p>Your system is now ready for the major upgrade.</p>
            <p class="text-secondary">Click the button below to start upgrading to version <strong>3.1</strong>.</p>

            <hr>

            <p class="text-danger">
                <small>⚠ Please avoid closing the browser during the upgrade process.</small>
            </p>
        </div>

        <div class="update-footer">
            <form action="../update/version">
                <button id="upgradeBtn" class="btn btn-success" type="submit">
                    <span id="btnText">Upgrade to 3.1</span>
                    <span id="loader" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </form>
        </div>

    </div>

    <script>
        const btn = document.getElementById("upgradeBtn");
        const btnText = document.getElementById("btnText");
        const loader = document.getElementById("loader");

        btn.addEventListener("click", function() {
            loader.classList.remove("d-none");
            btnText.innerText = "Upgrading...";
            setTimeout(() => {
                btn.classList.add("disabled");
                btn.setAttribute("disabled", "true");
            }, 100);
        });
    </script>

</body>

</html>