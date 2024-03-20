const refreshButton = document.getElementById('refreshButton');
const loadingSpinner = document.querySelector('.loading-spinner');
const scoreElement = document.getElementById('score');

async function fetchCricketScore(id) {
  try {
    if (!isValidNumericId(id)) {
      throw new Error('Invalid ID format. Please provide a numeric ID.');
    }

    loadingSpinner.style.display = 'block';
    refreshButton.disabled = true;

    const response = await fetch(`score.php?id=${id}`);
    if (!response.ok) {
      throw new Error('Failed to fetch data');
    }
    const data = await response.json();
    if (!data || !data.title) {
      throw new Error('Invalid data format');
    }
    loadingSpinner.style.display = 'none';
    renderScore(data);

    refreshButton.disabled = false;
  } catch (error) {
    console.error('Error fetching cricket score:', error.message);
    loadingSpinner.style.display = 'none';
    scoreElement.innerHTML = `<div class="notification is-danger">${error.message}</div>`;
    refreshButton.disabled = true;
  }
}

function renderScore(score) {
  const kolkataTime = getCurrentTimeInKolkata();
  if (score.livescore == 'Data Not Found') {
    const scoreCardHTML = `
    <hr>
    <p>${score.title}</p>
    <p>🔴 ${score.update}</p>
    <p>🕐 Updated at ${kolkataTime}</p>
    <hr>
   `;
   scoreElement.innerHTML = scoreCardHTML;
  } else {
    const scoreCardHTML = `
      <hr>
      <p>${score.title}</p>
      <p>🔴 ${score.livescore}</p>
      <p>📊 ${score.runrate}</p>
      <p>✊ ${score.batterone || '-'}: ${score.batsmanonerun || '-'}${score.batsmanoneball || '-'}</p>
      <p>✊ ${score.bowlerone || '-'}: Overs: ${score.bowleroneover || '-'} - Runs: ${score.bowleronerun || '-'} - Wickets: ${score.bowleronewickers || '-'}</p>
      <p>📑 ${score.update}</p>
      <p>🕐 Updated at ${kolkataTime}</p>
      <hr>
    `;
    scoreElement.innerHTML = scoreCardHTML;
  }
}

refreshButton.addEventListener('click', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('id');
  fetchCricketScore(id);
});

const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');
fetchCricketScore(id);

function isValidNumericId(id) {
  return /^\d+$/.test(id);
}

function getCurrentTimeInKolkata() {
    const currentDate = new Date();
    const options = { timeZone: "Asia/Kolkata" };
    const currentTime = currentDate.toLocaleTimeString("en-US", options);

    return currentTime;
}