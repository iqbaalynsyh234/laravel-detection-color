<!DOCTYPE html>
<html>

<head>
    <title>Object Detection Results</title>
    <style>
    .card {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .card img {
        max-width: 90%;
        height: 200px;
    }
    </style>
</head>

<body>
    <h1>Object Detection Results</h1>

    <div class="card">
        <h2>Original Image</h2>
        <img src="{{ $originalImagePath }}" alt="Original Image">
    </div>

    <div class="card">
        <h2>Annotated Image</h2>
        <img src="{{ $annotatedImagePath }}" alt="Annotated Image">
    </div>

    <div class="card">
        <h2>Chart Image</h2>
        <img src="{{ $chartImagePath }}" alt="Chart Image">
    </div>

    <div class="card">
        <h2>Download Results</h2>
        <ul>
            <li><a href="{{ $excelPath }}">Download CSV</a></li>
            <li><a href="{{ $pdfPath }}">Download PDF</a></li>
        </ul>
    </div>
</body>

</html>