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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete'])) {
        $sno = $_GET['delete'];
        $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        $snoEdit = $_POST['snoEdit'];
        $titleEdit = $_POST['titleEdit'];
        $descriptionEdit = $_POST['descriptionEdit'];

        $sql = "UPDATE `notes` SET `title` = '$titleEdit' , `description` = '$descriptionEdit' WHERE `notes`.`sno` = $snoEdit";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // echo "ashcoisanoivn";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
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

?>



<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/crud/index.php" method="POST" style="margin-bottom: 0;">
                <div class="modal-body" style="padding-bottom: 0;">
                    <input type="hidden" name="snoEdit" id="snoEdit">
                    <div class="form-group">
                        <label for="title">Note Title</label>
                        <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                            aria-describedby="emailHelp">
                    </div>

                    <div class="form-group">
                        <label for="desc">Note Description</label>
                        <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="float-left btn btn-danger" data-dismiss="modal">Discaard</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: "Poppins", sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
            background-color: transparent;
        }

        a:hover {
            color: #bdbdb9;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            /* border-color: #80bdff; */
            outline: 0;
            box-shadow: none;
        }
        .heading{
            transition: .4s all;
        }

        .heading::after {
            content: "";
            width: 0;
            height: 0.26rem;
            background: #17a2b8;
            position: absolute;
            left: 0.6rem;
            top: 3rem;
            transition: 0.5s;
        }
        .heading:hover::after{
            width: 30%;
        }
    </style>
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



    <div class="container-fluid mb-4" style="background: #dcdcdc36; padding: 1.5rem;">
        <div class="row">
            <div class="col-lg-4">
                <div class="card p-2">
                    <h2 class="heading" style="font-weight: 500;">Add a Note</h2>
                    <form action="/crud/index.php" method="POST">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                aria-describedby="emailHelp">
                        </div>

                        <div class="form-group">
                            <label for="desc">Note Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info mt-2" id="addNoteBtn">Add Note</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-2">
                    <table id="myTable" class="table table-striped table-bordered " style="width:100%">
                        <thead>
                            <tr class="bg-info text-white">
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
                     <td > <button class='edit btn btn-sm btn-info'  id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-danger'  id=d" . $row['sno'] . ">Delete</button> </td>
               </tr>";

                                $serno = $serno + 1;

                            }

                            ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="container">

    </div>





    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();

        });
    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[1].innerText;
                description = tr.getElementsByTagName("td")[2].innerText;
                console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');

            })
        })


        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1); //The substr() method extracts a part of a string ,begins at a specified position, and returns a specified number of characters and does not change the original string.
                // console.log(sno);
                if (confirm("Are you sure you want to delete this note!")) {
                    // confirm method displays a dialog box with a message, an OK button, and a Cancel button. returns true if the user clicked "OK", otherwise false
                    console.log("yes");
                    window.location = `/crud/index.php?delete=${sno}`;
                    //give us path in url i.e key value pair like -> delete=23
                }
                else {
                    console.log("no");
                }
            })
        })




    </script>

</body>

</html>