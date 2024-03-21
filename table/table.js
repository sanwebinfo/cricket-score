document.addEventListener('DOMContentLoaded', function () {
    fetch('/table/ipl.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.IPL_2024_schedule && data.IPL_2024_schedule.length > 0) {
                populateTable(data.IPL_2024_schedule);
                initializeFilter(data.IPL_2024_schedule);
            } else {
                handleNoData();
            }
        })
        .catch(error => {
            console.error('Error fetching IPL schedule:', error);
            handleNoData();
        });
});

function populateTable(matches) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    matches.forEach(function (match) {
        const row = tableBody.insertRow();
        const dateCell = row.insertCell(0);
        const matchCell = row.insertCell(1);
        const timeCell = row.insertCell(2);
        const venueCell = row.insertCell(3);
        dateCell.textContent = match.date || '';
        matchCell.textContent = match.match || '';
        timeCell.textContent = match.time_IST || '';
        venueCell.textContent = match.venue || '';
    });
}

function initializeFilter(matches) {
    const select = document.getElementById('filterSelect');
    const uniqueTeams = new Set();

    matches.forEach(function (match) {
        const teams = match.match.split(' VS ');
        teams.forEach(team => uniqueTeams.add(team.trim().toUpperCase()));
    });

    uniqueTeams.forEach(function (team) {
        const option = document.createElement('option');
        option.textContent = team;
        select.appendChild(option);
    });

    select.addEventListener('change', filterTable);
}

function handleNoData() {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '<tr><td colspan="4">No matches found.</td></tr>';
}

function filterTable() {
    const select = document.getElementById('filterSelect');
    const filterTeam = select.value.trim().toUpperCase();
    const tableBody = document.getElementById('tableBody');
    const rows = tableBody.getElementsByTagName('tr');
    let matchFound = false;

    for (let i = 0; i < rows.length; i++) {
        const matchCell = rows[i].cells[1];
        if (!matchCell) continue;
        //const teams = matchCell.textContent.trim().toUpperCase().split(' vs ');
        const teams = matchCell.textContent.trim().toUpperCase();
        const rowFound = teams.includes(filterTeam);
        rows[i].style.display = rowFound ? '' : 'none';
        if (rowFound) {
            matchFound = true;
        }
    }

    const clearFilterButton = document.getElementById('clearFilterButton');
    clearFilterButton.disabled = !matchFound;

    let noDataMessageRow = tableBody.querySelector('.no-data-message-row');
    if (!matchFound) {
        if (!noDataMessageRow) {
            noDataMessageRow = document.createElement('tr');
            noDataMessageRow.className = 'no-data-message-row';
            const noDataMessageCell = document.createElement('td');
            noDataMessageCell.setAttribute('colspan', '4');
            noDataMessageCell.textContent = 'No matches found.';
            noDataMessageRow.appendChild(noDataMessageCell);
            tableBody.appendChild(noDataMessageRow);
        }
        noDataMessageRow.style.display = '';
    } else {
        if (noDataMessageRow) {
            noDataMessageRow.style.display = 'none';
        }
    }
}
function clearFilter() {
    const select = document.getElementById('filterSelect');
    select.value = '';

    const tableBody = document.getElementById('tableBody');
    const rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        rows[i].style.display = '';
    }

    const noDataMessageRow = tableBody.querySelector('.no-data-message-row');
    if (noDataMessageRow) {
        noDataMessageRow.style.display = 'none';
    }

    const clearFilterButton = document.getElementById('clearFilterButton');
    clearFilterButton.disabled = true;
}