<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey K3 – {{ $registrasi->nama_perusahaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .option-card { cursor: pointer; transition: all .2s; }
        .option-card:hover { transform: translateY(-2px); }
        .option-card input[type=radio]:checked + .option-inner {
            border-color: #003D7C; background: #eff6ff;
        }
        .option-card input[type=radio]:checked + .option-inner .radio-dot {
            border-color: #003D7C; background: #003D7C;
        }
        .survey-q { display: none; }
        .survey-q.active { display: block; animation: fadeUp .3s ease; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        .progress-fill { transition: width .4s ease; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100">

    {{-- Header --}}
    <div class="bg-[#003D7C] text-white py-4 px-6 shadow-lg">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div>
                <div class="font-bold">Survey K3 – Safety Golden Rules</div>
                <div class="text-blue-200 text-xs">{{ $registrasi->nama_perusahaan }} | {{ $registrasi->nama_pekerjaan }}</div>
            </div>
            <div class="text-right">
                <div class="text-yellow-400 text-xs font-semibold">Skor Target</div>
                <div class="text-white font-bold text-lg">100%</div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">

        {{-- Notifikasi hasil survey --}}
        @if(session('survey_result'))
        @php $result = session('survey_result'); @endphp
        <div class="rounded-2xl p-6 mb-6 flex items-start gap-4 {{ $result['lulus'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
            <span class="text-3xl">{{ $result['lulus'] ? '🎉' : '❌' }}</span>
            <div>
                <div class="font-bold {{ $result['lulus'] ? 'text-green-800' : 'text-red-800' }} text-lg">
                    {{ $result['lulus'] ? 'Selamat! Survey Lulus 100%' : 'Belum Lulus' }}
                </div>
                <div class="text-sm mt-1 {{ $result['lulus'] ? 'text-green-700' : 'text-red-700' }}">
                    <strong>{{ $result['nama_pekerja'] }}</strong>
                    @if($result['lulus'])
                        telah terdaftar di Gate Access. Nama akan muncul di halaman publik.
                    @else
                        mendapat skor <strong>{{ $result['skor'] }}%</strong>
                        ({{ $result['benar'] }}/{{ $result['total'] }} benar).
                        Harus mengulang dari awal untuk mendapatkan 100%.
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Daftar status pekerja --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
            <h2 class="font-bold text-gray-700 mb-4">Status Survey Pekerja</h2>
            <div class="space-y-2">
                @foreach($pekerjas as $p)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $p->survey_lulus ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $p->survey_lulus ? '✓' : $loop->iteration }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">{{ $p->nama_pekerja }}</div>
                            @if($p->survey_attempt > 0 && !$p->survey_lulus)
                            <div class="text-xs text-red-500">{{ $p->survey_attempt }}x percobaan – belum lulus</div>
                            @elseif($p->survey_lulus)
                            <div class="text-xs text-green-600">Lulus {{ $p->survey_lulus_at?->format('d/m/Y H:i') }}</div>
                            @else
                            <div class="text-xs text-gray-400">Belum mengikuti survey</div>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if(!$p->survey_lulus)
                        <button onclick="startSurvey({{ $p->id }}, '{{ $p->nama_pekerja }}')"
                                class="bg-[#003D7C] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-900 transition">
                            {{ $p->survey_attempt > 0 ? 'Coba Lagi' : 'Mulai Survey' }}
                        </button>
                        @else
                        <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-xl text-xs font-bold">✓ Terdaftar</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Semua lulus? --}}
        @if($pekerjas->every(fn($p) => $p->survey_lulus))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center mb-6">
            <div class="text-4xl mb-2">🎊</div>
            <div class="font-bold text-green-800 text-lg">Semua Pekerja Telah Lulus Survey!</div>
            <div class="text-green-700 text-sm mt-1">Data seluruh pekerja sudah terdaftar di Gate Access PLN Sengkang.</div>
            <a href="/" class="inline-block mt-4 bg-[#003D7C] text-white px-6 py-3 rounded-xl font-bold text-sm">← Kembali ke Beranda</a>
        </div>
        @endif

        {{-- ═══ MODAL SURVEY ══════════════════════════════════ --}}
        <div id="survey-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">

                {{-- Modal header --}}
                <div class="bg-[#003D7C] text-white p-5 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-bold" id="modal-pekerja-name">—</div>
                            <div class="text-blue-200 text-xs">Survey K3 – Safety Golden Rules</div>
                        </div>
                        <div class="text-right">
                            <div class="text-yellow-400 text-xs">Soal</div>
                            <div class="font-bold text-lg" id="q-counter">1 / {{ $questions->count() }}</div>
                        </div>
                    </div>
                    {{-- Progress bar --}}
                    <div class="mt-3 bg-blue-900 rounded-full h-1.5">
                        <div class="progress-fill bg-yellow-400 h-1.5 rounded-full" id="progress-bar" style="width:0%"></div>
                    </div>
                </div>

                <form id="survey-form" method="POST" action="{{ route('vendor.survey.submit', $registrasi->token_registrasi) }}">
                    @csrf
                    <input type="hidden" name="pekerja_id" id="hidden-pekerja-id">
                    <input type="hidden" name="_form_type" value="survey">

                    <div class="p-6">
                        {{-- Soal-soal --}}
                        @foreach($questions as $qi => $q)
                        <div class="survey-q {{ $qi === 0 ? 'active' : '' }}" data-q="{{ $qi }}">
                            {{-- Nomor soal --}}
                            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3">
                                Soal {{ $qi + 1 }} dari {{ $questions->count() }}
                            </div>

                            {{-- Teks pertanyaan --}}
                            <div class="font-bold text-gray-800 text-base leading-relaxed mb-4">
                                {{ $q->pertanyaan }}
                            </div>

                            {{-- Gambar soal (jika ada) --}}
                            @if($q->gambar_soal)
                            <div class="mb-4 rounded-xl overflow-hidden">
                                <img src="{{ Storage::url($q->gambar_soal) }}"
                                     alt="Gambar soal"
                                     class="w-full max-h-52 object-cover">
                            </div>
                            @endif

                            {{-- Pilihan jawaban --}}
                            <div class="space-y-3">
                                @foreach($q->options as $opt)
                                <label class="option-card block">
                                    <input type="radio"
                                           name="jawaban[{{ $q->id }}]"
                                           value="{{ $opt->id }}"
                                           class="sr-only"
                                           onchange="onAnswerSelected({{ $qi }})">
                                    <div class="option-inner border-2 border-gray-200 rounded-xl p-3 flex items-center gap-3 bg-white hover:border-blue-300">
                                        {{-- Radio dot --}}
                                        <div class="radio-dot w-5 h-5 rounded-full border-2 border-gray-300 flex-shrink-0 transition"></div>

                                        @if($opt->gambar_opsi)
                                        {{-- Opsi dengan gambar --}}
                                        <div class="flex items-center gap-3 flex-1">
                                            <img src="{{ Storage::url($opt->gambar_opsi) }}"
                                                 alt="{{ $opt->teks_opsi }}"
                                                 class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            <span class="text-sm font-medium text-gray-700">{{ $opt->teks_opsi }}</span>
                                        </div>
                                        @else
                                        {{-- Opsi teks saja --}}
                                        <span class="text-sm font-medium text-gray-700 flex-1">{{ $opt->teks_opsi }}</span>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            {{-- Validasi belum pilih --}}
                            <div class="hidden mt-3 text-red-500 text-sm" id="err-q-{{ $qi }}">
                                ⚠️ Pilih salah satu jawaban terlebih dahulu.
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Navigasi soal --}}
                    <div class="px-6 pb-6 flex justify-between items-center gap-3">
                        <button type="button" id="btn-prev" onclick="prevQ()"
                                class="hidden px-5 py-3 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                            ← Sebelumnya
                        </button>
                        <div class="flex-1"></div>
                        <button type="button" id="btn-next" onclick="nextQ()"
                                class="px-5 py-3 bg-[#003D7C] text-white rounded-xl text-sm font-bold hover:bg-blue-900 transition">
                            Selanjutnya →
                        </button>
                        <button type="submit" id="btn-submit"
                                class="hidden px-6 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition">
                            ✓ Kumpulkan Jawaban
                        </button>
                    </div>
                </form>

                {{-- Tutup modal --}}
                <div class="px-6 pb-6 text-center">
                    <button onclick="closeSurvey()" class="text-xs text-gray-400 hover:text-gray-600 transition underline">
                        Batal / Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const totalQ = {{ $questions->count() }};
        let currentQ = 0;

        function startSurvey(pekerjaId, namaPekerja) {
            currentQ = 0;
            document.getElementById('hidden-pekerja-id').value = pekerjaId;
            document.getElementById('modal-pekerja-name').textContent = namaPekerja;
            // Reset semua jawaban
            document.querySelectorAll('#survey-form input[type=radio]').forEach(r => r.checked = false);
            document.querySelectorAll('.option-inner').forEach(d => {
                d.classList.remove('border-blue-600', 'bg-blue-50');
            });
            document.querySelectorAll('.radio-dot').forEach(d => {
                d.classList.remove('border-blue-700', 'bg-blue-700');
                d.classList.add('border-gray-300');
            });
            updateQ();
            document.getElementById('survey-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSurvey() {
            document.getElementById('survey-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function onAnswerSelected(qi) {
            document.getElementById('err-q-' + qi)?.classList.add('hidden');
        }

        function nextQ() {
            // Validasi harus pilih jawaban
            const answered = document.querySelectorAll(
                `.survey-q[data-q="${currentQ}"] input[type=radio]:checked`
            ).length > 0;

            if (!answered) {
                document.getElementById('err-q-' + currentQ)?.classList.remove('hidden');
                return;
            }

            if (currentQ < totalQ - 1) {
                currentQ++;
                updateQ();
            }
        }

        function prevQ() {
            if (currentQ > 0) {
                currentQ--;
                updateQ();
            }
        }

        function updateQ() {
            document.querySelectorAll('.survey-q').forEach(el => el.classList.remove('active'));
            document.querySelector(`.survey-q[data-q="${currentQ}"]`)?.classList.add('active');

            // Counter
            document.getElementById('q-counter').textContent = `${currentQ + 1} / ${totalQ}`;

            // Progress
            const pct = ((currentQ + 1) / totalQ) * 100;
            document.getElementById('progress-bar').style.width = pct + '%';

            // Tombol
            document.getElementById('btn-prev').classList.toggle('hidden', currentQ === 0);
            document.getElementById('btn-next').classList.toggle('hidden', currentQ === totalQ - 1);
            document.getElementById('btn-submit').classList.toggle('hidden', currentQ !== totalQ - 1);
        }
    </script>
</body>
</html>
