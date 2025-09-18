<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Placeholder Box</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-center text-3xl font-bold text-gray-800">
            Total Time to Load the Page {{ $totalTime }} seconds
        </div>
    </div>

</body>

</html>
