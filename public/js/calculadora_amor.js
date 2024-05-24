document.getElementById('calculateButton').addEventListener('click', function() {
    const maleName = document.getElementById('maleName').value;
    const femaleName = document.getElementById('femaleName').value;
    const resultElement = document.getElementById('result');
    const API_URL = `https://love-calculator.p.rapidapi.com/getPercentage?sname=${femaleName}&fname=${maleName}`;
    const headers = {
        'X-RapidAPI-Key': 'c23202eb73mshb2f37ea5bdb3ed2p1e0ad5jsn39c388869d71',
        'X-RapidAPI-Host': 'love-calculator.p.rapidapi.com'
    };

    resultElement.textContent = 'Calculating...';

    fetch(API_URL, {
            method: 'GET',
            headers: headers
        })
        .then(response => response.json())
        .then(data => {
            resultElement.textContent = ` ${data.percentage}%`;
        })
        .catch(error => {
            console.error('Error:', error);
            resultElement.textContent = 'Error calculating love percentage';
        });
});