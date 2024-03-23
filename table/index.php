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

    <title>IPL 2024 Schedule - First 21 Days</title>
    <meta name="description" content="IPL 2024 Schedule - First 21 Days - Get Real-time Live Cricket Score Update without refreshing the page.">
    <?php $current_page = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; echo '<link rel="canonical" href="'.$current_page.'" />'; ?>


    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.0/css/bulma.min.css" integrity="sha512-+oEiKVGJRHutsibRYkkTIfjI0kspDtgJtkIlyPCNTCFCdhy+nSe25nvrCw7UpHPwNbdmNw9AkgGA+ptQxcjPug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/style.css">

</head>
<body>

    <section class="section">
        <div class="container">
            <div id="quote-card" class="card is-rounded">
                <div class="card-content">
                <div id="quote-container">
                <h1 class="title has-text-centered is-size-6">IPL 2024 Schedule - First 21 Days</h1>
                    <div class="content">
                        <div class="field">
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select id="filterSelect" onchange="filterTable()">
                                        <option value="">Filter by...</option>
                                        <option value="CSK">CSK</option>
                                        <option value="RCB">RCB</option>
                                        <option value="DC">DC</option>
                                        <option value="KKR">KKR</option>
                                        <option value="SRH">SRH</option>
                                        <option value="GT">GT</option>
                                        <option value="MI">MI</option>
                                        <option value="RR">RR</option>
                                        <option value="LSG">LSG</option>
                                        <option value="PBKS">PBKS</option>
                                    </select>
                                </div>
                                <br><br>
                                <button class="button is-warning is-rounded read-score" id="clearFilterButton" onclick="clearFilter()" disabled>Clear</button>
                            </div>
                        </div>
                        <br>
                        <div class="table-container table-wrapper">
                            <table class="table is-fullwidth is-hoverable" id="scheduleTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Match</th>
                                        <th>Time (IST)</th>
                                        <th>Venue</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr class="no-data-message-row" style="display: none;">
                                        <td colspan="4">No matches found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>

<script src="/table/table.js"></script>

</body>
</html>