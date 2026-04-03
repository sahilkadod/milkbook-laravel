<?php $__env->startSection('title', 'Bill — '.$customer->name); ?>
<?php $__env->startSection('topbar-title', 'Monthly Bill'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('bill.pdf', ['customerId'=>$customer->id,'month'=>$month,'year'=>$year])); ?>"
       target="_blank" class="btn btn-sm btn-outline-white">🖨 Print / PDF</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    $prevMonth  = $month == 1  ? 12 : $month - 1;
    $prevYear   = $month == 1  ? $year - 1 : $year;
    $nextMonth  = $month == 12 ? 1  : $month + 1;
    $nextYear   = $month == 12 ? $year + 1 : $year;
    $activeRows = array_filter($rows, fn($r) => $r['mL'] > 0 || $r['eL'] > 0);
?>


<div class="month-picker">
    <a href="<?php echo e(route('bill.show', ['customerId'=>$customer->id,'month'=>$prevMonth,'year'=>$prevYear])); ?>" class="month-arrow">‹</a>
    <span class="month-label"><?php echo e($monthNames[$month-1]); ?> <?php echo e($year); ?></span>
    <a href="<?php echo e(route('bill.show', ['customerId'=>$customer->id,'month'=>$nextMonth,'year'=>$nextYear])); ?>" class="month-arrow">›</a>
</div>


<div class="bill-summary-grid">
    <div class="bill-card accent">
        <div class="bill-card-label">Rate / Fat</div>
        <div class="bill-card-val">₹<?php echo e(number_format($rate,2)); ?></div>
    </div>
    <div class="bill-card accent">
        <div class="bill-card-label">Total Amount</div>
        <div class="bill-card-val">₹<?php echo e(number_format($totalAmount,0)); ?></div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Liters</div>
        <div class="bill-card-val"><?php echo e(number_format($totalLiter,2)); ?> L</div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Fat</div>
        <div class="bill-card-val"><?php echo e(number_format($totalFat,2)); ?></div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Days</div>
        <div class="bill-card-val"><?php echo e($totalDays); ?> days</div>
    </div>
</div>


<div class="bill-subtotals">
    <div class="subtotal-block">
        <span class="subtotal-icon">🌅</span>
        <div>
            <div class="subtotal-label">Morning</div>
            <div class="subtotal-val"><?php echo e(number_format($totMorningLiter,2)); ?> L &nbsp;|&nbsp; <?php echo e(number_format($totMorningFat,2)); ?> F</div>
        </div>
    </div>
    <div class="subtotal-divider"></div>
    <div class="subtotal-block">
        <span class="subtotal-icon">🌇</span>
        <div>
            <div class="subtotal-label">Evening</div>
            <div class="subtotal-val"><?php echo e(number_format($totEveningLiter,2)); ?> L &nbsp;|&nbsp; <?php echo e(number_format($totEveningFat,2)); ?> F</div>
        </div>
    </div>
</div>


<div class="table-wrap" style="margin-top:16px;">
    <table class="milk-table bill-table">
        <thead>
            <tr>
                <th>Date</th><th>M.Lt</th><th>M.Fat</th><th>E.Lt</th><th>E.Fat</th><th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($row['mL'] > 0 || $row['eL'] > 0): ?>
                <tr>
                    <td class="cell-date"><?php echo e($row['day']); ?></td>
                    <td><?php echo e($row['mL'] > 0 ? $row['mL'] : '—'); ?></td>
                    <td><?php echo e($row['mF'] > 0 ? $row['mF'] : '—'); ?></td>
                    <td><?php echo e($row['eL'] > 0 ? $row['eL'] : '—'); ?></td>
                    <td><?php echo e($row['eF'] > 0 ? $row['eF'] : '—'); ?></td>
                    <td class="cell-amt"><?php echo e($row['dayTotal'] > 0 ? '₹'.number_format($row['dayTotal'],2) : '—'); ?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr class="tfoot-row">
                <td>Total</td>
                <td><?php echo e(number_format($totMorningLiter,1)); ?></td>
                <td><?php echo e(number_format($totMorningFat,1)); ?></td>
                <td><?php echo e(number_format($totEveningLiter,1)); ?></td>
                <td><?php echo e(number_format($totEveningFat,1)); ?></td>
                <td class="cell-amt">₹<?php echo e(number_format($totalAmount,0)); ?></td>
            </tr>
        </tfoot>
    </table>
</div>


<?php if(count($activeRows) > 0): ?>
<div class="chart-wrap">
    <div class="chart-title">📊 Daily Liters & Fat</div>
    <canvas id="billChart" height="180"></canvas>
    <script>
    (function() {
        const rows = <?php echo json_encode(array_values($activeRows), 15, 512) ?>;
        const labels = rows.map(r => parseInt(r.dateStr.slice(8)));
        const liters = rows.map(r => parseFloat(r.mL) + parseFloat(r.eL));
        const fats   = rows.map(r => parseFloat(r.mF) + parseFloat(r.eF));

        const ctx = document.getElementById('billChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total Liters',
                        data: liters,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46,125,50,0.1)',
                        borderWidth: 2.5,
                        pointRadius: 3,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'yL',
                    },
                    {
                        label: 'Total Fat',
                        data: fats,
                        borderColor: '#E65100',
                        backgroundColor: 'rgba(230,81,0,0.05)',
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.3,
                        borderDash: [6,3],
                        yAxisID: 'yF',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    yL: { position: 'left',  title: { display: true, text: 'Liters', color: '#2E7D32' } },
                    yF: { position: 'right', title: { display: true, text: 'Fat',    color: '#E65100' }, grid: { drawOnChartArea: false } },
                }
            }
        });
    })();
    </script>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/bills/show.blade.php ENDPATH**/ ?>