<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังบ้าน - CG Racing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> 
        body { font-family: 'Kanit', sans-serif; } 
        
        /* สร้าง Animation ลอยขึ้นมาแบบนุ่มนวล */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- ส่วนหัว Header (ใส่ Animation ลำดับที่ 1) -->
        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200 mb-6 animate-fade-in-up">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-800">จัดการข้อมูลแจ้งซ่อม</h2>
                    <p class="text-sm text-slate-500 mt-1">ผู้ดูแลระบบ: <span class="font-semibold text-blue-600"><?php echo e(Auth::user()->name); ?></span> (<?php echo e(Auth::user()->role); ?>)</p>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="w-full sm:w-auto">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-lg text-sm font-medium transition">ออกจากระบบ</button>
                </form>
            </div>
        </div>

        <!-- ส่วน Dashboard สถิติและกราฟ (ใส่ Animation ลำดับที่ 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 animate-fade-in-up delay-100">
            <!-- กล่องตัวเลขสถิติ (ใส่ Effect Hover ให้เด้งขึ้น) -->
            <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-md border-b-4 border-slate-500 flex flex-col justify-center items-center text-center h-full transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl cursor-default">
                    <p class="text-slate-500 text-sm font-semibold mb-1">งานซ่อมทั้งหมด</p>
                    <p class="text-4xl font-bold text-slate-700"><?php echo e($totalTickets); ?></p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md border-b-4 border-yellow-400 flex flex-col justify-center items-center text-center h-full transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-yellow-100 cursor-default">
                    <p class="text-slate-500 text-sm font-semibold mb-1">รอดำเนินการ</p>
                    <p class="text-4xl font-bold text-yellow-500"><?php echo e($pendingCount); ?></p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md border-b-4 border-blue-500 flex flex-col justify-center items-center text-center h-full transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-100 cursor-default">
                    <p class="text-slate-500 text-sm font-semibold mb-1">กำลังซ่อม</p>
                    <p class="text-4xl font-bold text-blue-600"><?php echo e($inProgressCount); ?></p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md border-b-4 border-green-500 flex flex-col justify-center items-center text-center h-full transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-green-100 cursor-default">
                    <p class="text-slate-500 text-sm font-semibold mb-1">เสร็จสมบูรณ์</p>
                    <p class="text-4xl font-bold text-green-500"><?php echo e($completedCount); ?></p>
                </div>
            </div>

            <!-- กราฟวงกลม -->
            <div class="bg-white p-4 rounded-xl shadow-md border border-slate-200 flex flex-col justify-center items-center transition-all duration-300 hover:shadow-lg">
                <h3 class="text-sm font-semibold text-slate-600 mb-2">สัดส่วนสถานะงานซ่อม</h3>
                <div class="w-32 h-32 relative hover:scale-105 transition-transform duration-300">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ส่วนตารางข้อมูล (ใส่ Animation ลำดับที่ 3) -->
        <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 animate-fade-in-up delay-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800">รายการแจ้งซ่อมล่าสุด</h3>
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="ค้นหาชื่อลูกค้า หรือ อุปกรณ์..." class="w-full sm:w-64 border rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-sm transition-all focus:shadow-md">
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full bg-white" id="repairTable">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700">วันที่แจ้ง</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700">ลูกค้า</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700">อุปกรณ์</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-slate-700">รูปภาพ</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-slate-700">สถานะ & หมายเหตุ</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-slate-700">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if($tickets && count($tickets) > 0): ?>
                            <?php foreach($tickets as$ticket): ?>
                            <!-- เพิ่ม effect เปลี่ยนสีพื้นหลังนิดๆ ตอนเอาเมาส์ชี้แถวตาราง -->
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 ticket-row group">
                                <td class="py-3 px-4 text-sm text-slate-600 whitespace-nowrap"><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="py-3 px-4 text-sm text-slate-800 font-medium customer-name whitespace-nowrap">
                                    <?php echo e($ticket->customer_name); ?><br><span class="text-xs text-slate-500 font-normal"><?php echo e($ticket->customer_email); ?></span>
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-800 device-name">
                                    <?php echo e($ticket->device_name); ?><br>
                                    <span class="text-xs text-slate-500 block truncate w-48"><?php echo e($ticket->problem_description); ?></span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php if($ticket->image_path): ?>
                                        <a href="<?php echo e(asset('storage/' . $ticket->image_path)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline hover:scale-110 inline-block transition-transform">ดูรูปภาพ</a>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php if($ticket->status == 'pending'): ?>
                                        <!-- ใส่ animate-pulse ให้ไฟกะพริบ -->
                                        <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1 animate-pulse">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span> รอดำเนินการ
                                        </span>
                                    <?php elseif($ticket->status == 'in_progress'): ?>
                                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> กำลังซ่อม
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span> เสร็จสมบูรณ์
                                        </span>
                                    <?php endif; ?>

                                    <?php if($ticket->admin_note): ?>
                                        <div class="mt-2 text-xs text-slate-600 bg-white border rounded p-1.5 shadow-sm text-left line-clamp-2 transition-all hover:line-clamp-none hover:shadow-md cursor-pointer" title="<?php echo e($ticket->admin_note); ?>">
                                            <strong>ช่าง:</strong> <?php echo e($ticket->admin_note); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap opacity-90 group-hover:opacity-100 transition-opacity">
                                    <input type="hidden" id="note-<?php echo e($ticket->id); ?>" value="<?php echo e($ticket->admin_note); ?>">
                                    <form action="<?php echo e(route('admin.updateStatus', $ticket->id)); ?>" method="POST" id="update-form-<?php echo e($ticket->id); ?>" class="hidden">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" id="input-status-<?php echo e($ticket->id); ?>">
                                        <input type="hidden" name="admin_note" id="input-note-<?php echo e($ticket->id); ?>">
                                    </form>

                                    <?php if(Route::has('repair.receipt')): ?>
                                        <a href="<?php echo e(route('repair.receipt', $ticket->id)); ?>" class="bg-slate-500 hover:bg-slate-600 text-white text-xs py-1.5 px-2 rounded shadow transition-transform hover:scale-105 inline-block mr-1" title="พิมพ์ใบรับซ่อม">🖨️</a>
                                    <?php endif; ?>
                                    
                                    <button type="button" onclick="openUpdateModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->status); ?>')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs py-1.5 px-2 rounded shadow transition-transform hover:scale-105 inline-block mr-1">อัปเดต</button>

                                    <?php if(Auth::user()->role === 'admin'): ?>
                                        <form action="<?php echo e(route('admin.delete', $ticket->id)); ?>" method="POST" id="delete-form-<?php echo e($ticket->id); ?>" class="inline-block">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" onclick="confirmDelete(<?php echo e($ticket->id); ?>)" class="bg-red-100 text-red-600 hover:bg-red-200 py-1.5 px-2 rounded text-xs font-semibold transition-transform hover:scale-105 inline-block">ลบ</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">ยังไม่มีข้อมูลการแจ้งซ่อมในระบบ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // โค้ดสร้างกราฟวงกลม
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['รอดำเนินการ', 'กำลังซ่อม', 'เสร็จสมบูรณ์'],
                datasets: [{
                    data: [<?php echo e($pendingCount); ?>, <?php echo e($inProgressCount); ?>, <?php echo e($completedCount); ?>],
                    backgroundColor: ['#eab308', '#3b82f6', '#22c55e'],
                    borderWidth: 0,
                    hoverOffset: 10 // ให้ชิ้นกราฟเด้งออกตอนเอาเมาส์ชี้
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500 // ให้กราฟค่อยๆ หมุนขึ้นมาตอนโหลด
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: function(context) { return ' ' + context.label + ': ' + context.raw + ' งาน'; } }
                    }
                }
            }
        });

        // ส่วน Popup อัปเดตและแจ้งเตือนยังคงเดิม
        function openUpdateModal(id, currentStatus) {
            let currentNote = document.getElementById('note-' + id).value;
            Swal.fire({
                title: 'อัปเดตงานซ่อม',
                html: `
                    <div class="text-left mb-2 text-sm font-semibold">สถานะงานซ่อม:</div>
                    <select id="swal-status" class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-blue-500 mb-4 transition-all">
                        <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>รอดำเนินการ</option>
                        <option value="in_progress" ${currentStatus === 'in_progress' ? 'selected' : ''}>กำลังซ่อม</option>
                        <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>เสร็จสมบูรณ์</option>
                    </select>
                    <div class="text-left mb-2 text-sm font-semibold">ข้อความถึงลูกค้า (หมายเหตุ):</div>
                    <textarea id="swal-note" rows="3" class="w-full border rounded-lg py-2 px-3 focus:ring-2 focus:ring-blue-500 transition-all" placeholder="ระบุเหตุผล เช่น รออะไหล่...">${currentNote}</textarea>
                `,
                showCancelButton: true, confirmButtonColor: '#2563eb',
                confirmButtonText: 'บันทึก & ส่งอีเมล', cancelButtonText: 'ยกเลิก',
                preConfirm: () => {
                    return { status: document.getElementById('swal-status').value, admin_note: document.getElementById('swal-note').value }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'กำลังส่งอีเมล...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    let form = document.getElementById('update-form-' + id);
                    document.getElementById('input-status-' + id).value = result.value.status;
                    document.getElementById('input-note-' + id).value = result.value.admin_note;
                    form.submit();
                }
            })
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบข้อมูล?', text: "ลบแล้วจะไม่สามารถกู้คืนได้!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
                confirmButtonText: 'ลบเลย!', cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }

        <?php if(session('success')): ?> Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '<?php echo e(session('success')); ?>', showConfirmButton: false, timer: 2000 }); <?php endif; ?>
        <?php if($errors->any()): ?> Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด!', text: '<?php echo e($errors->first()); ?>', confirmButtonColor: '#ef4444' }); <?php endif; ?>

        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.getElementsByClassName("ticket-row");
            for (let i = 0; i < rows.length; i++) {
                let customerName = rows[i].getElementsByClassName("customer-name")[0].innerText.toLowerCase();
                let deviceName = rows[i].getElementsByClassName("device-name")[0].innerText.toLowerCase();
                rows[i].style.display = (customerName.includes(input) || deviceName.includes(input)) ? "" : "none";
            }
        }
    </script>
</body>
</html><?php /**PATH /home/u835268696/domains/cgracingth.com/repair_core/resources/views/admin.blade.php ENDPATH**/ ?>