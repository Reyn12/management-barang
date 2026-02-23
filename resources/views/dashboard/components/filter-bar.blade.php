<div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <div class="flex items-center gap-2">
                <i class="far fa-calendar text-gray-500"></i>
                <label class="text-sm text-gray-600">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->subMonths(6)->format('Y-m-d')) }}"
                    class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}"
                    class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <button type="submit" class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                <i class="fas fa-filter"></i>
                <span>Terapkan</span>
            </button>
        </form>
    </div>
    <div class="flex items-center gap-4">
        <button onclick="refreshPage(this)" class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
            <i class="fas fa-sync-alt"></i>
            <span>Refresh</span>
        </button>
    </div>
</div>

<script>
function refreshPage(button) {
    const icon = button.querySelector('i');
    icon.classList.add('animate-spin');
    button.disabled = true;
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function showCustomizeAlert() {
    Swal.fire({
        title: 'Coming Soon!',
        text: 'Customize feature will be available soon',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
}

</script>