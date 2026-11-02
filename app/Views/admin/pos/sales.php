<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <!-- BAGIAN KIRI: DAFTAR PRODUK & LAYANAN -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Pilih Produk / Layanan</h5>
                    <div class="input-group w-50">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchItem" class="form-control bg-light border-0 py-2"
                            placeholder="Cari item...">
                    </div>
                </div>

                <!-- Tab Kategori: Produk & Layanan -->
                <ul class="nav nav-pills mb-3 gap-2" id="posTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 fw-semibold btn-sm" id="tab-product"
                            data-bs-toggle="pill" data-bs-target="#content-product" type="button" role="tab"
                            style="background: #111; color: #d4af37;">Produk</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-semibold btn-sm text-dark bg-white border"
                            id="tab-service" data-bs-toggle="pill" data-bs-target="#content-service" type="button"
                            role="tab">Layanan Cukur</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="posTabContent">
                    <!-- KONTEN PRODUK -->
                    <div class="tab-pane fade show active" id="content-product" role="tabpanel">
                        <div class="row g-3 overflow-auto" style="max-height: 500px;" id="productList">
                            <?php if (empty($products)): ?>
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada produk tersedia.
                            </div>
                            <?php else: ?>
                            <?php foreach ($products as $p): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card border shadow-sm rounded-4 h-100 product-card cursor-pointer"
                                    onclick="addToCart(<?= $p['id'] ?>, '<?= $p['product_name'] ?>', <?= $p['selling_price'] ?>, 'product')"
                                    style="transition: all 0.2s ease; background: #fdfdfd;">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-warning bg-opacity-10 text-dark fw-semibold mb-2"
                                                style="font-size: 10px;">Stok: <?= $p['stock'] ?></span>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate"><?= $p['product_name'] ?>
                                            </h6>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-success fs-6">Rp
                                                <?= number_format($p['selling_price'], 0, ',', '.') ?></span>
                                            <button
                                                class="btn btn-sm rounded-circle text-dark shadow-sm d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- KONTEN LAYANAN -->
                    <div class="tab-pane fade" id="content-service" role="tabpanel">
                        <div class="row g-3 overflow-auto" style="max-height: 500px;">
                            <?php if (empty($services)): ?>
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bi bi-scissors fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada layanan tersedia.
                            </div>
                            <?php else: ?>
                            <?php foreach ($services as $s): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card border shadow-sm rounded-4 h-100 product-card cursor-pointer border-warning"
                                    onclick="openBarberModal(<?= $s['id'] ?>, '<?= $s['service_name'] ?? $s['name'] ?? 'Layanan' ?>', <?= $s['price'] ?>)"
                                    style="transition: all 0.2s ease; background: #fdfdfd;">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-dark text-warning fw-semibold mb-2"
                                                style="font-size: 10px;">Layanan</span>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate">
                                                <?= $s['service_name'] ?? $s['name'] ?? 'Layanan' ?></h6>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-success fs-6">Rp
                                                <?= number_format($s['price'], 0, ',', '.') ?></span>
                                            <button
                                                class="btn btn-sm rounded-circle text-dark shadow-sm d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BAGIAN KANAN: KERANJANG & CHECKOUT -->
    <div class="col-lg-4">
        <form action="<?= base_url('admin/pos/store') ?>" method="post" id="posForm">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                <div
                    class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center border-0">
                    <h5 class="fw-bold mb-0 fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3" style="color: #d4af37;"></i> Keranjang Belanja
                    </h5>
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1" id="cartCount">0 Item</span>
                </div>

                <div class="card-body p-3 bg-light">
                    <!-- Input Nama Customer -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Customer</label>
                        <input type="text" name="customer_name" class="form-control form-control-sm rounded-3 bg-white"
                            placeholder="Nama Pelanggan" required value="Umum">
                    </div>

                    <!-- Tabel Item Keranjang -->
                    <div class="table-responsive bg-white rounded-3 p-2 shadow-sm border border-light mb-3"
                        style="min-height: 200px; max-height: 250px; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0 small" id="cartTable">
                            <thead class="text-secondary border-bottom">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center" width="20%">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody id="cartItemsContainer">
                                <tr id="emptyCartRow">
                                    <td colspan="4" class="text-center py-4 text-muted">Keranjang masih kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Rincian Pembayaran -->
                    <div class="bg-white p-3 rounded-3 shadow-sm border border-light mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Total Belanja</span>
                            <span class="fw-bold text-dark fs-5" id="grandTotalText">Rp 0</span>
                        </div>
                        <input type="hidden" name="total_amount" id="grandTotalInput" value="0">

                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-secondary">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select form-select-sm rounded-3 bg-white"
                                required>
                                <option value="Cash">Tunai (Cash)</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer">Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Proses Bayar -->
                    <button type="submit" class="btn w-100 py-2 rounded-3 fw-bold text-dark shadow-sm"
                        style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">
                        <i class="bi bi-wallet2 me-1"></i> Proses Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL PILIH KAPSTER (KHUSUS LAYANAN) ================= -->
<div class="modal fade" id="selectBarberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3">Pilih Kapster / Barber</h6>
            <div class="mb-3">
                <label class="form-label small text-secondary">Nama Kapster</label>
                <select id="modal_barber_id" class="form-select form-select-sm rounded-3">
                    <?php if (!empty($barbers)): ?>
                    <?php foreach ($barbers as $b): ?>
                    <option value="<?= $b['id'] ?>"><?= $b['name'] ?? $b['username'] ?? 'Barber' ?></option>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <option value="1">Barber Utama</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light btn-sm w-50 fw-semibold"
                    data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-dark btn-sm w-50 fw-bold" style="background: #111;"
                    onclick="confirmAddService()">Pilih</button>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIKA KERANJANG POS -->
<script>
let cart = [];
let selectedServiceData = {};

function addToCart(id, name, price, type) {
    let existingItem = cart.find(item => item.id === id && item.item_type === type);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            item_type: type,
            quantity: 1,
            barber_id: null,
            commission: 0
        });
    }
    renderCart();
}

function openBarberModal(id, name, price) {
    selectedServiceData = {
        id: id,
        name: name,
        price: price
    };
    var myModal = new bootstrap.Modal(document.getElementById('selectBarberModal'));
    myModal.show();
}

function confirmAddService() {
    let barberSelect = document.getElementById('modal_barber_id');
    let barberId = barberSelect.value;
    let barberName = barberSelect.options[barberSelect.selectedIndex].text;

    let uniqueKey = 'srv_' + selectedServiceData.id + '_b_' + barberId;
    let existingItem = cart.find(item => item.cartKey === uniqueKey);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            cartKey: uniqueKey,
            id: selectedServiceData.id,
            name: selectedServiceData.name + ' (' + barberName + ')',
            price: selectedServiceData.price,
            item_type: 'service',
            quantity: 1,
            barber_id: barberId,
            commission: selectedServiceData.price * 0.3 // Contoh estimasi komisi 30%
        });
    }

    renderCart();

    var modalEl = document.getElementById('selectBarberModal');
    var modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}

function updateQty(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    let container = document.getElementById('cartItemsContainer');
    let countBadge = document.getElementById('cartCount');
    let grandTotalText = document.getElementById('grandTotalText');
    let grandTotalInput = document.getElementById('grandTotalInput');

    container.innerHTML = '';
    if (cart.length === 0) {
        container.innerHTML =
            `<tr id="emptyCartRow"><td colspan="4" class="text-center py-4 text-muted">Keranjang masih kosong</td></tr>`;
        countBadge.innerText = '0 Item';
        grandTotalText.innerText = 'Rp 0';
        grandTotalInput.value = 0;
        return;
    }

    let totalItems = 0;
    let grandTotal = 0;

    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity; // Diperbaiki dari error sebelumnya
        totalItems += item.quantity;
        grandTotal += subtotal;

        let barberInput = item.barber_id ?
            `<input type="hidden" name="items[${index}][barber_id]" value="${item.barber_id}"><input type="hidden" name="items[${index}][commission]" value="${item.commission}">` :
            '';

        container.innerHTML += `
                <tr>
                    <td>
                        <div class="fw-bold text-dark text-truncate" style="max-width: 120px;">${item.name}</div>
                        <div class="text-muted" style="font-size: 10px;">Rp ${item.price.toLocaleString('id-ID')}</div>
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][item_type]" value="${item.item_type}">
                        <input type="hidden" name="items[${index}][price]" value="${item.price}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                        <input type="hidden" name="items[${index}][name]" value="${item.name}">
                        ${barberInput}
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center">
                            <button type="button" class="btn btn-outline-secondary px-1 py-0" onclick="updateQty(${index}, -1)">-</button>
                            <span class="px-2 fw-semibold">${item.quantity}</span>
                            <button type="button" class="btn btn-outline-secondary px-1 py-0" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-semibold text-dark">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm text-danger p-0" onclick="removeItem(${index})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
    });

    countBadge.innerText = totalItems + ' Item';
    grandTotalText.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    grandTotalInput.value = grandTotal;
}
</script>

<style>
.product-card:hover {
    transform: translateY(-3px);
    border-color: #d4af37 !important;
    box-shadow: 0 .5rem 1rem rgba(212, 175, 55, 0.15) !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<?= $this->endSection() ?>