<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Form Validation</title>
    <style type="text/css">
        body    {font-family:sans-serif;
                background-color:lightyellow}
        table{ background-color:lightblue;
            border-collapse:collapse;
            border:1px solid gray;}
        td{padding:5px;}
        tr:nth-child(odd){background-color:white;}
        p {margin: 0px}
        .error {color: red}
        p.head {font-weight:bold; margin-top: 10px;}
    </style>
</head>
<body>
<?php
    $URL = $_POST["URL"];
    $description = $_POST["description"];
    $servername = "CopDataSvr.ccec.unf.edu";
    $username = "n00942101";
    $password = "42101Spr2017#";
    $insertQuery = "INSERT INTO urltable VALUES('$URL', '$description')";
    $selectQuery = "SELECT * FROM urltable WHERE URL ='$URL'";
    $concatQuery ="UPDATE urltable SET description= CONCAT(description, ' ','$description') WHERE URL = '$URL'";
    //Create Connection
$conn = new mysqli($servername, $username, $password, "n00942101");

//Check connection
if($conn ->connect_errno){
    echo "Failed to connect to MYSQL: (".$conn->connect_errno .") ".$conn->connect_error;

}


//if select from table exists,  then concat input else insert into table
if($URL != "") {
    if ($result = $conn->query($selectQuery)) {

        if (mysqli_num_rows($result) == 0) {

            if (!$conn->query($insertQuery)) {
                echo "could not execute query!" . $conn->errno;
            } else {

                print("<p>Successfully added ".$URL. " with description: ".$description. ", to the database</p>");

            }
        } else {///updates existing description if url already exists.
            $conn->query($concatQuery);
            print("<p>Successfully updated ".$URL." 's description by adding ".$description.".</p>");
        }
    }
}else {
    $select = $_POST["keyword"];
    if($select == "*"){
        $query = "SELECT * FROM urltable";
    }else {
        $query = "SELECT * FROM urltable WHERE URL = '$select' OR description = '$select'";
    }
    if (!($selectionResult = $conn->query($query))) {
        echo "could not get selection";
    }
}
    mysqli_close($conn);

    ?>

<?php if($selectionResult){
print("<table>");
   print(" <caption>Results of Select ".$select." FROM urltable</caption>");

        while($row = mysqli_fetch_row($selectionResult)) {
            print("<tr>");
            foreach ($row as $key => $value)
                print("<td>$value</td>");
            print("</tr>");
        }

print("</table>");
print("<p>Your search yeielded ");
print(mysqli_num_rows($selectionResult));
print(" results.</p>");
}?>

</body>

</html>
