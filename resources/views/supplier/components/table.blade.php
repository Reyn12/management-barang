<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr>
                    <th class="w-24 px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Supplier</th>
                    <th class="w-44 px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Supplier</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No Telp</th>
                    <th class="w-64 px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" x-data="{ 
                formatDate(date) {
                    return date ? date.split('T')[0] : '';
                }
            }"> 
                <template x-for="supplier in filteredSuppliers()" :key="supplier.id_supplier">
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900" x-text="supplier.id_supplier"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900" x-text="supplier.nama_supplier"></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500" x-text="supplier.alamat"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500" x-text="supplier.no_telp"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500" x-text="supplier.email"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500" x-text="formatDate(supplier.created_at)"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <button @click="editingSupplier = {...supplier}; showEditModal = true" 
                                    class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form @submit.prevent="
                                    Swal.fire({
                                        title: 'Yakin nih mau hapus?',
                                        text: 'Data supplier akan dihapus permanen!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#EF4444',
                                        cancelButtonColor: '#6B7280',
                                        confirmButtonText: 'Ya, hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            fetch(`/supplier/${supplier.id_supplier}`, {
                                                method: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if(data.success) {
                                                    suppliers = suppliers.filter(s => s.id_supplier != supplier.id_supplier);
                                                    Swal.fire(
                                                        'Terhapus!',
                                                        'Supplier berhasil dihapus.',
                                                        'success'
                                                    );
                                                }
                                            });
                                        }
                                    })" 
                                class="inline">
                                    <button type="submit" 
                                        class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>