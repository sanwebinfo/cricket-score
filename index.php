<?php

require_once('functions.php');

$current_page = htmlspecialchars("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", ENT_QUOTES, 'UTF-8');

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="True" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#c7ecee">
<link rel="shortcut icon" type="image/x-icon" href="/media/favicon.ico" />
<link rel="icon" type="image/png" sizes="196x196" href="/media/192.png" />
<link rel="apple-touch-icon" href="/media/180.png" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="application-name" content="Cricket" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-title" content="Cricket" />

<title>Live Cricket Score 🏏</title>
<meta name="description" content="Get Real-time Live Cricket Score Update without refreshing the page.">
<?php $current_page = htmlspecialchars("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", ENT_QUOTES, 'UTF-8');
        echo '<link rel="canonical" href="' . $current_page . '" />';
?>


<meta property="og:site_name" content="Live Cricket Score 🏏">
<meta property="og:type" content="website">
<meta property="og:title" content="Live Cricket Score 🏏">
<meta property="og:description" content="Get Real-time Live Cricket Score Update without refreshing the page.">
<meta property="og:url" content="<?php echo $current_page; ?>">
<meta property="og:image" content="<?php echo getFullUrl() . '/media/random-score.jpg'; ?>">
<meta property="og:image:alt" content="Live Cricket Score 🏏" />
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

<meta name="twitter:title" content="Live Cricket Score 🏏">
<meta name="twitter:description" content="Get Real-time Live Cricket Score Update without refreshing the page.">
<meta name="twitter:url" content="<?php echo $current_page; ?>">
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="<?php echo getFullUrl() . '/media/random-score.jpg'; ?>">

<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css" integrity="sha512-HqxHUkJM0SYcbvxUw5P60SzdOTy/QVwA1JJrvaXJv4q7lmbDZCmZaqz01UPOaQveoxfYRv1tHozWGPMcuTBuvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="style.css">

</head>
<body>

  <section class="section">
    <div class="container content">
      <div class="columns is-centered">
        <div class="column is-half">
          <div id="quote-card" class="card is-rounded">
            <div class="card-content">
            <div id="quote-container">
              <h1 class="title has-text-centered is-size-6">Live Cricket Score 🏏</h1>
              <div class="content">
                <div class="has-text-centered">
                  <button id="refreshButton" class="button is-danger is-rounded read-score has-text-white">Refresh Score</button>
                </div>
                <br>
                <div class="loading-spinner" style="display: none;">
                  <progress class="progress is-small is-primary" max="100">Loading</progress>
                </div>
                <div id="score"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>

  <script src="script.js"></script>
  
</body>
</html>