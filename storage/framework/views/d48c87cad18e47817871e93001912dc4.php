<?php $__env->startSection('title', $customer['name']); ?>
<?php $__env->startSection('topbar-title', $customer['name']); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('customers.edit', $customer['_id'])); ?>" class="btn btn-sm btn-outline-white">Edit</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $months    = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $prevMonth = $month == 1  ? 12 : $month - 1;
    $prevYear  = $month == 1  ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1  : $month + 1;
    $nextYear  = $month == 12 ? $year + 1 : $year;
    $customerId = $customer['_id'];
?>


<div class="month-picker">
    <a href="<?php echo e(route('customers.show', ['id'=>$customerId,'month'=>$prevMonth,'year'=>$prevYear])); ?>" class="month-arrow">‹</a>
    <span class="month-label"><?php echo e($months[$month-1]); ?> <?php echo e($year); ?></span>
    <a href="<?php echo e(route('customers.show', ['id'=>$customerId,'month'=>$nextMonth,'year'=>$nextYear])); ?>" class="month-arrow">›</a>
</div>


<div class="rate-bar">
    <form method="POST" action="<?php echo e(route('rate.save', $customerId)); ?>" class="rate-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="month" value="<?php echo e($month); ?>">
        <input type="hidden" name="year"  value="<?php echo e($year); ?>">
        <label class="rate-label">Rate (₹/Fat)</label>
        <input type="number" name="rate" class="rate-input" step="0.01" min="0"
               value="<?php echo e(old('rate', $rate)); ?>" placeholder="0.00">
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
    </form>
</div>


<div class="summary-card">
    <div class="summary-row"><span>Total Liter</span><strong><?php echo e(number_format($totalLiter,1)); ?> L</strong></div>
    <div class="summary-row"><span>Total Fat</span><strong><?php echo e(number_format($totalFat,1)); ?></strong></div>
    <div class="summary-row"><span>Avg Fat</span><strong><?php echo e(number_format($avgFat,2)); ?></strong></div>
    <div class="summary-row"><span>Rate</span><strong>₹<?php echo e(number_format($rate,2)); ?>/Fat</strong></div>
    <div class="summary-row summary-total"><span>Total Amount</span><strong>₹<?php echo e(number_format($totalAmount,2)); ?></strong></div>
</div>


<div class="action-row">
    <a href="<?php echo e(route('bill.show', ['customerId'=>$customerId,'month'=>$month,'year'=>$year])); ?>"
       class="btn btn-success btn-block">📄 View Bill</a>
</div>


<div class="table-wrap">
    <table class="milk-table">
        <thead>
            <tr><th>Date</th><th>M.L</th><th>M.F</th><th>E.L</th><th>E.F</th><th>₹</th><th></th></tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $fullMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $mL  = $row['morning_liter'];
                $mF  = $row['morning_fat'];
                $eL  = $row['evening_liter'];
                $eF  = $row['evening_fat'];
                $amt = ($mL * $mF + $eL * $eF) * $rate;
                $day = (int) substr($row['date'], 8, 2);
                $hasData = $mL > 0 || $eL > 0;
            ?>
            <tr class="<?php echo e($hasData ? 'row-active' : 'row-empty'); ?>"
                onclick="openEntry('<?php echo e($row['date']); ?>', <?php echo e($mL); ?>, <?php echo e($mF); ?>, <?php echo e($eL); ?>, <?php echo e($eF); ?>, '<?php echo e($row['id'] ?? ''); ?>')"
                style="cursor:pointer;">
                <td class="cell-date"><?php echo e($day); ?></td>
                <td><?php echo e($mL > 0 ? $mL : '—'); ?></td>
                <td><?php echo e($mF > 0 ? $mF : '—'); ?></td>
                <td><?php echo e($eL > 0 ? $eL : '—'); ?></td>
                <td><?php echo e($eF > 0 ? $eF : '—'); ?></td>
                <td class="cell-amt"><?php echo e($amt > 0 ? '₹'.number_format($amt,1) : '—'); ?></td>
                <td class="cell-edit">✏️</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr class="tfoot-row">
                <td>Total</td>
                <td><?php echo e(number_format($totals['mL'],1)); ?></td>
                <td><?php echo e(number_format($totals['mF'],1)); ?></td>
                <td><?php echo e(number_format($totals['eL'],1)); ?></td>
                <td><?php echo e(number_format($totals['eF'],1)); ?></td>
                <td>₹<?php echo e(number_format($totalAmount,0)); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>


<div class="modal-backdrop" id="entryModal" onclick="if(event.target===this)closeModal()">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Milk Entry</h3>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <form method="POST" id="entryForm" action="<?php echo e(route('milk.store', $customerId)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="month" value="<?php echo e($month); ?>">
            <input type="hidden" name="year"  value="<?php echo e($year); ?>">
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" id="entryDate" class="form-input" required>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Morning Liter</label>
                    <input type="number" name="morning_liter" id="mL" class="form-input" step="0.1" min="0" placeholder="0.0">
                </div>
                <div class="form-group">
                    <label class="form-label">Morning Fat</label>
                    <input type="number" name="morning_fat" id="mF" class="form-input" step="0.1" min="0" placeholder="0.0">
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Evening Liter</label>
                    <input type="number" name="evening_liter" id="eL" class="form-input" step="0.1" min="0" placeholder="0.0">
                </div>
                <div class="form-group">
                    <label class="form-label">Evening Fat</label>
                    <input type="number" name="evening_fat" id="eF" class="form-input" step="0.1" min="0" placeholder="0.0">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="saveBtn">Save Entry</button>
        </form>
    </div>
</div>

<button class="fab" onclick="openEntry(null,0,0,0,0,null)" title="Add milk entry">+</button>

<script>
const customerId = '<?php echo e($customerId); ?>';
const baseStoreUrl = '<?php echo e(route("milk.store", $customerId)); ?>';

function openEntry(date, mL, mF, eL, eF, id) {
    document.getElementById('entryDate').value = date || new Date().toISOString().slice(0,10);
    document.getElementById('mL').value = mL || '';
    document.getElementById('mF').value = mF || '';
    document.getElementById('eL').value = eL || '';
    document.getElementById('eF').value = eF || '';

    if (id) {
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('entryForm').action = `/customers/${customerId}/milk/${id}`;
        document.getElementById('modalTitle').textContent = 'Edit Entry — ' + date;
        document.getElementById('saveBtn').textContent = 'Update Entry';
    } else {
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('entryForm').action = baseStoreUrl;
        document.getElementById('modalTitle').textContent = 'Add Milk Entry';
        document.getElementById('saveBtn').textContent = 'Save Entry';
    }
    document.getElementById('entryModal').classList.add('show');
}

function closeModal() {
    document.getElementById('entryModal').classList.remove('show');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/customers/show.blade.php ENDPATH**/ ?>