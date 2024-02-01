<?php


// Connecting to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

$conn = new mysqli($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connected successfully! to " . $database . " <br>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])){
        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];
        
        $sql="UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno"; 
        $result = mysqli_query($conn,$sql); 
        if($result){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Data Update.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>'
        }
        else{
            echo "We could not update the record successfully";
        }
     }
     else {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "INSERT INTO `notes` ( `title`, `description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("Location: /crud/index.php");
            exit();


        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Unable to save data to the database.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
    }
     }

 


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if (isset($_POST['title'], $_POST['description'])) {
//         $title = $_POST['title'];
//         $description = $_POST['description'];

//         $sql = "INSERT INTO `notes` ( `title`, `description`) VALUES ('$title', '$description')";
//         $result = mysqli_query($conn, $sql);

//         if ($result) {
//             header("Location: /crud/index.php");
//             exit();


//         } else {
//             echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
//                         <strong>Error!</strong> Unable to save data to the database.
//                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//                       </div>';
//         }
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curd App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="container-fluid navbar-dark bg-dark">
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-center py-3  border-bottom">

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
                <li><a href="#" class="nav-link px-2 link-secondary">About</a></li>
                <li><a href="#" class="nav-link px-2 link-secondary">Pricing</a></li>
                <li><a href="#" class="nav-link px-2 link-secondary">FAQs</a></li>
                <li><a href="#" class="nav-link px-2 link-secondary">Contact</a></li>
            </ul>
        </header>
    </div>

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit this Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/crud/index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>




    <div class="container my-4">
        <h2 style="font-weight: 600; text-decoration: underline;">Add a Note</h2>
        <form action="/crud/index.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
                <label for="desc">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2" id="addNoteBtn">Add Note</button>
        </form>
    </div>

    <div class="container">
        <table id="myTable" class="table table-striped table-bordered " style="width:100%">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $serno = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "  <tr>
                     <td> $serno </td>
                     <td> " . $row['title'] . "</td>
                     <td>" . $row['description'] . "</td>
                     <td > <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button> </td>
               </tr>";

                    $serno = $serno + 1;

                }

                ?>

            </tbody>
        </table>
    </div>





    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();

        });
    </script>
    <script>
        let edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode ;
                title = tr.getElementsByTagName("td")[1].innerText;
                description = tr.getElementsByTagName("td")[2].innerText;
                console.log(title , description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#exampleModal').modal('toggle');
            })
        })

        
    </script>

</body>

</html>