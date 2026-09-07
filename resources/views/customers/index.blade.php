<x-layouts.app title="Customers">

    <x-ui.breadcrumb :items="[
        ['label' => 'Customers'],
    ]" />

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm" x-data="{ allChecked: false }">
        <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
            <div class="hidden md:flex relative w-full max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#6b7280]">
                    <x-lucide-search class="h-4 w-4" />    
                </div>

                <input 
                    type="text" 
                    placeholder="Search customer" 
                    class="w-full bg-[#ffffff] border border-[#d1d5db] rounded-lg pl-11 pr-4 py-2 text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-px focus:ring-primary-500 transition-all"
                >
            </div>
            
            <a 
                href="{{ route('customers.create') }}"
                class="bg-primary-500 hover:bg-primary-600 h-10 w-10 flex items-center justify-center rounded-xl transition-colors"
            >
                <x-lucide-plus class="h-6 w-6 text-white" />
            </a>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Code #</th>
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Phone</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 font-medium text-gray-700">
                            {{ $customer->customer_code }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            <a href="{{ $customer->email }}" target="_blank" class="text-blue-500 underline">
                                {{ $customer->email }}
                            </a>
                        </td>

                        <td class="px-5 py-3 text-gray-500">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @php
                                $badgeClass = match ($customer->status) {
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-600',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3 text-xs font-medium">
                                <a 
                                    href="{{ route('customers.show', $customer->id) }}"
                                    class="text-gray-600 bg-white hover:bg-gray-300 border border-gray-300 rounded-lg font-medium p-2 inline-flex space-x-1.5 items-center"
                                >
                                    <x-lucide-eye class="h-5 w-5" />
                                    <span class="hidden md:inline-block">View</span>
                                </a>

                                <a 
                                    href="{{ route('customers.edit', $customer->id) }}"
                                    class="text-gray-600 bg-white hover:bg-gray-300 border border-gray-300 rounded-lg font-medium p-2 inline-flex space-x-1.5 items-center"
                                >
                                    <x-lucide-square-pen class="h-5 w-5" />
                                    <span class="hidden md:inline-block">Edit</span>
                                </a>

                                <form 
                                    method="POST" 
                                    action="{{ route('customers.destroy', $customer->id) }}"
                                    onsubmit="return confirm('Delete customer {{ $customer->customer_code }}?')" 
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-500 hover:text-white bg-white hover:bg-red-500 border border-gray-300 rounded-lg font-medium p-2 inline-flex space-x-1.5 items-center">
                                        <x-lucide-trash-2 class="h-5 w-5" />
                                        <span class="hidden md:inline-block">Edit</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-400">
                            No customers yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.app>