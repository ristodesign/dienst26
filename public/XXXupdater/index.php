<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updater</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 pt-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <strong>Must Read Before Proceed</strong>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">You must be at BookApp version - 3.0 to use this updater</li>
                        <li class="list-group-item">Please keep backup of your version - 3.0 project files & database sql file of previous version</li>
                        <li class="list-group-item">If you didn't keep a backup, then we cannot take the responsibility for losing data</li>
                        <li class="list-group-item text-danger font-weight-bold">All your customized code will be lost</li>
                    </ul>

                    <div class="card-footer text-center">
                        <a id="proceedBtn" class="btn btn-success px-4" href="inject.php">
                            <span id="btnText">I Have Read</span>
                            <span id="loader" class="spinner-border spinner-border-sm d-none"></span>
                        </a>

                        <p class="text-danger mb-0 mt-2">
                            <small>After clicking this button, updater codes will be injected into your project</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById("proceedBtn");
        const btnText = document.getElementById("btnText");
        const loader = document.getElementById("loader");

        btn.addEventListener("click", function(e) {
            // Disable multiple click
            btn.classList.add("disabled");
            btn.setAttribute("disabled", "true");

            // Show loader
            loader.classList.remove("d-none");

            // Change text
            btnText.innerText = "Processing...";

            // Allow normal link navigation
        });
    </script>
</body>

</html>
