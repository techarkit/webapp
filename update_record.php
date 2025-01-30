<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <style type="text/css">
        body {
            background-color: #ffffff;
        }
        div {
            border-bottom: 2px solid #aaa;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }
        div h2 {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the serial number and at least one field to update are provided
        if (isset($_POST['sn']) && !empty($_POST['sn'])) {
            // Connect to the database
            include('connectdb.php');

            // Prepare an UPDATE query
            $query = "UPDATE newstorage SET ";
            $params = array();
            foreach ($_POST as $key => $value) {
                if ($key != 'sn') {
                    $query .= "$key = ?, ";
                    $params[] = $value;
                }
            }
            // Remove the trailing comma and space from the query
            $query = rtrim($query, ', ');
            $query .= " WHERE sn = ?";
            $params[] = $_POST['sn'];

            // Prepare and execute the statement
            $stmt = $mysqli->prepare($query);
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                echo "Record updated successfully.<br>";
                echo "<a href='netappstorage.php'>Go Back</a>";
            } else {
                echo "Error updating record: " . $mysqli->error;
            }

            // Close statement and database connection
            $stmt->close();
            $mysqli->close();
        } else {
            echo "Serial number not provided.";
        }
    } else {
        echo "Invalid request.";
    }
    ?>
</body>
</html>
