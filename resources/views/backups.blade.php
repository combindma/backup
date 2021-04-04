@extends('dashui::layouts.app')
@section('title', 'Sauvegardes')
@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-4 border-b border-gray-200">
            <div class="pb-8 sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
                        Sauvegardes
                    </h1>
                </div>
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <a href="{{ route('backup::backups.create') }}" class="btn">
                        Créer une sauvegarde
                    </a>
                </div>
            </div>
        </div>
        @include('dashui::components.alert')
        @if (empty($backups))
            @component('dashui::components.blank-state')
                @slot('icon')
                    <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                @endslot
                @slot('heading')
                    Liste vide
                @endslot
                    Aucune sauvegarde trouvée
            @endcomponent
        @else
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Emplacement
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Taille de fichier
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($backups as $k => $b)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $k+1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $b['disk'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ round((int)$b['file_size']/1048576, 2).' MB' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            @if ($b['download'])
                                                <a href="{{ route('backup::backups.download') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}" target="_blank" class="text-primary-600 hover:text-primary-900">Télécharger</a>
                                            @endif
                                            <form action="{{ route('backup::backups.delete') }}?disk={{ $b['disk'] }}&file_name={{ urlencode($b['file_name']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="text-red-600 hover:text-red-900" href="javascript:" onclick='confirm("Etes-vous sûr de vouloir supprimer cette sauvegarde ?") && parentNode.submit();'>
                                                    Supprimer
                                                </a>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
