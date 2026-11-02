<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tableau de bord') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Bienvenue, {{ Auth::user()->name }} 👋 — {{ now()->translatedFormat('l d F Y') }}
                </p>
            </div>
            <a href="{{ route('ouvriers.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                style="background: #1a6b3a;">
                <i class="fa-solid fa-plus"></i>
                Nouvel ouvrier
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stats row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: #e6f4ed;">
                        <i class="fa-solid fa-users text-base" style="color: #1a6b3a;"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalOuvriers }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total ouvriers</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-50">
                        <i class="fa-solid fa-person text-base text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalHommes }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Hommes</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-pink-50">
                        <i class="fa-solid fa-person-dress text-base text-pink-400"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalFemmes }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Femmes</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50">
                        <i class="fa-solid fa-file-lines text-base text-amber-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900">11</p>
                        <p class="text-xs text-gray-400 mt-0.5">Documents générés</p>
                    </div>
                </div>

            </div>

            {{-- Quick actions + recent workers --}}
            <div class="grid grid-cols-3 gap-6">

                {{-- Recent workers --}}
                <div class="col-span-2 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">Ouvriers récents</h3>
                        <a href="{{ route('ouvriers.index') }}"
                            class="text-xs font-medium text-green-700 hover:text-green-900 transition-colors">
                            Voir tout <i class="fa-solid fa-arrow-right ml-0.5"></i>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($recentOuvriers as $ouvrier)
                            <div class="flex items-center gap-4 px-5 py-3.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0"
                                    style="background: #1a6b3a;">
                                    {{ strtoupper(substr($ouvrier->full_name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $ouvrier->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $ouvrier->his_work }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($ouvrier->gender === 'femme')
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-pink-50 text-pink-700">Femme</span>
                                    @else
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">Homme</span>
                                    @endif
                                    <span class="font-mono text-xs text-gray-400">{{ strtoupper($ouvrier->cin) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-users text-3xl opacity-20 mb-3 block"></i>
                                <p class="text-sm">Aucun ouvrier pour le moment.</p>
                                <a href="{{ route('ouvriers.create') }}"
                                    class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 text-sm font-medium text-white rounded-lg"
                                    style="background: #1a6b3a;">
                                    Ajouter le premier
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="space-y-4">

                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Actions rapides</h3>
                        <div class="space-y-2">
                            <a href="{{ route('ouvriers.create') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-50 group-hover:bg-green-100 transition-colors">
                                    <i class="fa-solid fa-user-plus text-sm" style="color: #1a6b3a;"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Nouvel ouvrier</p>
                                    <p class="text-xs text-gray-400">Ajouter un saisonnier</p>
                                </div>
                            </a>

                            <a href="{{ route('documents.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-100 hover:border-amber-200 hover:bg-amber-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-50 group-hover:bg-amber-100 transition-colors">
                                    <i class="fa-solid fa-file-arrow-down text-sm text-amber-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Générer documents</p>
                                    <p class="text-xs text-gray-400">Lettre & compte rendu</p>
                                </div>
                            </a>

                            <a href="{{ route('ouvriers.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
                                    <i class="fa-solid fa-list text-sm text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Liste complète</p>
                                    <p class="text-xs text-gray-400">Voir tous les ouvriers</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Gender breakdown --}}
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Répartition</h3>
                        @if($totalOuvriers > 0)
                            <div class="space-y-2.5">
                                <div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Hommes</span>
                                        <span>{{ $totalOuvriers > 0 ? round($totalHommes / $totalOuvriers * 100) : 0 }}%</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-400 rounded-full"
                                            style="width: {{ $totalOuvriers > 0 ? round($totalHommes / $totalOuvriers * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Femmes</span>
                                        <span>{{ $totalOuvriers > 0 ? round($totalFemmes / $totalOuvriers * 100) : 0 }}%</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-pink-400 rounded-full"
                                            style="width: {{ $totalOuvriers > 0 ? round($totalFemmes / $totalOuvriers * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-2">Aucune donnée disponible.</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
