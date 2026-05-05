<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil Daftar – Fit to Work</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl p-10 max-w-md w-full text-center shadow-xl">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl">✅</div>
        <h1 class="font-bold text-2xl text-gray-800 mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-gray-500 text-sm mb-2">
            <strong class="text-gray-700">{{ session('ftw_nama') }}</strong>
        </p>
        <p class="text-gray-500 text-sm mb-8">
            Notifikasi telah dikirim ke dokter klinik dan tim K3.
            Silakan segera datang ke <strong>Klinik Unit</strong> untuk pemeriksaan fisik Fit to Work.
        </p>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-left">
            <div class="font-semibold text-amber-800 text-sm mb-1">⏳ Selanjutnya</div>
            <ul class="text-amber-700 text-xs space-y-1 list-disc list-inside">
                <li>Datang ke Klinik Unit PLN Sengkang</li>
                <li>Dokter akan melakukan pemeriksaan fisik</li>
                <li>Hasil Fit to Work akan tercatat di sistem</li>
                <li>Jika vendor, status akan muncul di landing page</li>
            </ul>
        </div>
        <a href="/" class="block w-full bg-[#003D7C] text-white font-bold py-3 rounded-xl text-sm hover:bg-blue-900 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
