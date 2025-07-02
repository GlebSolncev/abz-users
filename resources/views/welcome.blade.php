<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management Panel</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 p-6">
<div id="app" class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-8 text-center">User Management Panel</h1>

    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-1/2 bg-white p-6 rounded-2xl shadow">
            <add-user-form></add-user-form>
        </div>

        <div class="md:w-1/2 bg-white p-6 rounded-2xl shadow max-h-screen overflow-y-auto">
            <user-list></user-list>
        </div>
    </div>

</div>
</body>
</html>
