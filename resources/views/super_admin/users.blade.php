<x-app-layout>
    <div x-data="{ showModal: false }">
        
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">User Management</h2>
                <p class="text-gray-500 mt-1">Manage access for Barangay Admins.</p>
            </div>
            <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Admin
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Existing Admins Directory</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Name / Email</th>
                            <th class="px-6 py-3">Barangay</th>
                            <th class="px-6 py-3">Joined Date</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $admin->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                        {{ $admin->barangay }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    {{ $admin->created_at->format('M d, Y') }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($admin->email_verified_at)
                                        @if($admin->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Deactivated</span>
                                        @endif
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending Verification</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('super.toggle_status', $admin->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        
                                        @if($admin->is_active)
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-medium text-xs border border-green-200 bg-green-50 hover:bg-green-100 px-3 py-1 rounded transition">
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No admins found. Click "Add New Admin" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Add New Barangay Admin
                                </h3>
                                <div class="mt-4">
                                    <form id="addAdminForm" action="{{ route('super.create_admin') }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">
                                                        First Name <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="first_name" 
                                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                                                        placeholder="Ex. Juan" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">
                                                        Last Name <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="last_name" 
                                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                                                        placeholder="Ex. Dela Cruz" required>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Middle Name <span class="text-gray-400 text-xs">(Optional)</span>
                                                </label>
                                                <input type="text" name="middle_name" 
                                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                                                    placeholder="Ex. Santos">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Email Address <span class="text-red-500">*</span>
                                                </label>
                                                <input type="email" name="email" 
                                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                                                    placeholder="Ex. juan.delacruz@gmail.com" required>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Barangay <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="barangay" 
                                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                                                    placeholder="Ex. Brgy. Poblacion" required>
                                            </div>

                                            <div x-data="{ showPassword: false }">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Password <span class="text-red-500">*</span>
                                                </label>
                                                
                                                <div class="relative">
                                                    <input :type="showPassword ? 'text' : 'password'" 
                                                        name="password" 
                                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 pr-10" 
                                                        placeholder="********" 
                                                        required>
                                                    
                                                    <button type="button" 
                                                            @click="showPassword = !showPassword" 
                                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none mt-1">
                                                        
                                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>

                                                        <svg x-show="showPassword" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Must be at least 8 characters with 1 uppercase, 1 symbol (@$!%*?&), and 1 number.
                                                </p>

                                                @error('password')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button onclick="document.getElementById('addAdminForm').submit()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Create Account
                        </button>
                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>