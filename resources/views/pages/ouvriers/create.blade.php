<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ isset($ouvrier) ? __('Modifier un ouvrier') : __('Ajouter un ouvrier') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Ouvrier saisonnier non spécialisé</p>
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
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">

                {{-- Form header --}}
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700">
                        {{ isset($ouvrier) ? 'Informations de l\'ouvrier' : 'Informations du nouvel ouvrier' }}
                    </h3>
                </div>

                <form method="POST"
                    enctype="multipart/form-data"
                    action="{{ isset($ouvrier) ? route('ouvriers.update', $ouvrier) : route('ouvriers.store') }}"
                    class="px-6 py-5 space-y-5">
                    @csrf
                    @if (isset($ouvrier))
                        @method('PUT')
                    @endif

                    {{-- Nom complet --}}
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nom et Prénom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name"
                            value="{{ old('full_name', $ouvrier->full_name ?? '') }}"
                            placeholder="Ex: BENALI Mohammed"
                            class="w-full px-3 py-2 text-sm border rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors
                                {{ $errors->has('full_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                        @error('full_name')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CIN --}}
                    <div>
                        <label for="cin" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Numéro CIN <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="cin" name="cin"
                            value="{{ old('cin', $ouvrier->cin ?? '') }}"
                            placeholder="Ex: B123456"
                            style="text-transform: uppercase; font-family: monospace; letter-spacing: 0.04em;"
                            class="w-full px-3 py-2 text-sm border rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors
                                {{ $errors->has('cin') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                        @error('cin')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Travail / Poste --}}
                    <div>
                        <label for="his_work" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Emploi à exercer <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="his_work" name="his_work"
                            value="{{ old('his_work', $ouvrier->his_work ?? '') }}"
                            placeholder="Ex: Nettoyage, Jardinage, Gardiennage…"
                            class="w-full px-3 py-2 text-sm border rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors
                                {{ $errors->has('his_work') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-green-600 focus:ring-green-50' }}">
                        @error('his_work')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Genre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Genre <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-3">

                            <label class="flex-1 flex items-center gap-3 px-4 py-3 border rounded-lg cursor-pointer transition-colors
                                {{ old('gender', $ouvrier->gender ?? 'homme') === 'homme' ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="gender" value="homme"
                                    {{ old('gender', $ouvrier->gender ?? 'homme') === 'homme' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-500 focus:ring-blue-200"
                                    onchange="updateGenderCards()">
                                <span class="text-sm font-medium {{ old('gender', $ouvrier->gender ?? 'homme') === 'homme' ? 'text-blue-700' : 'text-gray-700' }}">
                                    Homme
                                </span>
                            </label>

                            <label class="flex-1 flex items-center gap-3 px-4 py-3 border rounded-lg cursor-pointer transition-colors
                                {{ old('gender', $ouvrier->gender ?? '') === 'femme' ? 'border-pink-400 bg-pink-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="gender" value="femme"
                                    {{ old('gender', $ouvrier->gender ?? '') === 'femme' ? 'checked' : '' }}
                                    class="w-4 h-4 text-pink-500 focus:ring-pink-200"
                                    onchange="updateGenderCards()">
                                <span class="text-sm font-medium {{ old('gender', $ouvrier->gender ?? '') === 'femme' ? 'text-pink-700' : 'text-gray-700' }}">
                                    Femme
                                </span>
                            </label>

                        </div>
                        @error('gender')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Documents personnels --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Documents personnels <span class="text-gray-400 font-normal">(PDF)</span>
                        </label>

                        <div id="dropzone"
                            class="relative flex items-center gap-4 px-4 py-4 border-2 border-dashed rounded-xl bg-gray-50 transition-colors cursor-pointer
                                {{ $errors->has('personal_pdf') ? 'border-red-300' : 'border-gray-200 hover:border-green-600 hover:bg-green-50/40' }}">

                            <input type="file" id="personal_pdf" name="personal_pdf" accept="application/pdf"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #e6f4ed;">
                                <svg class="w-5 h-5" style="color: #1a6b3a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p id="dropTitle" class="text-sm font-medium text-gray-700 truncate">
                                    Cliquez ou glissez un fichier PDF ici
                                </p>
                                <p id="dropHint" class="text-xs text-gray-400 mt-0.5">PDF uniquement · 10 Mo maximum</p>
                            </div>

                            <button type="button" id="dropClear"
                                class="hidden relative z-10 p-1.5 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                title="Retirer le fichier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        @if (isset($ouvrier) && $ouvrier->personal_pdf)
                            <p class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Un fichier est déjà enregistré. Envoyez-en un nouveau pour le remplacer.
                            </p>
                        @endif

                        @error('personal_pdf')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-5 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                            style="background: #1a6b3a;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ isset($ouvrier) ? 'Enregistrer les modifications' : "Ajouter l'ouvrier" }}
                        </button>
                        <a href="{{ route('ouvriers.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function updateGenderCards() {
                const radios = document.querySelectorAll('input[name="gender"]');
                radios.forEach(radio => {
                    const label = radio.closest('label');
                    const span = label.querySelector('span');
                    if (radio.value === 'homme') {
                        label.className = label.className.replace(/border-\S+|bg-\S+/g, '').trim();
                        if (radio.checked) {
                            label.classList.add('border-blue-400', 'bg-blue-50');
                            span.className = 'text-sm font-medium text-blue-700';
                        } else {
                            label.classList.add('border-gray-200', 'hover:bg-gray-50');
                            span.className = 'text-sm font-medium text-gray-700';
                        }
                    } else {
                        label.className = label.className.replace(/border-\S+|bg-\S+/g, '').trim();
                        if (radio.checked) {
                            label.classList.add('border-pink-400', 'bg-pink-50');
                            span.className = 'text-sm font-medium text-pink-700';
                        } else {
                            label.classList.add('border-gray-200', 'hover:bg-gray-50');
                            span.className = 'text-sm font-medium text-gray-700';
                        }
                    }
                });
            }

            (function () {
                const input = document.getElementById('personal_pdf');
                const zone  = document.getElementById('dropzone');
                const title = document.getElementById('dropTitle');
                const hint  = document.getElementById('dropHint');
                const clear = document.getElementById('dropClear');
                const defaultTitle = title.textContent.trim();
                const defaultHint  = hint.textContent.trim();

                function refresh() {
                    const file = input.files[0];
                    if (file) {
                        title.textContent = file.name;
                        hint.textContent  = (file.size / 1024 / 1024).toFixed(2) + ' Mo';
                        zone.classList.add('border-green-600', 'bg-green-50/40');
                        clear.classList.remove('hidden');
                    } else {
                        title.textContent = defaultTitle;
                        hint.textContent  = defaultHint;
                        zone.classList.remove('border-green-600', 'bg-green-50/40');
                        clear.classList.add('hidden');
                    }
                }

                input.addEventListener('change', refresh);
                clear.addEventListener('click', (e) => {
                    e.preventDefault();
                    input.value = '';
                    refresh();
                });

                ['dragenter', 'dragover'].forEach(ev =>
                    input.addEventListener(ev, () => zone.classList.add('border-green-600', 'bg-green-50/40')));
                ['dragleave', 'drop'].forEach(ev =>
                    input.addEventListener(ev, () => { if (!input.files[0]) zone.classList.remove('border-green-600', 'bg-green-50/40'); }));
            })();
        </script>
    @endpush

</x-app-layout>
