<!DOCTYPE html>
<html>

<head>
    <title>Upload or Take Photo for Object Detection</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }

    .form-container {
        width: 100vw;
        height: 100vh;
        background-color: #7b2cbf;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .upload-files-container {
        background-color: #f7fff7;
        width: 500px;
        padding: 20px 30px;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 10px 10px, rgba(0, 0, 0, 0.28) 0px 6px 6px;
    }

    .drag-file-area {
        border: 2px dashed #7b2cbf;
        border-radius: 30px;
        margin: 0 0 10px;
        padding: 30px 10px;
        width: 350px;
        text-align: center;
    }

    .drag-file-area .upload-icon,
    .camera-icon {
        font-size: 50px;
        cursor: pointer;
    }

    .drag-file-area h3 {
        font-size: 22px;
        margin: 10px 5px;
    }

    .drag-file-area label {
        font-size: 17px;
    }

    .drag-file-area label .browse-files-text {
        color: #7b2cbf;
        font-weight: bolder;
        cursor: pointer;
    }

    .browse-files span {
        position: relative;
        top: -30px;
    }

    .default-file-input {
        opacity: 0;
        position: absolute;
        left: -9999px;
    }

    .camera-preview {
        border: 2px solid #7b2cbf;
        border-radius: 10px;
        width: 350px;
        height: auto;
        margin: 10px 0;
    }

    .upload-button {
        font-family: 'Montserrat';
        background-color: #7b2cbf;
        color: #f7fff7;
        display: flex;
        align-items: center;
        font-size: 18px;
        border: none;
        border-radius: 20px;
        margin: 10px;
        padding: 7.5px 50px;
        cursor: pointer;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .loading-overlay .spinner {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #7b2cbf;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="upload-files-container">
            <form id="upload-form" action="/upload" method="post" enctype="multipart/form-data">
                @csrf
                <div class="drag-file-area" style="margin-top: -20px;">
                    <div class="upload-icon">📁</div>
                    <h3>Drag & Drop to Upload File</h3>
                    <label for="image">
                        <span class="browse-files-text">or browse files</span>
                    </label>
                    <input type="file" name="image" id="image" class="default-file-input">
                </div>
                <div class="drag-file-area" onclick="openCamera()">
                    <div class="camera-icon">📷</div>
                    <h3>Take a Photo</h3>
                </div>
                <video id="camera-preview" class="camera-preview" autoplay></video>
                <button type="button" onclick="capturePhoto()" class="upload-button">Capture Photo</button>
                <canvas id="canvas" style="display: none;"></canvas>
                <input type="hidden" name="camera-image" id="camera-image">
                <input type="submit" value="Upload Image" class="upload-button">
            </form>
        </div>
    </div>

    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <script>
    let video = document.getElementById('camera-preview');
    let canvas = document.getElementById('canvas');
    let context = canvas.getContext('2d');
    let cameraImageInput = document.getElementById('camera-image');

    function openCamera() {
        navigator.mediaDevices.getUserMedia({
            video: true
        }).then(function(stream) {
            video.srcObject = stream;
            video.play();
        }).catch(function(err) {
            console.log("An error occurred: " + err);
        });
    }

    function capturePhoto() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        let dataUrl = canvas.toDataURL('image/png');
        cameraImageInput.value = dataUrl;
    }

    document.getElementById('upload-form').addEventListener('submit', function() {
        document.querySelector('.loading-overlay').style.display = 'flex';
    });
    </script>
</body>

</html>