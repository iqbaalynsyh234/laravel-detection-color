<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- displays site properly based on user's device -->
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;500;700;800&display=swap"
        rel="stylesheet">
    <title>Results Summary and Object Detection</title>
    <style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Container Styling */
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }

    /* Card Styling */
    .card {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
        max-width: 600px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0px 0px 40px #ddd9d9;
        background-color: #fff;
        padding: 20px;
        margin: 20px 0;
    }

    /* Left and Right Sections of the Card */
    .left,
    .right {
        flex: 1;
        width: 100%;
    }

    .left {
        background-color: black;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        color: #fff;
        text-align: center;
        border-radius: 20px;
    }

    .right {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        padding: 24px;
    }

    .chart-image img {
        max-width: 100%;
        height: auto;
        border-radius: 20px;
    }

    .result-text {
        margin-top: 20px;
    }

    /* Download Card Styling */
    .download-card {
        width: 100%;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 20px;
        text-align: center;
        background-color: #f9f9f9;
    }

    .download-card ul {
        list-style-type: none;
        padding: 0;
    }

    .download-card li {
        margin: 10px 0;
    }

    /* Image Card Styling */
    .image-card {
        width: 100%;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 20px;
        text-align: center;
        background-color: #f9f9f9;
    }

    .image-card img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }

    /* Additional Styling */
    h1 {
        text-align: center;
        margin: 40px 0 20px;
    }

    h2,
    h3 {
        margin-bottom: 20px;
    }

    .button {
        border: none;
        padding: 10px 20px;
        border-radius: 200px;
        background-color: #180429;
        color: #fff;
        font-size: 1.3em;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="left">
                <h1>Object Detection Results</h1>
                <div class="chart-image">
                    <img src="{{ $chartImagePath }}" alt="Chart Image">
                </div>
                <div class="card right">
                    <div class="download-card">
                        <h2 style="color: black;">Download Results</h2>
                        <ul>
                            <li><a href="{{ $excelPath }}">Download CSV</a></li>
                            <li><a href="{{ $pdfPath }}">Download PDF</a></li>
                        </ul>
                        <div class="image-card">
                            <h2>Original Image</h2>
                            <img src="{{ $originalImagePath }}" alt="Original Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Chart Image</h2>
            <div class="chart-image">
                <img src="{{ $chartImagePath }}" alt="Chart Image">
            </div>
        </div>
    </div>