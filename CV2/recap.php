<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Recapitulatif des Informations</title>
</head>

<body>

  <?php
  function handleFileUpload($cookieExpireTime)
  {
    //name the file with the user name
    $target_dir = "media/";
    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if file already exists
    if (file_exists($target_file)) {
      $uploadOk = 0;
    }
    //save the file
    if ($uploadOk == 0) {
    } else {
      if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
        // setcookie("message", "The file " . htmlspecialchars(basename($_FILES["fileUpload"]["name"])) . " has been uploaded.", $cookieExpireTime);
      } else {
        setcookie("message", "Sorry, there was an error uploading your file.", $cookieExpireTime);
      }
    }
  }

  function saveDataToFile($data)
  {
    $filePath = "saved_data.txt";
    // Check if the file already exists to prepend a newline character
    $prependNewline = file_exists($filePath) ? "\n" : "";
    $content = $prependNewline;
    foreach ($data as $key => $value) {
      if (is_array($value)) {
        $value = implode(", ", $value);
      }
      $content .= "$key: $value\n";
    }
    $content .= "----------------------------------\n";
    file_put_contents($filePath, $content, FILE_APPEND);
    return "Data saved successfully!";
  }



  // Check if form was submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //if the action is save => save the data to a file
  
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
      $data = [
        "Name" => $_COOKIE['name'],
        "First name" => $_COOKIE['firstname'],
        "Age" => $_COOKIE['age'],
        "Phone number" => $_COOKIE['phone'],
        "Email" => $_COOKIE['email'],
        "Year" => $_COOKIE['year'],
        "Modules" => $_COOKIE['modules'],
        "Projects" => $_COOKIE['projects'],
        "Remarks" => $_COOKIE['remarks'],
        "Interests" => $_COOKIE['interests'],
        "Languages" => $_COOKIE['languages'],
        "Projects list" => $_COOKIE['projects_list'],
        "Profile" => $_COOKIE['Profile'] ?? "media/default.png"
      ];
      $message = saveDataToFile($data);
      echo "<h1>your file is saved</h1> <br> ";

      echo "<br><be><a href='formulaire.php'><button>Back to the form</button></a>";
      return;

    } else {

      // Process form data
      $name = htmlspecialchars($_POST['name']);
      $firstname = htmlspecialchars($_POST['firstname']);
      $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
      $phone = htmlspecialchars($_POST['phone']);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      $year = htmlspecialchars($_POST['year']);
      $modules = isset($_POST['modules']) ? $_POST['modules'] : [];
      $projects = filter_var($_POST['projects'], FILTER_SANITIZE_NUMBER_INT);
      $remarks = htmlspecialchars($_POST['remarks']);
      $interests = htmlspecialchars($_POST['interests']);
      $languages = htmlspecialchars($_POST['languages']);

      $projects_list = htmlspecialchars($_POST['projects_list']);
      $uploadedFilePath = isset($_COOKIE['Profile']) ? $_COOKIE['Profile'] : 'default.png';


      //set cookiis 
      $cookieExpireTime = time() + (86400 * 30); //1 day
      setcookie("name", $name, $cookieExpireTime);
      setcookie("firstname", $firstname, $cookieExpireTime);
      setcookie("age", $age, $cookieExpireTime);
      setcookie("phone", $phone, $cookieExpireTime);
      setcookie("email", $email, $cookieExpireTime);
      setcookie("year", $year, $cookieExpireTime);
      setcookie("modules", implode(", ", $modules), $cookieExpireTime);
      setcookie("projects", $projects, $cookieExpireTime);
      setcookie("remarks", $remarks, $cookieExpireTime);
      setcookie("interests", $interests, $cookieExpireTime);
      setcookie("languages", $languages, $cookieExpireTime);
      setcookie("projects_list", $projects_list, $cookieExpireTime);
      handleFileUpload($cookieExpireTime);
      setcookie("Profile", "media/" . $_FILES["fileUpload"]["name"], $cookieExpireTime);    }

    $messageContent = '';
    if (isset($_COOKIE['message'])) {
      $message = $_COOKIE['message'];
      $messageContent = <<<HTML
          <div class="message">
            <!-- message from cookies -->
            <p>$message</p>
          </div>
HTML;
    }

    $modulesList = implode(", ", $modules);
    $uploadedFilePath = "media/" . $_FILES["fileUpload"]["name"];

    echo <<<HTML
    <div class="info-container">
      <h1>Recapitulatif des Informations</h1>
      <div class="section">
        <h2>Personal Information</h2>

         $messageContent

  <div class="profile-pic">
    <img src="$uploadedFilePath" alt="Profile Picture" style="width:150px; height:auto;">
  </div>
        <p><strong>Name:</strong> $name</p>
        <p><strong>First name:</strong> $firstname</p>
        <p><strong>Age:</strong> $age</p>
        <p><strong>Phone number:</strong> $phone</p>
        <p><strong>Email:</strong> $email</p>
      </div>
      <div class="section">
        <h2>Academic Information</h2>
        <p><strong>Year:</strong> $year</p>
        <p><strong>Modules followed this year:</strong> $modulesList</p>
        <p><strong>Number of projects done this year:</strong> $projects</p>
      </div>
      <div class="section">
        <h2>Other Information</h2>
        <p><strong>Remarks:</strong> $remarks</p>
        <p><strong>Interests:</strong> $interests</p>
        <p><strong>Languages spoken:</strong> $languages</p>
        <p><strong>Projects:</strong> $projects_list</p>
      </div>
        <a href="formulaire.php"><button>MODIFIER</button></a>
         <form method="POST" >
          
        <input type="hidden" name="action" value="save">
        <button type="submit">Save</button>
    </form>
    </div>
HTML;
  } else {
    header("Location: formulaire.php");
  }
  ?>

</body>

</html>