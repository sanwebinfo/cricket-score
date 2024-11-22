<?php

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

<title>Get Match ID 🏏</title>
<meta name="description" content="Get Real-time Live Cricket Score Update without refreshing the page.">
<?php $current_page = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; echo '<link rel="canonical" href="'.$current_page.'" />'; ?>


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
            <h1 class="title is-size-5">Fetch ID from URL</h1>
            <hr>
            <div class="notification is-danger" id="error-notification" style="display: none;"></div>
            <div class="notification is-link" id="loading-notification" style="display: none;">Loading...</div>
            <form id="fetchForm">
                <div class="field">
                    <label class="label">Enter URL:</label>
                    <div class="control">
                        <input class="input is-rounded" type="text" id="urlInput" placeholder="Enter URL">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-warning is-rounded read-score" type="submit" id="fetchButton">Fetch ID</button>
                    </div>
                </div>
            </form>
            <div id="result" style="display: none;">
                <br>
                <div class="notification is-warning" id="success-notification"></div>
            </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>

    <script>
        document.getElementById('fetchForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const errorNotification = document.getElementById('error-notification');
            const loadingNotification = document.getElementById('loading-notification');
            const successNotification = document.getElementById('success-notification');
            const resultSection = document.getElementById('result');

            errorNotification.style.display = 'none';
            loadingNotification.style.display = 'block';
            successNotification.style.display = 'none';
            resultSection.style.display = 'none';

            if (!url) {
                errorNotification.innerText = 'Please enter a URL.';
                errorNotification.style.display = 'block';
                loadingNotification.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/api/id.php?url=${encodeURIComponent(url)}`);
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to fetch data.');
                }

                if (!data.success) {
                    throw new Error(data.message || 'An error occurred while fetching data.');
                }
                successNotification.innerHTML = `<span>${data.message}</span>`;
                successNotification.style.display = 'block';
                resultSection.style.display = 'block';
            } catch (error) {
                errorNotification.innerText = error.message || 'An error occurred.';
                errorNotification.style.display = 'block';
            } finally {
                loadingNotification.style.display = 'none';
            }
        });
    </script>

</body>
</html>