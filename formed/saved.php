<?php
session_start();
include "dbhelper.php"; // Ensure this file contains the database connection logic

// Ensure the form token is set
if (!isset($_SESSION['form_token'])) {
    die("Invalid form submission.");
}

// Validate the form token
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['form_token']) {
    die("Invalid form submission.");
}

// Collecting data from the form
$name = $_POST['name'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$status = $_POST['status'];
$date_of_birth = $_POST['date_of_birth'];
$place_of_birth = $_POST['place_of_birth'];
$home_address = $_POST['home_address'];
$occupation = $_POST['occupation'];
$religion = $_POST['religion'];
$contact_no = $_POST['contact_no'];
$pantawid = $_POST['pantawid'];

// Insert into registrations table
$stmt = $db->prepare("INSERT INTO registrations (name, age, sex, status, date_of_birth, place_of_birth, home_address, occupation, religion, contact_no, pantawid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssssssss", $name, $age, $sex, $status, $date_of_birth, $place_of_birth, $home_address, $occupation, $religion, $contact_no, $pantawid);

if ($stmt->execute()) {
    $registration_id = $stmt->insert_id; // Get the last inserted ID

    // Insert family composition data
    if (!empty($_POST['family_name'])) {
        $family_names = $_POST['family_name'];
        $family_relationships = $_POST['family_relationship'];
        $family_ages = $_POST['family_age'];
        $family_birthdays = $_POST['family_birthday'];
        $family_occupations = $_POST['family_occupation'];

        for ($i = 0; $i < count($family_names); $i++) {
            $family_stmt = $db->prepare("INSERT INTO family_composition (registration_id, name, relationship, age, birthday, occupation) VALUES (?, ?, ?, ?, ?, ?)");
            $family_stmt->bind_param("ississ", $registration_id, $family_names[$i], $family_relationships[$i], $family_ages[$i], $family_birthdays[$i], $family_occupations[$i]);

            if (!$family_stmt->execute()) {
                echo "Error inserting family member: " . $family_stmt->error;
            }
            $family_stmt->close(); // Close family statement after each execution
        }
    }
}

    // Insert educational attainment data
    $elementary = $_POST['elementary'];
    $high_school = $_POST['high_school'];
    $vocational = $_POST['vocational'];
    $college = $_POST['college'];
    $others = $_POST['others'];

    $education_stmt = $db->prepare("INSERT INTO educational_attainment (registration_id, elementary, high_school, vocational, college, others) VALUES (?, ?, ?, ?, ?, ?)");
    $education_stmt->bind_param("isssss", $registration_id, $elementary, $high_school, $vocational, $college, $others);

    if (!$education_stmt->execute()) {
        echo "Error inserting educational attainment: " . $education_stmt->error;
    }
    $education_stmt->close(); // Close educational statement

    // Insert community involvement data
    $school = $_POST['school'];
    $civic = $_POST['civic'];
    $community = $_POST['community'];
    $workspace = $_POST['workspace'];

$community_stmt = $db->prepare("INSERT INTO community_involvement (registration_id, school, civic, community, workspace) VALUES (?, ?, ?, ?, ?)");
    $community_stmt->bind_param("sssss", $registration_id, $school, $civic, $community, $workspace);

    if (!$community_stmt->execute()) {
        echo "Error inserting community involvement: " . $community_stmt->error;
    }
    $community_stmt->close(); // Close community statement

    // Insert seminars and trainings data
    if (!empty($_POST['seminar_title'])) {
        $seminar_title = $_POST['seminar_title'];
        $seminar_date = $_POST['seminar_date'];
        $seminar_organizer = $_POST['seminar_organizer'];
        

        for ($i = 0; $i < count($seminar_title); $i++) {
            $seminar_stmt = $db->prepare("INSERT INTO seminars_trainings  (registration_id, title, date, organizer) VALUES (?, ?, ?, ?)");
            $seminar_stmt->bind_param("isis", $registration_id, $seminar_title[$i], $seminar_date[$i], $seminar_organizer[$i]);

            if (!$seminar_stmt->execute()) {
                echo "Error inserting title: " . $seminar_stmt->error;
            }
            $seminar_stmt->close(); // Close family statement after each execution
        }
    }


// Close the main statement and database connection
$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Summary</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Registration Summary</h1>

        <div class="card mb-4">
    <div class="card-header bg-primary text-white">Identifying Data</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Name</th>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Age</th>
                <td><input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Sex</th>
                <td><input type="text" name="sex" value="<?php echo htmlspecialchars($sex); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Status</th>
                <td><input type="text" name="status" value="<?php echo htmlspecialchars($status); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Date of Birth</th>
                <td><input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Place of Birth</th>
                <td><input type="text" name="place_of_birth" value="<?php echo htmlspecialchars($place_of_birth); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Home Address</th>
                <td><input type="text" name="home_address" value="<?php echo htmlspecialchars($home_address); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Occupation</th>
                <td><input type="text" name="occupation" value="<?php echo htmlspecialchars($occupation); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Religion</th>
                <td><input type="text" name="religion" value="<?php echo htmlspecialchars($religion); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Contact No.</th>
                <td><input type="text" name="contact_no" value="<?php echo htmlspecialchars($contact_no); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Pantawid</th>
                <td><input type="text" name="pantawid" value="<?php echo htmlspecialchars($pantawid); ?>" class="form-control" /></td>
            </tr>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-secondary text-white">Family Composition</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>Age</th>
                    <th>Birthday</th>
                    <th>Occupation</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_POST['family_name'])): ?>
                    <?php foreach ($_POST['family_name'] as $index => $familyName): ?>
                        <tr>
                            <td><input type="text" name="family_name[]" value="<?php echo htmlspecialchars($familyName); ?>" class="form-control" /></td>
                            <td><input type="text" name="family_relationship[]" value="<?php echo htmlspecialchars($_POST['family_relationship'][$index]); ?>" class="form-control" /></td>
                            <td><input type="number" name="family_age[]" value="<?php echo htmlspecialchars($_POST['family_age'][$index]); ?>" class="form-control" /></td>
                            <td><input type="date" name="family_birthday[]" value="<?php echo htmlspecialchars($_POST['family_birthday'][$index]); ?>" class="form-control" /></td>
                            <td><input type="text" name="family_occupation[]" value="<?php echo htmlspecialchars($_POST['family_occupation'][$index]); ?>" class="form-control" /></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No family members added.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Educational Attainment</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Elementary</th>
                <td><input type="text" name="elementary" value="<?php echo htmlspecialchars($elementary); ?>" class="form-control" /></td>
            </tr>
            <tr><th>High School</th>
                <td><input type="text" name="high_school" value="<?php echo htmlspecialchars($high_school); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Vocational</th>
                <td><input type="text" name="vocational" value="<?php echo htmlspecialchars($vocational); ?>" class="form-control" /></td>
            </tr>
            <tr><th>College</th>
                <td><input type="text" name="college" value="<?php echo htmlspecialchars($college); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Others</th>
                <td><input type="text" name="others" value="<?php echo htmlspecialchars($others); ?>" class="form-control" /></td>
            </tr>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Community Involvement</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>School</th>
                <td><input type="text" name="school" value="<?php echo htmlspecialchars($school); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Civic</th>
                <td><input type="text" name="civic" value="<?php echo htmlspecialchars($civic); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Community</th>
                <td><input type="text" name="community" value="<?php echo htmlspecialchars($community); ?>" class="form-control" /></td>
            </tr>
            <tr><th>Workplace</th>
                <td><input type="text" name="workspace" value="<?php echo htmlspecialchars($workspace); ?>" class="form-control" /></td>
            </tr>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-secondary text-white">Seminars and Trainings</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Organizer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_POST['seminar_title'])): ?>
                    <?php foreach ($_POST['seminar_title'] as $index => $seminarTitle): ?>
                        <tr>
                            <td><input type="text" name="seminar_title[]" value="<?php echo htmlspecialchars($seminarTitle); ?>" class="form-control" /></td>
                            <td><input type="date" name="seminar_date[]" value="<?php echo htmlspecialchars($_POST['seminar_date'][$index]); ?>" class="form-control" /></td>
                            <td><input type="text" name="seminar_organizer[]" value="<?php echo htmlspecialchars($_POST['seminar_organizer'][$index]); ?>" class="form-control" /></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No seminars and trainings added.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Button to save data to database -->
<form action="saved.php" method="POST">
    <?php foreach ($_POST as $key => $value): ?>
        <?php if (is_array($value)): ?>
            <?php foreach ($value as $subValue): ?>
                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>[]" value="<?php echo htmlspecialchars($subValue); ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <button type="submit" name="save_to_database" class="btn btn-primary">Save to Database</button>
</form>
</div>
</body>
</html>