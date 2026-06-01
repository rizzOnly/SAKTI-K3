<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil – Fit to Work</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl p-10 max-w-md w-full text-center shadow-xl">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl">✅</div>
        <h1 class="font-bold text-2xl text-gray-800 mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-gray-500 text-sm mb-1">
            <strong class="text-gray-700"><?php echo e(session('ftw_perusahaan')); ?></strong>
        </p>
        <p class="text-gray-500 text-sm mb-6">
            <strong><?php echo e(session('ftw_jumlah')); ?></strong> pekerja telah didaftarkan untuk pemeriksaan Fit to Work.
        </p>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-left">
            <div class="font-semibold text-amber-800 text-sm mb-2">⏳ Langkah Selanjutnya</div>
            <ul class="text-amber-700 text-xs space-y-1.5 list-disc list-inside">
                <li>Notifikasi telah dikirim ke dokter klinik dan tim K3</li>
                <li>Setiap pekerja harus datang ke <strong>Klinik Unit PLN Sengkang</strong></li>
                <li>Dokter akan memeriksa dan mencatat hasilnya di sistem</li>
                <li>Pekerja yang dinyatakan Fit akan tampil di landing page</li>
                <li>Status aktif sesuai durasi pekerjaan yang didaftarkan</li>
            </ul>
        </div>
        <a href="/" class="block w-full bg-[#003D7C] text-white font-bold py-3 rounded-xl text-sm hover:bg-blue-900 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/fit-to-work/sukses.blade.php ENDPATH**/ ?>