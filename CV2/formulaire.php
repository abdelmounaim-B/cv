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

include 'header.php';
?>


  <h1>formulaire de CV</h1>

  <form method="POST" action="recap.php" enctype="multipart/form-data" class="form">
    <!-- personal info -->

    <h2>Renseignement Personel</h2>
    <p>
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required value="<?php setValueFromCookie("name") ?>">
    </p>
    <p>
      <label for="firstname">First name:</label>
      <input type="text" name="firstname" id="firstname" required value="<?php setValueFromCookie("firstname") ?>">

    </p>
    <p>
      <label for="age">Age:</label>
      <input type="number" name="age" id="age" required value="<?php setValueFromCookie("age") ?>">
    </p>
    <p>
      <label for="phone">Phone number:</label>
      <input type="tel" name="phone" id="phone" required value="<?php setValueFromCookie("phone") ?>">
    </p>
    <p>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required value="<?php setValueFromCookie("email") ?>">
    </p>
    <!-- academic info -->

    <h2>Renseignement Academique</h2>
    <p>
      <label>You are in:</label>
      <input type="radio" name="year" value="1st" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '1st') ? 'checked' : ''; ?>> 1st year
      <input type="radio" name="year" value="2nd" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '2nd') ? 'checked' : ''; ?>> 2nd year
      <input type="radio" name="year" value="3rd" <?php echo (isset($_COOKIE['year']) && $_COOKIE['year'] == '3rd') ? 'checked' : ''; ?>> 3rd year
    </p>
    <p>
      <label>Modules followed this year:</label>
      <input type="checkbox" name="modules[]" value="Pro Av" <?php echo isModuleChecked('Pro Av'); ?>> Pro Av
      <input type="checkbox" name="modules[]" value="Comp" <?php echo isModuleChecked('Comp'); ?>> Compilation
      <input type="checkbox" name="modules[]" value="Networks" <?php echo isModuleChecked('Networks'); ?>> Networks
    </p>
    <p>
      <label for="projects">Number of projects done this year:</label>
      <input type="number" name="projects" min="0" id="projects" value="<?php setValueFromCookie("projects") ?>">
    </p>
    <!-- remarques and photo -->
    <h2>profile and remarques</h2>

    <p>
      <label for="remarks">Your remarks:</label>
      <textarea name="remarks" id="remarks"> <?php setValueFromCookie("remarks") ?></textarea>
    </p>
    <p>
      <label for="fileUpload">Choose a file to upload:</label>
      <input type="file" name="fileUpload" id="fileUpload" required>
    </p>
    <h2>intrests / projects / Languages</h2>
    <p>
      <label for="interests">Interests:</label>
      <input type="text" name="interests" id="interests" value="<?php setValueFromCookie("interests") ?>">

    <p>
      <label for="languages">Languages spoken:</label>
      <input type="text" name="languages" id="languages" value="<?php setValueFromCookie("languages") ?>">
    </p>

    <p>
      <label for="projects">les projets</label>
      <input type="text" name="projects_list" id="projects" value="<?php setValueFromCookie("projects_list") ?>">
    </p>


    <input type="submit" value="Send">
    <input type="reset" value="Clear">
  </form>

</body>

</html>