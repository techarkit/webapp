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
    <h2>Edit Record</h2>

    <?php
    // Check if serial number parameter is set
    if (isset($_GET['sn'])) {
        $sn = $_GET['sn'];

        // Connect to the database
        include('connectdb.php');

        // Fetch the record based on serial number
        $stmt = $mysqli->prepare("SELECT * FROM newstorage WHERE sn = ?");
        $stmt->bind_param("i", $sn);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display edit form if record exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form method="POST" action="update_record.php">
                <input type="hidden" name="sn" value="<?php echo $row['sn']; ?>">
                <?php
                // Dynamically generate input fields for each column
                foreach ($row as $column => $value) {
                    echo "<label for='$column'>$column:</label>";
                    echo "<input type='text' name='$column' id='$column' value='$value'><br>";
                }
                ?>
                <input type="submit" value="Update">
            </form>
            <?php
        } else {
            echo "Record not found.";
        }

        // Close statement and database connection
        $stmt->close();
        $mysqli->close();
    } else {
        echo "Invalid request.";
    }
    ?>
</body>
</html>
