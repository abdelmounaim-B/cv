<?php
session_start(); // Start the session

// Check if the user is authenticated, if not, redirect to login page
if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
  header("Location: main.php");
  exit();
}



function establish_connection()
{
  try {
    $con = new PDO("mysql:host=127.0.0.1;dbname=u476084088_prices", 'u476084088_prices', '8Cv*cVT4A95l');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    return $con;
  } catch (PDOException $e) {
    exit("Échec de la connexion : " . $e->getMessage());
  }
}





$tilalResults = [];
$pneuproResults = [];
$ouipneuResults = [];
$clicpneuResults = [];



if ($filterCheckbox) {
  $tilalResults = filter_results($tilalResults);
  $ouipneuResults = filter_results($ouipneuResults);
  $clicpneuResults = filter_results($clicpneuResults);
}


function get_pneupro_data($search)
{
  $con = establish_connection();
  $stmt = $con->prepare("SELECT * FROM products WHERE dimension LIKE ? AND source = 'Pneupro'");
  $stmt->execute(["%" . $search . "%"]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $con = null;

  return $results;
}

function get_ouipneu_data($search)
{
  $con = establish_connection();
  $stmt = $con->prepare("SELECT * FROM products WHERE dimension LIKE ? AND source = 'Ouipneus'");
  $stmt->execute(["%" . $search . "%"]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $con = null;

  return $results;
}

function get_clicpneu_data($search)
{
  $con = establish_connection();
  $stmt = $con->prepare("SELECT * FROM products WHERE dimension LIKE ? AND source = 'Ouipneus'");
  $stmt->execute(["%" . $search . "%"]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $con = null;

  return $results;
}


function get_tilal_data($search)
{
  $con = establish_connection();
  $stmt = $con->prepare("SELECT * FROM products WHERE REPLACE(dimension, ' ', '') LIKE ? AND source = 'Tilal'");
  $stmt->execute(["%" . $search . "%"]);
  // Check for errors
  $errorInfo = $stmt->errorInfo();
  if ($errorInfo[0] !== '00000') {
    echo "Error: " . $errorInfo[2];
    return [];
  }

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $con = null;
  return $results;
}


function print_tilal_table($results)
{
  echo "<h1>Produits Tilal</h1>";
  if (count($results) > 0) {
    echo "<div class='table-container'>";
    echo "<table class='product-table my-custom-table'>";
    echo "<tr><th>Dimension</th><th>Marque</th><th>Profil</th><th>Type</th><th>Quantité</th><th>Prix Tilal</th></tr>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . $row['dimension'] . "</td>";
      echo "<td><img src='Marques/" . strtoupper(str_replace(' ', '', $row['brand'])) . ".png' alt='" . strtoupper($row['brand']) . "' class='brand-image'></td>";
      echo "<td>" . $row['profile'] . "</td>";
      echo "<td>" . $row['type'] . "</td>"; // Display the type as it is
      echo "<td>" . ($row['quantite'] ? $row['quantite'] : '-') . "</td>"; // Display quantity
      echo "<td>" . ($row['price'] ? $row['price'] : '-') . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
  } else {
    echo "<div class='no-results'>Aucun résultat trouvé.</div>";
  }
}


function print_Clicpneu_table($results)
{
  echo "<h1>Produits Clicpneu</h1>";
  if (count($results) > 0) {
    echo "<div class='table-container'>";
    echo "<table class='product-table my-custom-table'>";
    echo "<tr><th>Dimension</th><th>Marque</th><th>Profil</th><th>Prix Clicpneu</th></tr>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . $row['dimension'] . "</td>";
      echo "<td><img src='Marques/" . strtoupper(str_replace(' ', '', $row['brand'])) . ".png' alt='" . strtoupper($row['brand']) . "' class='brand-image'></td>";
      echo "<td>" . $row['profile'] . "</td>";
      echo "<td>" . ($row['price'] ? $row['price'] : '-') . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
  } else {
    echo "<div class='no-results'>Aucun résultat trouvé.</div>";
  }
}



function print_pneupro_table($results)
{
  if (count($results) > 0) {
    echo "<div class='table-container'>";
    echo "<table class='product-table my-custom-table'>";
    echo "<h1>Produits Pneupro</h1>";
    echo "<tr><th>Dimension</th><th>Marque</th><th>Profil</th><th>Spécifiques</th><th>Prix Pneupro</th></tr>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . strtoupper($row['dimension']) . "</td>";
      echo "<td><img src='Marques/" . strtoupper($row['brand']) . ".png' alt='" . strtoupper($row['brand']) . "' class='brand-image'></td>";
      echo "<td>" . strtoupper($row['profile']) . "</td>";
      echo "<td>" . $row['specifics'] . "</td>";
      echo "<td>" . ($row['price'] ? $row['price'] : '-') . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
  } else {
    echo "<h1>Produits Pneupro</h1>";
    echo "<div class='no-results'>Aucun résultat trouvé.</div>";
  }
}

function print_ouipneu_table($results)
{
  if (count($results) > 0) {
    echo "<div class='table-container'>";
    echo "<table class='product-table my-custom-table'>";
    echo "<h1>Produits Ouipneu</h1>";
    echo "<tr><th>Dimension</th><th>Marque</th><th>Profil</th><th>Spécifiques</th><th>Prix Ouipneu</th></tr>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . strtoupper($row['dimension']) . "</td>";
      echo "<td><img src='Marques/" . strtoupper($row['brand']) . ".png' alt='" . strtoupper($row['brand']) . "' class='brand-image'></td>";
      echo "<td>" . strtoupper($row['profile']) . "</td>";
      echo "<td>" . $row['specifics'] . "</td>";
      echo "<td>" . ($row['price'] ? $row['price'] : '-') . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
  } else {
    echo "<h1>Produits Ouipneu</h1>";
    echo "<div class='no-results'>Aucun résultat trouvé.</div>";
  }
}

function filter_results($results)
{
  $filterWords = array("Runflat", "ROF", "DSST", "MOE", "RSI", "HRS", "ZP", "RFT", "SSR", "RUNFLAT");

  $filteredResults = array();

  foreach ($results as $row) {
    $specifics = strtoupper($row['specifics']);
    $type = strtoupper($row['type']);
    $match = false;

    foreach ($filterWords as $filterWord) {
      if (strpos($specifics, strtoupper($filterWord)) !== false || strpos($type, strtoupper($filterWord)) !== false) {
        $match = true;
        break;
      }
    }

    if ($match) {
      $filteredResults[] = $row;
    }
  }

  return $filteredResults;
}


/////////////////////

$search = isset($_POST['search']) ? $_POST['search'] : '';
$filterCheckbox = isset($_POST['filter_checkbox']);
$source = isset($_POST['source']) ? $_POST['source'] : null;

$search = preg_replace('/[^0-9]/', '', $search); // Remove non-digit characters
$search = substr_replace($search, '/', 3, 0); // Add "/" after the first 3 digits
$search_tilal = substr_replace($search, 'R', 6, 0);
$search = substr_replace($search, ' R', 6, 0); // Add " R" after the next 2 digits  
$search = strtoupper($search); // Convert input to uppercase
$search_tilal = strtoupper($search_tilal); // Convert input to uppercase


$tilalResults = get_tilal_data($search_tilal);
$ouipneuResults = get_ouipneu_data($search);
$pneuproResults = get_pneupro_data($search);
$clicpneuResults = get_clicpneu_data($search);

if ($filterCheckbox) {
  $tilalResults = filter_results($tilalResults);
  $ouipneuResults = filter_results($ouipneuResults);
  $pneuproResults = filter_results($pneuproResults);
  $clicpneuResults = filter_results($clicpneuResults);
}


///////////////////////

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Recherche-prix</title>
  <link rel="icon" type="image/x-icon" href="marques/logo.png">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="navbar">
    <img src="Marques/pneupro.png" alt="Logo">
    <a href="logout.php">
      <button class="logout_button">
        Déconnexion
        <div class="arrow-wrapper">
          <div class="arrow"></div>
        </div>
      </button>
    </a>
  </div>

  <div class="container">
    <h1>Recherche de Produits</h1>
    <form method="POST" action="search.php">
      <input type="text" placeholder="Entrez une dimension: Ex: 2055516" name="search" class="search_bar" id="search_input" list="tire_sizes">
      <div class="source-options">
        <label>
          <input type="radio" name="source" value="Tilal"> Tilal
        </label>
        <label>
          <input type="radio" name="source" value="pneupro"> Pneupro
        </label>
        <label>
          <input type="radio" name="source" value="ouipneu"> Ouipneu
        </label>
        <label>
          <input type="radio" name="source" value="ouipneu"> Clicpneu
        </label>
      </div>
      <datalist id="tire_sizes"></datalist> <input class="search_button" type="submit" style="margin-left: 40px;" value="Rechercher">
      <input type="checkbox" style="margin-left: 40px;" name="filter_checkbox" value="1">SEULEMENT RUNFLAT
    </form>
    <?php
    if ($source === null || $source === 'Tilal') {
      print_tilal_table($tilalResults);
    }

    if ($source === null || $source === 'pneupro') {
      print_pneupro_table($pneuproResults);
    }

    if ($source === null || $source === 'ouipneu') {
      print_ouipneu_table($ouipneuResults);
    }
    if ($source === null || $source === 'clicpneu') {
      print_ouipneu_table($clicpneuResults);
    }

    ?>

  </div>

  <script>
    var tireSizes = ['2254517', '2556517', '2656018', '2755517', '2157015', '2756517', '2353520', '1756515', '2454021', '1856014', '2456517', '1855515', '2754520', '2354519', '2755020', '2655020', '2553020', '2954021', '2355019', '2154517', '2156017', '2854519', '2855020', '2257515', '2754018', '1957516', '2254518', '2151411', '2355020', '2055016', '1956516', '1955520', '2457016', '3052521', '2556017', '3253021', '2257017', '2555518', '2754521', '2454019', '1956515', '2954520', '2953020', '2554018', '3353020', '1458013', '2054017', '2257016', '2253519', '1656014', '2853521', '1558013', '2755519', '1657014', '2156517', '2954020', '2255517', '2553019', '2255018', '2356018', '2555520', '2853019', '2253520', '1855514', '2756020', '3153021', '2557018', '2754519', '2657016', '2256018', '2355018', '2657015', '2254019', '1757014', '2156515', '2157514', '1656513', '3054020', '2054517', '2055516', '2358516', '2057514', '2256517', '2554518', '2553521', '1557013', '2056515', '2856018', '1955516', '2554020', '2654022', '2257514', '2255519', '1656514', '2753020', '2157515', '2156016', '2154017', '2653518', '2953521', '2553518', '2556020', '2654020', '2653519', '2654521', '2355517', '2255017', '2354019', '2357017', '2255516', '2453520', '2853021', '3153521', '2753522', '2155018', '2055017', '2156516', '2055515', '2854020', '1958015', '2354518', '2453519', '2755019', '1954516', '2354517', '2154018', '1851410', '2354520', '1955515', '1556514', '2355519', '1657013', '2555517', '2753520', '2357016', '2853520', '2953520', '1957015', '2257015', '2757016', '1856514', '2555019', '2455019', '2555020', '2753519', '2051611', '2653521', '2653520', '2454517', '2254519', '2056016', '2356516', '1955016', '2554019', '2556018', '2754021', '1955015', '2454521', '2554021', '2853522', '1757013', '1856015', '2653020', '2155017', '2255016', '2454017', '2056516', '2355017', '2853020', '2455020', '2553520', '2356517', '2056015', '2255518', '2853519', '1856515', '1857014', '2054516', '2654018', '2756518', '2657017', '3153520', '2854521', '2953019', '2455517', '2256017', '2254018', '2257516', '1551288', '1551390', '2454520', '2854022', '2256016', '2454518', '2054018', '2753019', '3154021', '1457013', '2353519', '1956015', '2554520', '2753521', '2655019', '2753518', '2854520', '2754019', '2856517', '2453019', '1951510', '2553519', '2454018', '2157516', '2954519', '2058016', '2953021', '2154518', '2055517', '2157016', '2653019', '2356017', '2155516', '2656517', '2554519', '2556516', '2057015', '2051410', '2654019', '2453518', '2253518', '2557016', '2055519', '2155517', '2754022', '3053020', '1757516', '2354018', '2155518', '2356016', '2456018', '2154516', '2657516', '2854021', '2356519', '2357515', '2954022', '1951410', '2356518', '2754020', '2854019', '2654520', '1756514', '2454020', '2455018', '2654021', '3153022', '1956016', '2355518', '2457017', '1857516', '2454519', '2853518', '2256516', '2555519', '2057516']
  </script>

  <script>
    var searchInput = document.getElementById('search_input');
    var datalist = document.getElementById('tire_sizes');

    searchInput.addEventListener('input', function() {
      var input = searchInput.value.toLowerCase();
      var suggestions = [];

      // Filter tire sizes that match the user input
      for (var i = 0; i < tireSizes.length; i++) {
        if (tireSizes[i].includes(input)) {
          suggestions.push(tireSizes[i]);
        }
      }

      // Clear the datalist
      datalist.innerHTML = '';

      // Add matching suggestions to the datalist
      for (var j = 0; j < suggestions.length; j++) {
        var option = document.createElement('option');
        option.value = suggestions[j];
        datalist.appendChild(option);
      }
    });
  </script>

</body>
<div class="logo-container">
  <!-- Replace "logo.png" with the path to your logo image -->
  <img src="Marques/pneupro.png" alt="Logo">
</div>

<div class="links">
  <a href="https://pneupro.ma/">
    <button class="buttons type1">
    </button>
  </a>
  <a href="http://tilalpneus.com/tilal3/rechref.php">
    <button class="buttons type2">
    </button>
  </a>
  <a href="https://ouipneus.ma/">
    <button class="buttons type3">
    </button>
  </a>

</div>

</html>