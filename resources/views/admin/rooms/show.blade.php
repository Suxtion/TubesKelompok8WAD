<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ruangan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Detail Ruangan: {{ $room->name }}</h1>

        <div class="mb-4">
            <p class="text-gray-700 font-bold">Nama Ruangan:</p>
            <p class="text-gray-900">{{ $room->name }}</p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 font-bold">Lokasi:</p>
            <p class="text-gray-900">{{ $room->location ?? '-' }}</p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 font-bold">Kapasitas:</p>
            <p class="text-gray-900">{{ $room->capacity }}</p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 font-bold">Deskripsi:</p>
            <p class="text-gray-900">{{ $room->description ?? '-' }}</p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 font-bold">Gambar:</p>
            @if ($room->image)
                <img src="{{ asset($room->image) }}" alt="{{ $room->name }}" class="w-64 h-64 object-cover rounded-md mt-2">
            @else
                <p class="text-gray-900">Tidak ada gambar</p>
            @endif
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">Edit Ruangan</a>
            <a href="{{ route('admin.rooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali ke Daftar</a>
        </div>
    </div>
</body>
</html>