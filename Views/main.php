<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        #results ul {
            list-style: none;
            padding-left: 0
        }

        #results li {
            display: flex;
            padding: 6px;
            align-items: center;
        }

        #results .color {
            border-radius: 50%;
            border: solid 1px black;
            width: 12px;
            height: 12px;
        }

        #results .name {
            margin-left: 6px;
        }
    </style>
</head>
<body>
<form id="form">
    город:
    <select id="citySelect">
        <option value="">не выбран</option>
    </select>
    станция:
    <input id="stationInput" autocomplete="off">
    <span id="status">
   </span>
    <div id="results">
    </div>
</form>

<script type="text/javascript">
    let cityId = '';
    let searchInput = '';

    document.addEventListener('DOMContentLoaded', function () {
        fetchCities();
    });

    onCitySelectChange = function (el) {
        displayStatus(''); //TODO: не нужна строка, так как в doSearchRequest статус сразу же обновляется
        displayResults([]);
        cityId = el.target.value;
        doSearchRequest();
    };
    onStationInputChange = function (el) {
        displayStatus(''); //TODO: не нужна строка, так как в doSearchRequest статус сразу же обновляется
        displayResults([]);
        searchInput = el.target.value;
        setRequestHoldDown();
    };
    doSearchRequest = function () {
        const requestCityId = cityId;
        const requestSearchInput = searchInput;

        if (!checkRequest()) return false;

        displayStatus('Выполняется поиск...');

        fetch("/findStations?city=" + cityId + "&term=" + searchInput)
            .then((response) => {
                if (requestCityId === cityId && requestSearchInput === searchInput) {
                    // if (response.status !== 'ok') { //TODO: для проверки успешности есть св-во response.ok
                    if (!response.ok) {
                        let error = new Error(response.statusText);
                        error.response = response;
                        throw error
                        // displayStatus(response.error || 'Неизвестная ошибка сервера')
                    } else {
                        // displayStatus('Найдено: ' + response.data.results.length);
                        // displayResults(response.data.results);
                        return response.json();
                    }
                }
            })
            .then(response => {
                console.log(response);
                displayStatus('Найдено: ' + response.counter + ' (показано ' + response.data.length +')');
                displayResults(response.data);
            })
            .catch((error) => {
                displayStatus(error.message);
            });
    };
    displayStatus = function (message) {
        document.getElementById('status').innerHTML = message;
    };
    displayResults = function (results) {
        for (let i in results){
            let regexp = new RegExp('(' + searchInput + ')', 'i');
            results[i].name = results[i].name.replace(regexp, '<strong>$1</strong>');
        }
        document.getElementById('results').innerHTML = '<ul>' + results.map((item) => {
            return '<li><div class="color"></div><div class="name">' + item.name + '</div></li>'
        }).join('') + '</ul>';
    };

    function fetchCities() {
        fetch("/getCities")
            .then(response => {
                return response.json();
            })
            .then(cities => {
                cities.forEach(element => {
                    let option = document.createElement('option');
                    option.value = element.id;
                    option.text = element.name;
                    citySelect.add(option);
                })
            })
    }

    function checkRequest() {
        let matches = searchInput.match(/[а-яА-я]+/);

        if (searchInput.length < 2) {
            displayStatus('Минимум 2 символа');
            return false
        } else if (!matches) {
            displayStatus('Только кириллица');
            return false
        }

        return true
    }

    function setRequestHoldDown() {
        let doneTypingInterval = 250;
        let myInput = document.getElementById('stationInput');

        myInput.addEventListener('keyup', () => {
            clearTimeout(window.typingTimer);
            if (myInput.value) {
                window.typingTimer = setTimeout(doneTyping, doneTypingInterval);
            }
        });

        function doneTyping() {
            doSearchRequest();
        }
    }

    document.getElementById('form').onsubmit = (e) => {  //TODO: зачем нужен этот блок? мы не отправляем форму
        e.preventDefault()
    };
    document.getElementById('citySelect').addEventListener('change', onCitySelectChange);
    document.getElementById('stationInput').addEventListener('input', onStationInputChange);

</script>
</body>

</html>