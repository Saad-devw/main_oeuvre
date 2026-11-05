<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Générer les documents du mois') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Lettre d'engagement & Compte rendu de prise de service</p>
            </div>
            <a href="{{ route('ouvriers.index') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-5 gap-6">

                {{-- Left: Form --}}
                <div class="col-span-3 space-y-5">
                    @if ($errors->has('general'))
                        <div class="mb-4 px-4 py-3 text-sm rounded-lg bg-red-50 text-red-700">
                            {{ $errors->first('general') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('documents.generate') }}" id="generateForm">
                        @csrf

                        {{-- Paramètres de la période --}}
                        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs"
                                    style="background: #1a6b3a;">1</span>
                                Paramètres de la période
                            </h2>

                            <div class="grid grid-cols-2 gap-4">

                                {{-- Date début --}}
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Date de début <span class="text-red-400">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date') }}"
                                        class="w-full px-3 py-2 text-sm border rounded-lg text-gray-700 focus:outline-none focus:ring-1 transition-colors
                                            {{ $errors->has('start_date') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                                    @error('start_date')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Date fin --}}
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Date de fin <span class="text-red-400">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date"
                                        value="{{ old('end_date') }}"
                                        class="w-full px-3 py-2 text-sm border rounded-lg text-gray-700 focus:outline-none focus:ring-1 transition-colors
                                            {{ $errors->has('end_date') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                                    @error('end_date')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Prix par jour --}}
                                <div class="col-span-2">
                                    <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Prix par jour (DH) <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="price_per_day" id="price_per_day"
                                            value="{{ old('price_per_day') }}"
                                            placeholder="Ex: 95" min="1" step="0.01"
                                            class="w-full px-3 py-2 pr-14 text-sm border rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors
                                                {{ $errors->has('price_per_day') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">
                                            DH/j
                                        </span>
                                    </div>
                                    @error('price_per_day')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Nombre de jours --}}
                                <div>
                                    <label for="working_days" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nombre de jours <span class="text-red-400">*</span>
                                    </label>
                                    <input type="number" name="working_days" id="working_days"
                                        value="{{ old('working_days', 22) }}" min="1" max="31"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-1 focus:border-green-600 focus:ring-green-50">
                                    @error('working_days')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Date signature --}}
                                <div>
                                    <label for="signature_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Date Signature <span class="text-gray-400 ext-xs">(Optionnelle)</span>
                                    </label>
                                    <input type="date" name="signature_date" id="signature_date"
                                        value="{{ old('signature_date') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-1 focus:border-green-600 focus:ring-green-50">
                                    @error('signature_date')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Preview calcul --}}
                            <div id="calcPreview" class="mt-4 p-3.5 rounded-lg hidden bg-green-50">
                                <p class="text-xs font-medium text-green-800 mb-2">Aperçu du calcul :</p>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-xs text-green-800">
                                    <span>Salaire brut × Nombre de jours :</span>
                                    <span class="font-semibold font-mono" id="calc_brut">—</span>
                                    <span>Part RCAR salariale (6%) :</span>
                                    <span class="font-semibold font-mono" id="calc_rcar">—</span>
                                    <span class="font-bold">Salaire net :</span>
                                    <span class="font-bold font-mono" id="calc_net">—</span>
                                </div>
                            </div>
                        </div>

                        {{-- Sélection des employés --}}
                        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs"
                                        style="background: #1a6b3a;">2</span>
                                    Sélectionner les employés
                                </h2>
                                <div class="flex items-center gap-3">
                                    <button type="button" id="selectAll"
                                        class="text-xs font-medium text-green-700 hover:text-green-900 transition-colors">
                                        Tout sélectionner
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" id="deselectAll"
                                        class="text-xs font-medium text-gray-400 hover:text-gray-600 transition-colors">
                                        Tout désélectionner
                                    </button>
                                </div>
                            </div>

                            {{-- Search employees --}}
                            <div class="relative mb-3">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                                </svg>
                                <input type="text" id="empSearch" placeholder="Filtrer les employés..."
                                    class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-300 transition-colors">
                            </div>

                            @error('employee_ids')
                                <p class="mb-3 text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            {{-- Employee list --}}
                            <div class="space-y-1.5 max-h-72 overflow-y-auto pr-1" id="employeeList">
                                @foreach ($employees as $employee)
                                    <label
                                        class="emp-item flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer transition-all duration-100 hover:border-green-300 has-[:checked]:border-green-400 has-[:checked]:bg-green-50"
                                        data-name="{{ strtolower($employee->full_name) }}">
                                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                            {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 rounded emp-checkbox accent-green-700">
                                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0"
                                                style="background: #1a6b3a;">
                                                {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $employee->full_name }}
                                                </p>
                                                <p class="text-xs font-mono text-gray-400">
                                                    {{ $employee->cin }} — {{ $employee->his_work }}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Counter --}}
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <p class="text-xs text-gray-400">
                                    <span id="selectedCount">0</span> employé(s) sélectionné(s)
                                </p>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="submitBtn"
                            class="w-full inline-flex items-center justify-center gap-2 py-3 text-base font-medium text-white rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background: #1a6b3a;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Générer et télécharger les documents
                        </button>

                    </form>
                </div>

                {{-- Right: Info panel --}}
                <div class="col-span-2 space-y-5">

                    {{-- Documents générés --}}
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Documents générés</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                                <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0 mt-0.5 bg-green-50">
                                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Compte Rendu de Prise de Service</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Nom, catégorie, emploi, date de prise de service</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                                <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0 mt-0.5 bg-green-50">
                                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Lettre d'Engagement</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Période, salaire calculé, déduction RCAR 6%</p>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-gray-400">
                            Un fichier <span class="font-mono">.docx</span> par employé sera généré dans une archive
                            <span class="font-mono">.zip</span>.
                        </p>
                    </div>

                    {{-- Formule de calcul --}}
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Formule de calcul</h3>
                        <div class="space-y-2 text-xs text-gray-500">
                            <div class="p-2.5 rounded-lg font-mono bg-gray-50">Brut = prix/jour × Nombre de jours</div>
                            <div class="p-2.5 rounded-lg font-mono bg-gray-50">RCAR = Brut × 6%</div>
                            <div class="p-2.5 rounded-lg font-mono font-semibold bg-green-50 text-green-800">
                                Net = Brut − RCAR
                            </div>
                        </div>
                    </div>

                    {{-- Récapitulatif sélection --}}
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hidden" id="selectionSummary">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Récapitulatif</h3>
                        <div id="summaryList" class="space-y-1.5 text-xs text-gray-500"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ── Calcul salaire ──────────────────────────────────────────
            function updateCalc() {
                const price = parseFloat(document.getElementById('price_per_day').value);
                const preview = document.getElementById('calcPreview');
                if (!price || price <= 0) {
                    preview.classList.add('hidden');
                    return;
                }
                const days = parseInt(document.getElementById('working_days').value) || 0;
                const brut = price * days;
                const rcar = brut * 0.06;
                const net  = brut - rcar;

                document.getElementById('working_days').addEventListener('input', updateCalc);
                document.getElementById('calc_brut').textContent = brut.toFixed(2) + ' DH';
                document.getElementById('calc_rcar').textContent = rcar.toFixed(2) + ' DH';
                document.getElementById('calc_net').textContent  = net.toFixed(2)  + ' DH';
                preview.classList.remove('hidden');
            }
            document.getElementById('price_per_day').addEventListener('input', updateCalc);
            updateCalc();

            // ── Sélection employés ──────────────────────────────────────
            function updateCounter() {
                const checked = document.querySelectorAll('.emp-checkbox:checked');
                document.getElementById('selectedCount').textContent = checked.length;

                const summary = document.getElementById('selectionSummary');
                const list    = document.getElementById('summaryList');

                if (checked.length === 0) {
                    summary.classList.add('hidden');
                    return;
                }

                summary.classList.remove('hidden');
                list.innerHTML = Array.from(checked).map(cb => {
                    const name = cb.closest('label').querySelector('p.text-sm').textContent.trim();
                    return `<div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 bg-green-600"></span>
                        ${name}
                    </div>`;
                }).join('');
            }

            document.querySelectorAll('.emp-checkbox').forEach(cb => {
                cb.addEventListener('change', updateCounter);
            });

            document.getElementById('selectAll').addEventListener('click', () => {
                document.querySelectorAll('.emp-checkbox:not([disabled])').forEach(cb => cb.checked = true);
                updateCounter();
            });

            document.getElementById('deselectAll').addEventListener('click', () => {
                document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = false);
                updateCounter();
            });

            // ── Filtre employés ─────────────────────────────────────────
            document.getElementById('empSearch').addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.emp-item').forEach(item => {
                    item.style.display = item.dataset.name.includes(q) ? '' : 'none';
                });
            });

            updateCounter();
        </script>
    @endpush

</x-app-layout>
