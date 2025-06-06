<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ruangan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-8">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-indigo-700">Daftar Ruangan</h1>
            <a href="{{ route('admin.rooms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-150">+ Tambah Ruangan</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2" role="alert">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="py-3 px-4 border-b text-left font-semibold text-indigo-700">Gambar</th>
                        <th class="py-3 px-4 border-b text-left font-semibold text-indigo-700">Nama</th>
                        <th class="py-3 px-4 border-b text-left font-semibold text-indigo-700">Lokasi</th>
                        <th class="py-3 px-4 border-b text-left font-semibold text-indigo-700">Kapasitas</th>
                        <th class="py-3 px-4 border-b text-left font-semibold text-indigo-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-indigo-50 transition">
                            <td class="py-3 px-4 border-b">
                                @if ($room->image)
                                    <img src="{{ asset($room->image) }}" alt="{{ $room->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b font-medium text-gray-800">{{ $room->name }}</td>
                            <td class="py-3 px-4 border-b text-gray-600">{{ $room->location ?? '-' }}</td>
                            <td class="py-3 px-4 border-b text-gray-600">{{ $room->capacity }}</td>
                            <td class="py-3 px-4 border-b">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded transition text-sm font-semibold">Lihat</a>
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded transition text-sm font-semibold">Edit</a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded transition text-sm font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 px-4 border-b text-center text-gray-500">Belum ada ruangan yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>