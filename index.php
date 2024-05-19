<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container">
    <h1>Student Management System</h1>
    <hr>
    <div class="card">
        <div class="card-header">Add / Edit Student</div>
        <div class="card-body">
            <form id="student-form" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10}">
                </div>
                <div class="form-group">
                    <label for="series">Series</label>
                    <select class="form-control" id="series" name="series" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="group">Group</label>
                    <input type="text" class="form-control" id="group" name="group" required pattern="\d{3}">
                </div>
                <input type="hidden" id="action" name="action" value="add">
                <input type="hidden" id="id" name="id">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <hr>

    <h2>Existing Students</h2>
    <div class="table-responsive">
        <table id="student-table" class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Series</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aici for fi afisati dinamic studentii -->
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Functia de fetch si display
        function fetchStudents() {
            $.get("data.php", function (data) {
                $("#student-table tbody").html(data);
            });
        }

        // Functia de adaugare student
        $("#student-form").submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post("data.php", formData, function () {
                fetchStudents(); // Dam refresh la lista dupa ce adaugam un nou student
                $("#student-form")[0].reset();
                $("#action").val("add");
                $("#id").val("");
            });
        });

        // Functia de stergere student
        $(document).on("click", ".delete-btn", function () {
            var id = $(this).data("id");
            if (confirm("Are you sure you want to delete this student?")) {
                var formData = {action: "delete", id: id};
                $.post("data.php", formData, function () {
                    fetchStudents(); // Dam refresh la lista dupa ce adaugam un nou student
                });
            }
        });

        // Functia de editare student
        $(document).on("click", ".edit-btn", function () {
            var id = $(this).data("id");
            var name = $(this).closest('tr').find('td:eq(0)').text();
            var dob = $(this).closest('tr').find('td:eq(1)').text();
            var email = $(this).closest('tr').find('td:eq(2)').text();
            var phone = $(this).closest('tr').find('td:eq(3)').text();
            var series = $(this).closest('tr').find('td:eq(4)').text();
            var group = $(this).closest('tr').find('td:eq(5)').text();
            $("#name").val(name);
            $("#dob").val(dob);
            $("#email").val(email);
            $("#phone").val(phone);
            $("#series").val(series);
            $("#group").val(group);
            $("#id").val(id);
            $("#action").val("update");
        });
        // Mesaje custom pentru validare
        $("#email").on("invalid", function () {
            this.setCustomValidity("Please enter a valid email address.");
        });

        $("#email").on("input", function () {
            this.setCustomValidity("");
        });

        $("#phone").on("invalid", function () {
            this.setCustomValidity("Please enter a valid 10-digit phone number.");
        });

        $("#phone").on("input", function () {
            this.setCustomValidity("");
        });

        $("#group").on("invalid", function () {
            this.setCustomValidity("Group must be exactly 3 digits.");
        });

        $("#group").on("input", function () {
            this.setCustomValidity("");
        });
        // Initial fetch of students
        fetchStudents();
    });
</script>

</body>
</html>
