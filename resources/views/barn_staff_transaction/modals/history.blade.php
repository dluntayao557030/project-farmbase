@push('styles')
<style>
    .history-modal { max-width: 540px; }

    .history-header {
        background: var(--green-main);
        color: #fff;
        padding: 1rem 1.5rem;
        font-family: var(--font);
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .history-list {
        max-height: 420px;
        overflow-y: auto;
        padding: 0.6rem 0.8rem;
    }

    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.8rem;
        padding: 0.9rem 1rem;
        margin-bottom: 0.4rem;
        background: #fff;
        border-radius: 8px;
        border: 1px solid var(--green-border);
        transition: background 0.15s, border-color 0.15s;
    }

    .history-item:hover {
        background: #f8fbf4;
        border-color: var(--green-mid);
    }

    .history-info { flex: 1; min-width: 0; }

    .history-supply {
        font-family: var(--font);
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.88rem;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .history-detail {
        font-size: 0.75rem;
        color: #777;
        line-height: 1.4;
    }

    .history-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.3rem;
        flex-shrink: 0;
    }

    .history-type {
        font-family: var(--font);
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.72rem;
        white-space: nowrap;
    }

    .history-in  { background: #e8f5e0; color: #2e7d32; }
    .history-out { background: #ffebee; color: #c62828; }

    .history-quantity {
        font-family: var(--font);
        font-size: 1.1rem;
        font-weight: 800;
    }

    .history-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #aaa;
        font-size: 0.9rem;
        line-height: 1.7;
    }

    @media (max-width: 576px) {
        .history-modal { max-width: 100%; }
        .history-list  { max-height: 60vh; }

        .history-item {
            flex-direction: column;
            gap: 0.6rem;
        }

        .history-right {
            flex-direction: row;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endpush

<div class="fb-modal-backdrop" id="historyModal">
    <div class="fb-modal history-modal">
        <div class="history-header">
             My Transaction History
        </div>

        <div class="history-list" id="historyList">
            <!-- Populated by JS -->
        </div>

        <div class="modal-footer-btns" style="padding:0.9rem 1.5rem 1.2rem; border-top:1px solid var(--green-border);">
            <button class="btn-modal-back" style="flex:1;" onclick="closeModal('historyModal')">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openHistoryModal() {
        openModal('historyModal');
        loadHistory();
    }

    function loadHistory() {
        const list = document.getElementById('historyList');
        list.innerHTML = `<div class="history-empty">⏳ Loading your transactions...</div>`;

        fetch('{{ route("transactions.historyData") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => renderHistory(data))
        .catch(() => {
            list.innerHTML = `<div class="history-empty">❌ Failed to load history.<br><span style="font-size:0.8rem;">Please try again.</span></div>`;
        });
    }

    function renderHistory(transactions) {
        const list = document.getElementById('historyList');

        if (!transactions || transactions.length === 0) {
            list.innerHTML = `<div class="history-empty">You haven't made any transactions yet.<br>Use <strong>Stock In</strong> or <strong>Stock Out</strong> to get started.</div>`;
            return;
        }

        list.innerHTML = transactions.map(txn => {
            const isIn = txn.transaction_type === 'stock_in';
            const typeClass = isIn ? 'history-in' : 'history-out';
            const typeText = isIn ? 'STOCK IN' : 'STOCK OUT';
            const qtyColor = isIn ? '#2e7d32' : '#c62828';
            const sign = isIn ? '+' : '−';

            let supplierHtml = '';
            if (isIn && txn.supplier_name) {
                supplierHtml = `<div class="history-detail" style="color:#2e7d32;">Supplier: ${txn.supplier_name}</div>`;
            }

            let costHtml = '';
            if (isIn && txn.unit_cost) {
                costHtml = `<div class="history-detail" style="color:#b08000;">Unit Cost: ₱${parseFloat(txn.unit_cost).toFixed(2)}</div>`;
            }

            return `
                <div class="history-item">
                    <div class="history-info">
                        <div class="history-supply">${txn.supply_name}</div>
                        <div class="history-detail">${txn.created_at}</div>
                        ${supplierHtml}
                        ${costHtml}
                        <div class="history-detail" style="font-style:italic;">${txn.remarks || 'No remarks'}</div>
                    </div>
                    <div class="history-right">
                        <span class="history-type ${typeClass}">${typeText}</span>
                        <span class="history-quantity" style="color:${qtyColor};">${sign}${txn.quantity}</span>
                    </div>
                </div>`;
        }).join('');
    }
</script>
@endpush