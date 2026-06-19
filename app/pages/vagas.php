<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>

    <link rel="icon" href="<?=ROOT?>/assets/imgs/MaxaP.ico" type="image/x-icon">
    <link href="<?=ROOT?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=ROOT?>/assets/css/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?=ROOT?>/assets/css/maxa.css" rel="stylesheet">

    <style>
        body { background: #11111a; }
        #placa {
            border-radius: 18px;
            margin-top: 24px;
            padding: 1.4rem;
        }
        #placa h1 { font-weight: 700; letter-spacing: 2px; margin: 0; }
        .painel-titulo { color: #fff; }
        #vagas-container {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: center;
            margin-top: 8px;
        }
        .painel-vaga {
            flex: 0 0 130px;
            border-left: 3px dashed #ffd23f;
            border-right: 3px dashed #ffd23f;
        }
        .painel-vaga .nome { font-size: 1.9rem; font-weight: 700; margin: 2px 0; }
        .painel-vaga .codigo, .painel-vaga .sector { font-size: .8rem; opacity: .85; margin: 0; }
        .painel-vazio { color: #bbb; padding: 2rem; }
    </style>
</head>
<body>
    <div class="container pb-5">
        <div class="text-center pt-4">
            <img src="<?=ROOT?>/assets/imgs/Maxa250W.png" width="70" alt="MaxaPark">
        </div>
        <div class="row justify-content-center align-items-center g-2">
            <div id="placa" class="painel-status-aberto col-12 d-flex flex-column justify-content-center align-items-center text-center">
                <h1 id="horario" class="text-white">ABERTO</h1>
                <div class="text-white-50 mt-1"><i class="bi bi-p-square-fill"></i> Lugares disponíveis em tempo real</div>
            </div>
            <h4 class="painel-titulo text-center mt-3">Vagas Disponíveis</h4>
            <main id="vagas-container"></main>
        </div>
    </div>
       <script>
    function updateVagas() {
        // Use an AJAX request to fetch updated data from the server
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Update the content of the "vagas-container" with the new data
                document.getElementById("vagas-container").innerHTML = xhr.responseText;
            }
        };
        xhr.open("GET", "updateVagas", true);
        xhr.send();
    }

    // Update the data every 10 seconds (10000 milliseconds)
    setInterval(updateVagas, 10000);

    // Call the function immediately to display data on page load
    updateVagas();
</script>
<script>
        function checkTimeAndChangeText() {
            const currentTime = new Date();
            const currentHour = currentTime.getHours(); // Get the current hour (0-23)
            var divElement = document.getElementById("placa");
    
            const statusElement = document.getElementById("horario");
            console.log(currentHour);

            var base = "col-12 d-flex flex-column justify-content-center align-items-center text-center ";
            if (currentHour < <?= park_abertura() ?> || currentHour >= <?= park_fecho() ?>) {
                statusElement.textContent = "FECHADO";
                divElement.className = base + "painel-status-fechado";
            } else {
                statusElement.textContent = "ABERTO";
                divElement.className = base + "painel-status-aberto";
            }
        }

        // Call the function to check the time and change the text and color
        checkTimeAndChangeText();
    </script>
</body>
</html>