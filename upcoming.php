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
<link rel="shortcut icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABJklEQVQ4jc2TvUoDQRSFvxk2+5PgKskmuGCxWyS+gIgICnkBg2DpO9kKCrYW1nZiLQoGRARBi6ASEheLkLhqhrEIBHaXEEkapxpmmO+ec+8ZsXF2qpljGQAIMdtrrZHzVAfmBxgTyQJWCjYAg6HiPf75G0AI8B2L/eoy6xUXUwpe+l8cPbxxG/WyhdIHa57L4dYqjcDDz5sULQMvVhxsVtn2l6YDdsMyJTsHwHe/x/1VE993yUnBXljBNZOiMxaChZHv3vUdz6831OoNpByN2ZIaSyZHnlFw3ooAGDiKWn0HZ7E4vmu2P+immplQIABbwGWrQ+gHlPIFlFIM4piLVpeTpyjTg4yF48f2GFY2NCYQKcHnhMAnADq17wynR/y/RFnP/qN/ASq1TUWfNqPXAAAAAElFTkSuQmCC" />

<title>Upcoming Live Cricket Matches 🏏</title>
<meta name="description" content="Get Real-time Live Cricket Score Update without refreshing the page.">
<?php $current_page = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; echo '<link rel="canonical" href="'.$current_page.'" />'; ?>


<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css" integrity="sha512-HqxHUkJM0SYcbvxUw5P60SzdOTy/QVwA1JJrvaXJv4q7lmbDZCmZaqz01UPOaQveoxfYRv1tHozWGPMcuTBuvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="style.css">

</head>
<body>

    <section class="section">
        <div class="container">
            <h1 class="title is-size-5">Upcoming Matches</h1>
            <div id="matches-table" class="table-container table-wrapper"></div>
        </div>
    </section>

<script>
const baseURL = '/api/data.php';

async function fetchMatches() {
    try {
        const response = await fetch(`${baseURL}`);
        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
        return [];
    }
}

function displayMatches(matches) {
    const tableContainer = document.getElementById('matches-table');
    tableContainer.innerHTML = '';
    const table = document.createElement('table');
    table.classList.add('table', 'is-fullwidth');
    table.classList.add('table', 'is-hoverable');

    table.innerHTML = `
        <thead>
            <tr>
                <th>ID</th>
                <th>Match Name</th>
                <th>Get Score</th>
            </tr>
        </thead>
    `;

    const tbody = document.createElement('tbody');
    matches.forEach(match => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${match.id}</td>
            <td>${match.title}</td>
            <td><a href=/?id=${match.id}>View Score</a></td>
        `;
        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    tableContainer.appendChild(table);
}

async function fetchAndDisplayMatches() {
    const matches = await fetchMatches();
    displayMatches(matches);
}

fetchAndDisplayMatches();

</script>

</body>
</html>