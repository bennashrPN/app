<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Forms Example</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Toggle Forms Example</h1>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="formSwitch">
            <label class="form-check-label" for="formSwitch">Show Form 2</label>
        </div>

        <!-- Form 1 -->
        <div id="form1" class="form-container active">
            <div class="card bg-light-subtle text-black mb-4">
                <div class="card-body"><b>Form 1</b></div>
                <div class="card-footer">
                    <form>
                        <div class="form-group">
                            <label for="input1">Field 1</label>
                            <input type="text" class="form-control" id="input1" placeholder="Enter something...">
                        </div>
                        <div class="form-group">
                            <label for="input2">Field 2</label>
                            <input type="text" class="form-control" id="input2" placeholder="Enter something else...">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form 2 -->
        <div id="form2" class="form-container">
            <div class="card bg-light-subtle text-black mb-4">
                <div class="card-body"><b>Form 2</b></div>
                <div class="card-footer">
                    <form>
                        <div class="form-group">
                            <label for="textarea1">Textarea 1</label>
                            <textarea class="form-control" id="textarea1" rows="4" placeholder="Enter some text..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="textarea2">Textarea 2</label>
                            <textarea class="form-control" id="textarea2" rows="4" placeholder="Enter more text..."></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var switchElement = document.getElementById('formSwitch');
            var form1 = document.getElementById('form1');
            var form2 = document.getElementById('form2');

            switchElement.addEventListener('change', function() {
                if (this.checked) {
                    form1.classList.remove('active');
                    form2.classList.add('active');
                } else {
                    form1.classList.add('active');
                    form2.classList.remove('active');
                }
            });

            // Initialize form visibility
            if (switchElement.checked) {
                form1.classList.remove('active');
                form2.classList.add('active');
            } else {
                form1.classList.add('active');
                form2.classList.remove('active');
            }
        });
    </script>
</body>
</html>
