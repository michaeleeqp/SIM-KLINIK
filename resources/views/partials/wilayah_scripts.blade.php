<script>
// Reusable Wilayah dropdowns script
const BASE_URL_WILAYAH = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

const initialProv = {!! json_encode($initialProv ?? null) !!};
const initialKab = {!! json_encode($initialKab ?? null) !!};
const initialKec = {!! json_encode($initialKec ?? null) !!};
const initialDesa = {!! json_encode($initialDesa ?? null) !!};

async function fetchAndFillDropdown(url, dropdownId, placeholderText = null) {
    const selectElement = document.getElementById(dropdownId);
    if (!selectElement) return;
    selectElement.disabled = true;
    selectElement.innerHTML = `<option value=\"\" disabled selected hidden>Memuat ${placeholderText || 'data'}...</option>`;
    const nextDropdowns = ['provinsi','kabupaten','kecamatan','desa'];
    const currentIndex = nextDropdowns.indexOf(dropdownId);
    if (currentIndex >= 0) {
        for (let i = currentIndex + 1; i < nextDropdowns.length; i++) {
            const nextSelect = document.getElementById(nextDropdowns[i]);
            if (nextSelect) {
                const name = nextDropdowns[i].charAt(0).toUpperCase() + nextDropdowns[i].slice(1);
                nextSelect.innerHTML = `<option value=\"\" disabled selected hidden>Pilih ${name}</option><option value=\"-\">-</option>`;
                nextSelect.disabled = true;
            }
        }
    }

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        selectElement.innerHTML = `<option value=\"\" disabled selected hidden>${placeholderText || 'Pilih'}</option>`;
        if (dropdownId !== 'provinsi') selectElement.innerHTML += '<option value=\"-\">-</option>';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });
        selectElement.disabled = false;
    } catch (error) {
        console.error(`Error fetching data for ${dropdownId}:`, error);
        selectElement.innerHTML = `<option value=\"\" disabled selected hidden>Gagal memuat data</option>`;
    }
}

// Initialize and handle chaining + preselection
document.addEventListener('DOMContentLoaded', function() {
    (async function initWilayah() {
        await fetchAndFillDropdown(`${BASE_URL_WILAYAH}provinces.json`, 'provinsi', 'Pilih Provinsi');
        const provSelect = document.getElementById('provinsi');
        if (provSelect && initialProv) {
            provSelect.value = String(initialProv);
            provSelect.dispatchEvent(new Event('change'));
        }
    })();

    const provSelect = document.getElementById('provinsi');
    if (provSelect) provSelect.addEventListener('change', async function() {
        const selectedProvinceId = this.value;
        if (selectedProvinceId && selectedProvinceId !== '-') {
            await fetchAndFillDropdown(`${BASE_URL_WILAYAH}regencies/${selectedProvinceId}.json`, 'kabupaten', 'Pilih Kabupaten');
            const kabSelect = document.getElementById('kabupaten');
            if (kabSelect && initialKab) {
                kabSelect.value = String(initialKab);
                kabSelect.dispatchEvent(new Event('change'));
            }
        }
    });

    const kabSelect = document.getElementById('kabupaten');
    if (kabSelect) kabSelect.addEventListener('change', async function() {
        const selectedRegencyId = this.value;
        if (selectedRegencyId && selectedRegencyId !== '-') {
            await fetchAndFillDropdown(`${BASE_URL_WILAYAH}districts/${selectedRegencyId}.json`, 'kecamatan', 'Pilih Kecamatan');
            const kecSelect = document.getElementById('kecamatan');
            if (kecSelect && initialKec) {
                kecSelect.value = String(initialKec);
                kecSelect.dispatchEvent(new Event('change'));
            }
        }
    });

    const kecSelect = document.getElementById('kecamatan');
    if (kecSelect) kecSelect.addEventListener('change', async function() {
        const selectedDistrictId = this.value;
        if (selectedDistrictId && selectedDistrictId !== '-') {
            await fetchAndFillDropdown(`${BASE_URL_WILAYAH}villages/${selectedDistrictId}.json`, 'desa', 'Pilih Desa');
            const desaSelect = document.getElementById('desa');
            if (desaSelect && initialDesa) {
                desaSelect.value = String(initialDesa);
            }
        }
    });
});
</script>
