<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Completed Successfully</title>

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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            width: 500px;
            overflow: hidden;
            animation: fadeIn 0.4s ease;
        }

        .update-body {
            padding: 25px 30px;
        }

        .update-body ul li {
            margin-bottom: 10px;
            font-size: 15px;
        }

        .update-footer {
            padding: 25px 0px;
            text-align: center;
            background: #fff;
        }

        .goto-btn {
            padding: 7px 30px;
            font-size: 17px;
            font-weight: 600;
            transition: 0.2s;
        }

        .goto-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
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

    <div class="update-box">

        <div class="update-body">
            <div class="alert alert-success text-center">
                <strong>You have successfully updated to the latest version.</strong>
            </div>

            <ul>
                <li>Click the <strong>Go To Website</strong> button to continue.</li>

                <li>Clear your browser cache and reload the website to apply all updates properly.</li>

                <li>For security, delete the <strong>updater</strong> folder from the <strong>root directory</strong>.</li>
            </ul>
        </div>

        <div class="update-footer">
            <a class="btn btn-success btn-lg goto-btn" href="../">
                Go To Website
            </a>
        </div>
    </div>

</body>

</html>
