<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kriti";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = $passwordErr = $confirmPasswordErr = $dropdownErr = $termsErr = "";
$name = $email = $gender = $comment = $website = $password = $confirmPassword = $dropdown = $terms = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
  }

  if (empty($_POST["confirm_password"])) {
    $confirmPasswordErr = "Please confirm password";
  } else {
    $confirmPassword = test_input($_POST["confirm_password"]);
    if ($password != $confirmPassword) {
      $confirmPasswordErr = "Passwords do not match";
    }
  }
    
  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $websiteErr = "Invalid URL";
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }

  if (empty($_POST["dropdown"])) {
    $dropdownErr = "Please select an option";
  } else {
    $dropdown = test_input($_POST["dropdown"]);
  }

  if (!isset($_POST["terms"])) {
    $termsErr = "You must accept the terms";
  } else {
    $terms = test_input($_POST["terms"]);
  }

  // Insert data into database if no errors
  if ($nameErr == "" && $emailErr == "" && $websiteErr == "" && $genderErr == "" && $passwordErr == "" && $confirmPasswordErr == "" && $dropdownErr == "" && $termsErr == "") {
    $stmt = $conn->prepare("INSERT INTO form_data (name, email, password, website, comment, gender, dropdown, terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $password, $website, $comment, $gender, $dropdown, $terms);

    if ($stmt->execute()) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
  }
}

$conn->close();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>PHP Form Validation with More Fields</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>

  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>

  Password: <input type="password" name="password" value="<?php echo $password;?>">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>

  Confirm Password: <input type="password" name="confirm_password" value="<?php echo $confirmPassword;?>">
  <span class="error">* <?php echo $confirmPasswordErr;?></span>
  <br><br>

  Website: <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>

  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>

  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>

  Select an option:
  <select name="dropdown">
    <option value="">Select...</option>
    <option value="Option1" <?php if (isset($dropdown) && $dropdown=="Option1") echo "selected";?>>Option 1</option>
    <option value="Option2" <?php if (isset($dropdown) && $dropdown=="Option2") echo "selected";?>>Option 2</option>
    <option value="Option3" <?php if (isset($dropdown) && $dropdown=="Option3") echo "selected";?>>Option 3</option>
  </select>
  <span class="error">* <?php echo $dropdownErr;?></span>
  <br><br>

  <input type="checkbox" name="terms" <?php if (isset($terms) && $terms=="on") echo "checked";?>> I accept the terms and conditions
  <span class="error">* <?php echo $termsErr;?></span>
  <br><br>

  <input type="submit" name="submit" value="Submit">  
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
echo "<br>";
echo $dropdown;
echo "<br>";
echo $terms ? "Accepted" : "Not accepted";
?>

</body>
</html>
