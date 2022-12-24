<?php
include('../settings.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Character Selection</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1>Please Select a Character</h1>
        <ul style="list-style-type:none;">
        <?php
        
        
        $query = "SELECT * FROM characters WHERE ownerID='1'";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['first'] . " "  . $row['last'] . "</li>";
            }
        }
            ?>
        </ul>
        <script>
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'doesSteamExist',
      steam: steam
    },
    function(response){
        console.log(response);
    });
        </script>
        
    </body>
</html>
