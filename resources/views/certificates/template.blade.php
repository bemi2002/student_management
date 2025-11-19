<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; padding: 40px; }
        .frame { border: 8px solid #1e3a8a; border-radius: 15px; padding: 40px; }
        h1 { font-size: 42px; color: #1e3a8a; margin-bottom: 0; }
        h2 { font-size: 32px; margin-top: 10px; }
        p { font-size: 20px; }
    </style>
</head>
<body>
    <div class="frame">
        <h1>Certificate of Completion</h1>
        <p>This certifies that</p>
        <h2>{{ $student->name }}</h2>
        <p>has successfully completed the course</p>
        <h2>{{ $course->title }}</h2>
        <p>on {{ $date }}</p>
    </div>
</body>
</html>
