<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Soal Survey K3</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-blue-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 text-center">
            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Mode Admin</span>
            <h1 class="text-2xl font-bold text-gray-800 mt-3">Preview Soal K3</h1>
            <p class="text-gray-500 text-sm mt-1">Total: {{ $questions->count() }} Soal Aktif</p>
        </div>

        <div class="space-y-6">
            @foreach($questions as $qi => $q)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="text-sm font-bold text-blue-600 mb-2">Soal {{ $qi + 1 }}</div>
                <div class="font-semibold text-gray-800 mb-4">{{ $q->pertanyaan }}</div>

                @if($q->gambar_soal)
                <img src="{{ Storage::url($q->gambar_soal) }}" class="mb-4 max-h-40 rounded-lg">
                @endif

                <div class="space-y-2">
                    @foreach($q->options as $opt)
                    <div class="p-3 rounded-xl border {{ $opt->is_benar ? 'border-green-500 bg-green-50' : 'border-gray-200' }} flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full border-2 {{ $opt->is_benar ? 'border-green-500 bg-green-500' : 'border-gray-300' }} flex-shrink-0"></div>
                        <div class="text-sm font-medium {{ $opt->is_benar ? 'text-green-800' : 'text-gray-600' }}">{{ $opt->teks_opsi }}</div>
                        @if($opt->is_benar) <span class="ml-auto text-xs font-bold text-green-600">Jawaban Benar</span> @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
