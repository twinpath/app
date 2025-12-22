@extends('layouts.app')

@section('content')
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Root CA Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your Root and Intermediate Certificates.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-theme-xs rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Common Name</th>
                        <th scope="col" class="px-6 py-3">Serial Number</th>
                        <th scope="col" class="px-6 py-3">Valid From</th>
                        <th scope="col" class="px-6 py-3">Valid To</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ ucfirst(str_replace('_', ' ', $cert->ca_type)) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $cert->common_name }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                {{ $cert->serial_number }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($cert->valid_from)->format('Y-m-d H:i') }}
                            </td>
                             <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($cert->valid_to)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($cert->status === 'valid')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Valid</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.root-ca.renew', $cert->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to renew this certificate?');">
                                    @csrf
                                    <input type="hidden" name="days" value="3650">
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Renew (10 Years)
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                         <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="7" class="px-6 py-4 text-center">
                                No Root CA certificates found. 
                                <span class="text-gray-400">(Run Setup first)</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
