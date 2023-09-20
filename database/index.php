<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encryption App</title>
    <!-- Include Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Encryption App</h2>
        <form id="encryption-form">
            <div class="form-group">
                <label for="user-input">User Input</label>
                <input type="text" class="form-control" id="user-input" name="user-input" required>
            </div>
            <button type="submit" class="btn btn-primary">Encrypt</button>
        </form>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>User Input</th>
                    <th>Stored in DB</th>
                    <th>Retrieved from DB</th>
                </tr>
            </thead>
            <tbody id="output-table">
                <!-- Output rows will be added here -->
            </tbody>
        </table>

        <!-- Table for mirroring the data -->
        <h3>DB Mirror Table</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>User Input</th>
                    <th>Timestamp</th> <!-- Add a new column for Timestamp -->
                </tr>
            </thead>
            <tbody id="db-mirror-table">
                <!-- Mirror rows will be added here -->
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS and jQuery via CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadMirrorData() {
                $.ajax({
                    type: "GET",
                    url: "get_mirror_data.php",
                    dataType: "json",
                    success: function(mirrorData) {
                        // Clear the existing mirror table
                        $('#db-mirror-table').empty();

                        // Add the mirror data to the "DB Mirror Table" including timestamps
                        for (var i = 0; i < mirrorData.length; i++) {
                            var mirrorRow = '<tr>' +
                                '<td>' + mirrorData[i].userinput + '</td>' +
                                '<td>' + mirrorData[i].time + '</td>' + // Display timestamp
                                '</tr>';
                            $('#db-mirror-table').append(mirrorRow);
                        }
                    },
                    error: function() {
                        alert("An error occurred while loading mirror data.");
                    }
                });
            }

            $('#encryption-form').submit(function(e) {
                e.preventDefault();

                // Get user input
                var userInput = $('#user-input').val();

                // Send the user input to the PHP script using AJAX
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: { "user-input": userInput },
                    dataType: "json",
                    success: function(response) {
                        // Update the main table with the response data
                        var newRow = '<tr>' +
                            '<td>' + response.userInput + '</td>' +
                            '<td>' + response.encryptedOutput + '</td>' +
                            '<td>' + response.decryptedInput + '</td>' +
                            '</tr>';
                        $('#output-table').append(newRow);
                        loadMirrorData();
                        $('#user-input').val('');
                    },
                    error: function() {
                        alert("An error occurred while processing the request.");
                    }
                });
            });

            // Load mirror data on page load
            loadMirrorData();
        });
    </script>
</body>
</html>
