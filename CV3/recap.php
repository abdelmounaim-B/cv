<?php
ob_start();
require('FPDF/fpdf.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'generetePDF') {
  $data = [
    "Name" => $_COOKIE['name'],
    "First name" => $_COOKIE['firstname'],
    "Age" => $_COOKIE['age'],
    "Phone number" => $_COOKIE['phone'],
    "Email" => $_COOKIE['email'],
    "Year" => $_COOKIE['year'],
    "Projects" => $_COOKIE['projects'],

    "Interests" => $_COOKIE['interests'],
    "Languages" => $_COOKIE['languages'],
    "Education and Internships" => $_COOKIE['Stages_formation'],
    "Profile" => $_COOKIE['Profile'] ?? "media/default.png"
  ];
  ob_end_clean();
  generatePdf($data);
  resetCookies();
  exit;
}


include('header.php');

function handleFileUpload($cookieExpireTime)
{
  //name the file with the user name
  $target_dir = "media/";
  $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  // Check if file already exists
  if (file_exists($target_file)) {
    setcookie("message", "Sorry, file already exists.", $cookieExpireTime);
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileUpload"]["size"] > 500000) {
    setcookie("message", "Sorry, your file is too large.", $cookieExpireTime);
    $uploadOk = 0;
  }
  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
    setcookie("message", "Sorry, only JPG, JPEG, PNG & GIF files are allowed.", $cookieExpireTime);
    $uploadOk = 0;
  }
  //save the file
  if ($uploadOk == 0) {
    setcookie("message", "Sorry, your file was not uploaded.", $cookieExpireTime);
  } else {
    if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
      setcookie("message", "The file " . htmlspecialchars(basename($_FILES["fileUpload"]["name"])) . " has been uploaded.", $cookieExpireTime);
    } else {
      setcookie("message", "Sorry, there was an error uploading your file.", $cookieExpireTime);
    }
  }
}


function generatePdf($data)
{
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetAutoPageBreak(true, 20);

  // Initial setup
  $pdf->SetFont('Arial', '', 12);
  $pdf->SetTextColor(33, 33, 33); 

  // Header Section
  if (!empty($data["Profile"]) && file_exists($data["Profile"])) {
    
    $pdf->Image($data["Profile"], 10, 10, 30);
    $pdf->Ln(50); 
  }

  foreach ($data as $key => $value) {
    if ($key == "Profile") continue; 

    $pdf->SetFont('Arial', 'B', 14); 
    $pdf->SetFillColor(230, 230, 230); 
    $pdf->Cell(0, 10, strtoupper($key), 0, 1, 'L', true); 

    $pdf->SetFont('Arial', '', 12); 
    $pdf->SetTextColor(50, 50, 50); 

    if (is_array($value)) {
      $value = implode(", ", $value);
    }

    $pdf->Ln(2);

    $pdf->MultiCell(0, 10, $value); 

    
    $pdf->Ln(6);
  }

  // Output the PDF
  $pdf->Output();
}


// Reset cookies function
function resetCookies()
{
  $past_time = time() - (86400 * 30);
  setcookie("name", "", $past_time);
  setcookie("firstname", "", $past_time);
  setcookie("age", "", $past_time);
  setcookie("phone", "", $past_time);
  setcookie("email", "", $past_time);
  setcookie("year", "", $past_time);
  setcookie("modules", "", $past_time);
  setcookie("projects", "", $past_time);
  setcookie("remarks", "", $past_time);
  setcookie("interests", "", $past_time);
  setcookie("languages", "", $past_time);
  setcookie("Stages_formation", "", $past_time);
  setcookie("Profile", "", $past_time);
}



// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $cookieExpireTime = time() + (86400 * 30); // 86400 = 1 day

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
  $Stages_formation = htmlspecialchars($_POST['Stages_formation']);
  $remarks = htmlspecialchars($_POST['remarks']);
  $interests = htmlspecialchars($_POST['interests']);

  //set cookiis 
  setcookie("name",$name ?? '',$cookieExpireTime);
  setcookie("firstname", $firstname ?? '', $cookieExpireTime);
  setcookie("age", $age ?? '', $cookieExpireTime);
  setcookie("phone", $phone ?? '', $cookieExpireTime);
  setcookie("email", $email ?? '', $cookieExpireTime);
  setcookie("year",$year ?? '',$cookieExpireTime);
  setcookie("modules", isset($modules) ? implode(", ", $modules) : '', $cookieExpireTime);
  setcookie("projects", $projects ?? '', $cookieExpireTime);
  setcookie("remarks", $remarks ?? '', $cookieExpireTime);
  setcookie("interests", $interests ?? '', $cookieExpireTime);
  setcookie("languages", $languages ?? '', $cookieExpireTime);
  setcookie("Stages_formation", $Stages_formation ?? '', $cookieExpireTime);
  if (isset($_FILES["fileUpload"]["name"])) {
    setcookie("Profile", "media/" . $_FILES["fileUpload"]["name"], $cookieExpireTime);
    handleFileUpload($cookieExpireTime); // Ensure this function is defined and handles file uploads appropriately
  }

  $modulesList  = implode(", ", $modules);
  $uploadedFilePath = "media/" . $_FILES["fileUpload"]["name"] ?? 'default.png';


  echo <<<HTML
    <div class="info-container">
      <h1>Recapitulatif des Informations</h1>
        <div class="profile-pic">
    <img src="$uploadedFilePath" alt="Profile Picture" style="width:150px; height:auto;">
  </div>
      <div class="section">
        <h2>Personal Information</h2>
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
        <p><strong>Projects:</strong> $Stages_formation</p>
      </div>
        <a href="formulaire.php"><button>MODIFIER</button></a>
    <form method="POST">
        <input type="hidden" name="action" value="generetePDF">
        <button type="submit">generate PDF</button>
    </form>
    </div>
HTML;

  if (isset($_POST['clear'])) {
    resetCookies(); // Reset all cookies
    header("Location: formulaire.php");
    exit();
  }
} else {

  header("Location: formulaire.php");
}
?>
</body>

</html>