<?php
    // Connecting to Data Base
    $username = "root";
    $password = "";
    $servername = "localhost";
    $conn = mysqli_connect($servername,$username,$password);

    $insert = false;
    $update = false;
    $delete = false;

    if (isset($_GET['delete_'])) {
        $sno = $_GET['delete_'];
        $delete = "DELETE FROM `crud_notes`.`notes` WHERE `Sno` = '$sno'";
        $deleting = mysqli_query($conn,$delete);
        $delete = true;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['snoedit'])){
            // Update The record
            $title = $_POST['edittitle'];
            $descripation = $_POST['editDescripation'];
            $sno = $_POST['snoedit'];
            $update = "UPDATE `crud_notes`.`notes` SET `title` = '$title' , `descripation` = '$descripation' WHERE `notes`.`Sno` = '$sno'";
            $result = mysqli_query($conn,$update);
            if ($result) {
                $update = true;
            }
        }
        else {
            $title = $_POST['title'];
            $description = $_POST['descripation'];
            $insert = "INSERT INTO `crud_notes`.`notes` (`title`,`descripation`) VALUES ('$title','$description')";
            $result = mysqli_query($conn,$insert);
            if ($result) {
                $insert = true;
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id = "modalBox">
        <div id="background"></div>
        <div id="main-data">
            <div id="cross">
                <h2>Edit This Note</h2>
                <h2 id="modalBoxCross">&times;</h2>
            </div>
            <form action="index.php" method = "post">
                <input type="hidden" name="snoedit" id="snoedit">
                <label for="title">Title</label>
                <input type="text" placeholder="Title" id="editTitle" name = "edittitle" required>
                <label for="descripation" id="descriptionModelLabel">Descripation</label>
                <textarea id="editDescripation" cols="30" rows="10" name = "editDescripation"></textarea>
                <input type="submit" id="editSubmit" value="Update Note">
            </form>
        </div>
    </div>
    <div class="header">
        <div>
            <h2>CRUD APP</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php">About Us</a></li>
                <li><a href="index.php">Contact Us</a></li>
            </ul>
        </div>
        <form action="#">
            <input type="text" placeholder="Search" id="search">
            <input type="submit" value="Search">
        </form>
    </div>
    <?php
        if ($insert) {
            echo "<div id = 'alert'>
                <p><strong>Success</strong> Your Note Added Successfully</p>
                <p>&times;</p>
            </div>";
        }
        if ($update) {
            echo "<div id = 'alert'>
                <p><strong>Updated</strong> Your Note Updated Successfully</p>
                <p>&times;</p>
            </div>";
        }
        if ($delete) {
            echo "<div id = 'alert'>
                <p><strong>Delete</strong> Your Note Deleted Successfully</p>
                <p>&times;</p>
            </div>";
        }

    ?>

    <div class="container">
        <div id="mainForm">        
            <h2>Add Note</h2>    
            <form action="index.php" method = "post">
                
                <label for="title">Title</label>
                <input type="text" name = "title" placeholder="Title" id="title" required>
                <label for="descripation">Description</label>
                <textarea name="descripation" id="descripation" cols="30" rows="10" required></textarea>
                <input type="submit" id="submit">
            </form>
        </div>
        <div id="data">
            <div id = "numberOfEntriesToShow">
                <span>Show Entries </span>
                <select name="numberOfEntriesToShowtoUser" id="numberOfEntriesToShowtoUser">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>
            </div>
            <table border="1">
                <thead>
                    <th>So. no.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <?php
                        $number = 1;
                        $sql = "SELECT * FROM `crud_notes`.`notes`";
                        $result = mysqli_query($conn,$sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <th>".$number."</th>
                                    <td>".$row['Title']."</td>
                                    <td>".$row['descripation']."</td>
                                    <td id = 'updateNote'>
                                        <button class = 'edit' id=".$row['Sno'].">EDIT</button>
                                        <button name = 'delete' class = 'delete' id=d".$row['Sno']." >DELETE</button>
                                    </td>
                                </tr>";
                        $number = $number + 1;
                        }
                        
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>
<script>
    let alert = document.getElementById("alert");
    if(alert != null){
        let alertcross = alert.children[1];
        alertcross.addEventListener("click",alertclick);
        function alertclick(){
        alert.style.display = "none";
    }
    setTimeout(alertclick,2000);
    }

    let danger = document.getElementById("danger");
    if(danger != null){
        let dangercross = danger.children[1];
        dangercross.addEventListener("click",()=>{
            danger.style.display = "none";
        });
    }
</script>

<script>
    let edits = document.querySelectorAll(".edit");
    let modalBox = document.getElementById("modalBox");
    Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
            let tr = e.target.parentNode.parentNode;
            let title = tr.getElementsByTagName("td")[0].innerText;
            let descripation = tr.getElementsByTagName("td")[1].innerText;
            let titleEdit = document.getElementById("editTitle");
            let descripationEdit = document.getElementById("editDescripation");
            titleEdit.value = title;
            descripationEdit.value = descripation;
            let snoedit = document.getElementById("snoedit");
            snoedit.value = e.target.id;
            modalBox.style.display = "block";
        });
    });
    let modalBoxCross = document.getElementById("modalBoxCross");
    modalBoxCross.addEventListener("click",()=>{
        modalBox.style.display = "none";
    });

    let deletes = document.querySelectorAll(".delete");
    Array.from(deletes).forEach(element => {
        element.addEventListener("click",(e)=>{
            let sno = e.target.id.substr(1,);
            if (confirm("Are You Sure You want delete this note")) {
                window.location = `index.php?delete = ${sno}`;
            }
        });
    });

</script>