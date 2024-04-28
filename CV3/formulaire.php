<?php
function setValueFromCookie($cookieName)
{
  if (isset($_COOKIE[$cookieName])) {
    echo $_COOKIE[$cookieName];
  }
}
//get modules from session
function isModuleChecked($module)
{
  if (isset($_COOKIE['modules'])) {
    $modules = explode(", ", $_COOKIE['modules']);
    if (in_array($module, $modules)) {
      return 'checked';
    }
  }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>formulaire de CV</title>
</head>

<body>
  <h1>CV Form</h1>

  <form class="form" method="POST" action="recap.php" enctype="multipart/form-data">
    <!-- Personal Info -->
    <h2>Personal Information</h2>
    <p>
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required placeholder="Enter your name" value="<?php echo isset($_COOKIE['name']) ? htmlspecialchars($_COOKIE['name']) : ''; ?>">
    </p>
    <p>
      <label for="firstname">First name:</label>
      <input type="text" name="firstname" id="firstname" required placeholder="Enter your first name" value="<?php echo isset($_COOKIE['firstname']) ? htmlspecialchars($_COOKIE['firstname']) : ''; ?>">
    </p>
    <p>
      <label for="age">Age:</label>
      <input type="number" name="age" id="age" required placeholder="Enter your age" value="<?php echo isset($_COOKIE['age']) ? htmlspecialchars($_COOKIE['age']) : ''; ?>">
    </p>
    <p>
      <label for="phone">Phone number:</label>
      <input type="tel" name="phone" id="phone" required placeholder="Enter your phone number" value="<?php echo isset($_COOKIE['phone']) ? htmlspecialchars($_COOKIE['phone']) : ''; ?>">
    </p>
    <p>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required placeholder="Enter your email" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">
    </p>
    <!-- Academic Info -->
    <h2>Academic Information</h2>
    <p>
      <label>You are in:</label>
      <input type="radio" name="year" value="1st" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '1st') ? 'checked' : ''; ?>> 1st year
      <input type="radio" name="year" value="2nd" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '2nd') ? 'checked' : ''; ?>> 2nd year
      <input type="radio" name="year" value="3rd" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '3rd') ? 'checked' : ''; ?>> 3rd year
      <input type="radio" name="year" value="4th" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '4th') ? 'checked' : ''; ?>> 4th year
      <input type="radio" name="year" value="5th" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '5th') ? 'checked' : ''; ?>> 5th year
    </p>
    <p>
      <label>Modules followed this year:</label>
      <!-- Update the isModuleChecked function call as per your implementation -->
      <input type="checkbox" name="modules[]" value="Advanced Programming" <?php echo isModuleChecked('Advanced Programming'); ?>> Advanced Programming
      <input type="checkbox" name="modules[]" value="Compilation" <?php echo isModuleChecked('Compilation'); ?>> Compilation
      <input type="checkbox" name="modules[]" value="Networks" <?php echo isModuleChecked('Networks'); ?>> Networks
      <input type="checkbox" name="modules[]" value="Web Development" <?php echo isModuleChecked('Web Development'); ?>> Web Development
    </p>
    <p>
      <label for="projects_count">Number of projects done this year:</label>
      <input type="number" name="projects" min="0" id="projects_count" placeholder="Enter number of projects" value="<?php echo isset($_COOKIE['projects']) ? htmlspecialchars($_COOKIE['projects']) : ''; ?>">
    </p>
    <!-- Profile and Remarks -->
    <h2>Profile and Remarks</h2>
    <p>
      <label for="remarks">Your remarks:</label>
      <textarea name="remarks" id="remarks" placeholder="Enter your remarks"><?php echo isset($_COOKIE['remarks']) ? htmlspecialchars($_COOKIE['remarks']) : ''; ?></textarea>
    </p>
    <p>
      <label for="fileUpload">Choose a file to upload:</label>
      <input type="file" name="fileUpload" id="fileUpload" required>
    </p>
    <!-- Interests / Projects / Languages -->
    <h2>Interests / Projects / Languages</h2>
    <p>
      <label for="interests">Interests:</label>
      <input type="text" name="interests" id="interests" placeholder="Enter your interests" value="<?php echo isset($_COOKIE['interests']) ? htmlspecialchars($_COOKIE['interests']) : ''; ?>">
    </p>
    <p>
      <label for="languages">Languages spoken:</label>
      <input type="text" name="languages" id="languages" placeholder="Enter languages you speak" value="<?php echo isset($_COOKIE['languages']) ? htmlspecialchars($_COOKIE['languages']) : ''; ?>">
    </p>
    <p>
      <label for="Stages_formation">Education and Internships</label>
      <input type="text" name="Stages_formation" id="Stages_formation" placeholder="List your projects" value="<?php echo isset($_COOKIE['Stages_formation']) ? htmlspecialchars($_COOKIE['Stages_formation']) : ''; ?>">
    </p>

    <input type="submit" value="Send">
    <input type="submit" name="clear" value="Clear">
  </form>
</body>


</html>