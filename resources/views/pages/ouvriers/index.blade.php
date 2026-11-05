<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Ouvriers saisonniers') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Liste des ouvriers saisonniers non spécialisés</p>
            </div>
            <a href="{{ route('ouvriers.create') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                style="background: #1a6b3a;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel ouvrier
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="bg-white border border-gray-100 rounded-xl px-5 py-4 flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background: #e6f4ed;">
                        <svg class="w-5 h-5" style="color: #1a6b3a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-gray-900">{{ $ouvriers->total() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total ouvriers</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl px-5 py-4 flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-blue-50">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-gray-900">{{ $ouvriers->where('gender', 'homme')->count() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Hommes</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl px-5 py-4 flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-pink-50">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-gray-900">{{ $ouvriers->where('gender', 'femme')->count() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Femmes</p>
                    </div>
                </div>

            </div>

            @if (session('success'))
                <div class="px-4 py-3 text-sm rounded-lg bg-green-50 text-green-800">{{ session('success') }}</div>
            @endif
            @if ($errors->has('file'))
                <div class="px-4 py-3 text-sm rounded-lg bg-red-50 text-red-700">{{ $errors->first('file') }}</div>
            @endif
            @if (session('import_errors'))
                <div class="px-4 py-3 text-xs rounded-lg bg-amber-50 text-amber-800 space-y-1">
                    <p class="font-medium text-sm">Lignes ignorées :</p>
                    @foreach (session('import_errors') as $err)
                        <p>• {{ $err }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Table card --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">

                {{-- Toolbar --}}
                <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100">
                    <div class="relative max-w-xs flex-1">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        <input id="searchInput" type="text"
                            placeholder="Rechercher par nom ou CIN…"
                            value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-300 transition-colors">
                    </div>

                    <form method="POST" action="{{ route('ouvriers.import') }}" id="importForm">
                        @csrf
                        {{-- no name attribute: the script sends the file itself --}}
                        <input type="file" id="importFile" accept=".xlsx,.xls,.csv" class="hidden">
                        <button type="button" onclick="document.getElementById('importFile').click()"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Importer Liste des Oeuvriers (excel)
                        </button>
                    </form>

                    <form method="POST" action="{{ route('ouvriers.bulk-pdf') }}" id="bulkPdfForm">
                        @csrf
                        {{-- no name attribute: the script sends the files itself --}}
                        <input type="file" id="bulkPdfFiles" accept="application/pdf" multiple class="hidden">
                        <button type="button" onclick="document.getElementById('bulkPdfFiles').click()"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Importer fichiers personnels
                        </button>
                    </form>

                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 text-sm font-medium text-white rounded-lg ml-auto transition-colors"
                        style="background: #1a6b3a;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Générer documents du mois
                    </a>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                                <th class="px-5 py-3 text-left font-medium w-12">#</th>
                                <th class="px-5 py-3 text-left font-medium">Nom complet</th>
                                <th class="px-5 py-3 text-left font-medium">CIN</th>
                                <th class="px-5 py-3 text-left font-medium">Poste / Travail</th>
                                <th class="px-5 py-3 text-left font-medium">Genre</th>
                                <th class="px-5 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($ouvriers as $ouvrier)
                                <tr class="hover:bg-gray-50/60 transition-colors duration-100 ouvrier-row"
                                    data-name="{{ strtolower($ouvrier->full_name) }}"
                                    data-cin="{{ strtolower($ouvrier->cin) }}">

                                    <td class="px-5 py-3.5 font-mono text-xs text-gray-400">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0"
                                                style="background: #1a6b3a;">
                                                {{ strtoupper(substr($ouvrier->full_name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $ouvrier->full_name }}</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <span class="font-mono text-xs px-2.5 py-1 rounded-md bg-gray-100 text-gray-500 border border-gray-200">
                                            {{ strtoupper($ouvrier->cin) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-3.5 text-gray-500">{{ $ouvrier->his_work }}</td>

                                    <td class="px-5 py-3.5">
                                        @if ($ouvrier->gender === 'femme')
                                            <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full bg-pink-50 text-pink-700">
                                                Femme
                                            </span>
                                        @else
                                            <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                                                Homme
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-1.5 justify-end">
                                            <a href="{{ route('ouvriers.edit', $ouvrier) }}"
                                                title="Modifier"
                                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('ouvriers.destroy', $ouvrier) }}"
                                                onsubmit="return confirm('Supprimer {{ $ouvrier->full_name }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Supprimer"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                                        <svg class="w-10 h-10 mx-auto mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                                        </svg>
                                        <p class="text-sm">Aucun ouvrier trouvé.</p>
                                        <a href="{{ route('ouvriers.create') }}"
                                            class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 text-sm font-medium text-white rounded-lg"
                                            style="background: #1a6b3a;">
                                            Ajouter le premier
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($ouvriers->hasPages())
                    <div class="px-5 py-3.5 border-t border-gray-100">
                        {{ $ouvriers->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div id="uploadOverlay" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <svg id="upSpinner" class="w-5 h-5 animate-spin" style="color:#1a6b3a;" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <h3 id="upTitle" class="text-sm font-semibold text-gray-900">Importation des PDF personnels…</h3>
            </div>

            <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                <div id="upBar" class="h-full rounded-full transition-all duration-200" style="width:0%; background:#1a6b3a;"></div>
            </div>

            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                <span id="upCount">0 / 0 fichiers</span>
                <span id="upPct" class="font-mono">0%</span>
            </div>
            <p id="upStatus" class="mt-1 text-xs text-gray-400"></p>

            <div id="upResult" class="hidden mt-4">
                <p id="upSummary" class="text-sm font-medium text-green-700"></p>
                <div id="upErrors" class="mt-2 max-h-40 overflow-y-auto text-xs text-amber-800 bg-amber-50 rounded-lg p-3 space-y-1 hidden"></div>
                <button type="button" id="upClose"
                    class="mt-4 w-full py-2 text-sm font-medium text-white rounded-lg" style="background:#1a6b3a;">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.ouvrier-row').forEach(row => {
                    const hit = row.dataset.name.includes(q) || row.dataset.cin.includes(q);
                    row.style.display = hit ? '' : 'none';
                });
            });
        </script>
    @endpush

    @push('scripts') <!-- CIN -->
        <script src="{{ asset('js/pdf-lib.min.js') }}"></script>
        <script>
            (function () {
                const MAX_BATCH_BYTES = 1.5 * 1024 * 1024;   // max size of one request
                const MAX_BATCH_FILES = 10;                   // max files in one request

                const input   = document.getElementById('bulkPdfFiles');
                const form    = document.getElementById('bulkPdfForm');
                const overlay = document.getElementById('uploadOverlay');
                const bar     = document.getElementById('upBar');
                const pct     = document.getElementById('upPct');
                const count   = document.getElementById('upCount');
                const status  = document.getElementById('upStatus');
                const title   = document.getElementById('upTitle');
                const spinner = document.getElementById('upSpinner');
                const result  = document.getElementById('upResult');
                const summary = document.getElementById('upSummary');
                const errBox  = document.getElementById('upErrors');

                document.getElementById('upClose').addEventListener('click', () => location.reload());

                function setProgress(done, total, bytesDone, bytesTotal) {
                    const p = bytesTotal ? Math.min(100, Math.round(bytesDone / bytesTotal * 100)) : 0;
                    bar.style.width = p + '%';
                    pct.textContent = p + '%';
                    count.textContent = done + ' / ' + total + ' fichiers';
                }

                // Re-write the PDF in the classic format that the server can merge
                async function normalizePdf(file) {
                    try {
                        const src = await PDFLib.PDFDocument.load(await file.arrayBuffer(), { ignoreEncryption: true });
                        const out = await PDFLib.PDFDocument.create();
                        const pages = await out.copyPages(src, src.getPageIndices());
                        pages.forEach(p => out.addPage(p));
                        const bytes = await out.save({ useObjectStreams: false });
                        return new File([bytes], file.name, { type: 'application/pdf' });
                    } catch (e) {
                        return file; // could not be rewritten: send the original
                    }
                }

                // Group files so that no request is bigger than MAX_BATCH_BYTES
                function makeBatches(files) {
                    const batches = [];
                    let current = [], size = 0;
                    for (const f of files) {
                        if (current.length && (size + f.size > MAX_BATCH_BYTES || current.length >= MAX_BATCH_FILES)) {
                            batches.push(current);
                            current = []; size = 0;
                        }
                        current.push(f);
                        size += f.size;
                    }
                    if (current.length) batches.push(current);
                    return batches;
                }

                function sendBatch(files, token, onProgress) {
                    return new Promise((resolve, reject) => {
                        const fd = new FormData();
                        fd.append('_token', token);
                        files.forEach(f => fd.append('files[]', f));

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', form.action);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                        xhr.upload.onprogress = e => { if (e.lengthComputable) onProgress(e.loaded, e.total); };
                        xhr.upload.onload = () => { status.textContent = 'Traitement des fichiers sur le serveur…'; };

                        xhr.onload = () => {
                            if (xhr.status === 413) return reject(new Error('fichiers trop volumineux pour le serveur'));
                            if (xhr.status < 200 || xhr.status >= 300) return reject(new Error('erreur serveur ' + xhr.status));
                            try { resolve(JSON.parse(xhr.responseText)); }
                            catch (e) { reject(new Error('réponse invalide')); }
                        };
                        xhr.onerror = () => reject(new Error('connexion interrompue'));
                        xhr.send(fd);
                    });
                }

                input.addEventListener('change', async () => {
                    const rawFiles = Array.from(input.files);
                    if (!rawFiles.length) return;

                    // reset + show overlay
                    overlay.classList.remove('hidden');
                    result.classList.add('hidden');
                    errBox.classList.add('hidden');
                    errBox.innerHTML = '';
                    spinner.classList.remove('hidden');
                    title.textContent = 'Importation des PDF personnels…';
                    setProgress(0, rawFiles.length, 0, 1);

                    // 1) prepare every PDF in the browser
                    const files = [];
                    for (let i = 0; i < rawFiles.length; i++) {
                        status.textContent = 'Préparation des PDF… (' + (i + 1) + ' / ' + rawFiles.length + ')';
                        files.push(await normalizePdf(rawFiles[i]));
                    }

                    // 2) upload in size-limited batches
                    const token = form.querySelector('input[name=_token]').value;
                    const bytesTotal = files.reduce((s, f) => s + f.size, 0);
                    let bytesDone = 0, filesDone = 0, ok = 0, errors = [];
                    setProgress(0, files.length, 0, bytesTotal);

                    for (const batch of makeBatches(files)) {
                        const batchBytes = batch.reduce((s, f) => s + f.size, 0);
                        status.textContent = 'Envoi des fichiers…';

                        try {
                            const data = await sendBatch(batch, token, (loaded, total) => {
                                const part = total ? loaded / total * batchBytes : 0;
                                setProgress(filesDone, files.length, bytesDone + part, bytesTotal);
                            });
                            ok += data.ok;
                            errors = errors.concat(data.errors);
                        } catch (e) {
                            batch.forEach(f => errors.push(f.name + ' : échec (' + e.message + ')'));
                        }

                        bytesDone += batchBytes;
                        filesDone += batch.length;
                        setProgress(filesDone, files.length, bytesDone, bytesTotal);
                    }

                    // 3) finished
                    input.value = '';
                    spinner.classList.add('hidden');
                    title.textContent = 'Importation terminée';
                    status.textContent = '';
                    summary.textContent = ok + ' PDF associé(s) sur ' + files.length + '.';
                    result.classList.remove('hidden');

                    if (errors.length) {
                        errors.forEach(msg => {
                            const p = document.createElement('p');
                            p.textContent = '• ' + msg;
                            errBox.appendChild(p);
                        });
                        errBox.classList.remove('hidden');
                    }
                });
            })();
        </script>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const input   = document.getElementById('importFile');
                const form    = document.getElementById('importForm');
                const overlay = document.getElementById('uploadOverlay');
                const bar     = document.getElementById('upBar');
                const pct     = document.getElementById('upPct');
                const count   = document.getElementById('upCount');
                const status  = document.getElementById('upStatus');
                const title   = document.getElementById('upTitle');
                const spinner = document.getElementById('upSpinner');
                const result  = document.getElementById('upResult');
                const summary = document.getElementById('upSummary');
                const errBox  = document.getElementById('upErrors');

                function setBar(p) {
                    bar.style.width = p + '%';
                    pct.textContent = p + '%';
                }

                function finish(message, isError, errors) {
                    spinner.classList.add('hidden');
                    bar.classList.remove('animate-pulse');
                    title.textContent = isError ? 'Échec de l\'importation' : 'Importation terminée';
                    status.textContent = '';
                    summary.textContent = message;
                    summary.className = 'text-sm font-medium ' + (isError ? 'text-red-600' : 'text-green-700');
                    result.classList.remove('hidden');

                    errBox.innerHTML = '';
                    if (errors && errors.length) {
                        errors.forEach(msg => {
                            const p = document.createElement('p');
                            p.textContent = '• ' + msg;
                            errBox.appendChild(p);
                        });
                        errBox.classList.remove('hidden');
                    }
                }

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;

                    // reset + show overlay
                    overlay.classList.remove('hidden');
                    result.classList.add('hidden');
                    errBox.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    title.textContent = 'Importation de la liste des ouvriers…';
                    count.textContent = file.name;
                    status.textContent = 'Envoi du fichier…';
                    setBar(0);

                    const fd = new FormData();
                    fd.append('_token', form.querySelector('input[name=_token]').value);
                    fd.append('file', file);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    xhr.upload.onprogress = e => {
                        if (e.lengthComputable) setBar(Math.round(e.loaded / e.total * 100));
                    };
                    xhr.upload.onload = () => {
                        setBar(100);
                        bar.classList.add('animate-pulse');
                        status.textContent = 'Lecture et enregistrement des ouvriers…';
                    };

                    xhr.onload = () => {
                        input.value = '';
                        let data = {};
                        try { data = JSON.parse(xhr.responseText); } catch (e) {}

                        if (xhr.status === 422) {
                            const msg = data.errors && data.errors.file ? data.errors.file[0] : 'Fichier invalide.';
                            return finish(msg, true, []);
                        }
                        if (xhr.status === 413) return finish('Fichier trop volumineux pour le serveur.', true, []);
                        if (xhr.status < 200 || xhr.status >= 300) return finish('Erreur serveur (' + xhr.status + ').', true, []);

                        finish(data.created + ' ajouté(s), ' + data.updated + ' mis à jour.'
                            + (data.errors.length ? ' ' + data.errors.length + ' ligne(s) ignorée(s).' : ''),
                            false, data.errors);
                    };
                    xhr.onerror = () => { input.value = ''; finish('Connexion interrompue.', true, []); };

                    xhr.send(fd);
                });
            })();
        </script>
    @endpush

</x-app-layout>
