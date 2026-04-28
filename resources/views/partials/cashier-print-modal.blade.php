<!-- Print Date Modal -->
<dialog id="printDateModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Print Daily Transaction Report</h3>
        <form action="{{ route('accounting.print.daily-transactions') }}" method="GET" target="_blank">
            <div class="form-control">
                <label class="label"><span class="label-text">Select Date</span></label>
                <input type="date" name="date" class="input input-bordered w-full" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('printDateModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Print</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
