<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!-- record.html -->
    <video id="video" width="320" height="240" autoplay></video>
    <button id="snap">Capture</button>
    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>

    <script>
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => document.getElementById('video').srcObject = stream);

        document.getElementById('snap').onclick = () => {
            const canvas = document.getElementById('canvas');
            canvas.getContext('2d').drawImage(video, 0, 0, 320, 240);
            const dataURL = canvas.toDataURL('image/png');

            fetch('verify.php', {
                method: 'POST',
                body: JSON.stringify({
                    image: dataURL
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(res => res.text()).then(alert);
        };
    </script>

</body>

</html>