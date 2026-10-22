<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแจ้งซ่อม - CG Racing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .delay-100 { animation-delay: 0.15s; }
    </style>
</head>
<body class="bg-slate-100 py-12">
    
    <!-- กล่องฟอร์มแจ้งซ่อม (ใส่ Animation ลำดับที่ 1) -->
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-200 animate-fade-in-up">
        
        <!-- ส่วนหัว: แสดงชื่อผู้ใช้ และปุ่มออกจากระบบ -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 pb-4 border-b border-slate-200 gap-4">
            <div class="text-sm text-slate-600">
                เข้าสู่ระบบโดย: <span class="font-semibold text-slate-800"><?php echo e(Auth::user()->name); ?></span>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg text-sm font-medium transition hover:scale-105">
                    ออกจากระบบ
                </button>
            </form>
        </div>

        <h2 class="text-3xl font-semibold mb-6 text-center text-slate-800">ฟอร์มแจ้งซ่อมอุปกรณ์</h2>

        <form action="<?php echo e(route('repair.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-5 transition-all focus-within:transform focus-within:translate-x-1">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="customer_name">ชื่อ-นามสกุล</label>
                <input type="text" name="customer_name" id="customer_name" value="<?php echo e(Auth::user()->name); ?>" class="bg-slate-50 shadow-sm border rounded-lg w-full py-2.5 px-3 text-slate-500 leading-tight focus:outline-none" readonly required>
            </div>

            <div class="mb-5 transition-all focus-within:transform focus-within:translate-x-1">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="customer_email">อีเมล (สำหรับรับแจ้งเตือน)</label>
                <input type="email" name="customer_email" id="customer_email" value="<?php echo e(Auth::user()->email); ?>" class="bg-slate-50 shadow-sm border rounded-lg w-full py-2.5 px-3 text-slate-500 leading-tight focus:outline-none" readonly required>
            </div>

            <div class="mb-5 transition-all focus-within:transform focus-within:translate-x-1">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="device_name">ชื่ออุปกรณ์ / รุ่น</label>
                <input type="text" name="device_name" id="device_name" placeholder="เช่น หินเจียร Makita 9553" class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-slate-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" required>
            </div>

            <div class="mb-5 transition-all focus-within:transform focus-within:translate-x-1">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="problem_description">รายละเอียดปัญหา / อาการเสีย</label>
                <textarea name="problem_description" id="problem_description" rows="4" class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-slate-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" required></textarea>
            </div>

            <div class="mb-8 transition-all focus-within:transform focus-within:translate-x-1">
                <label class="block text-slate-700 text-sm font-bold mb-2" for="image">แนบรูปภาพอาการเสีย (ถ้ามี)</label>
                <input type="file" name="image" id="image" accept="image/*" class="shadow-sm border rounded-lg w-full py-2 px-3 text-slate-700 bg-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                <p class="text-xs text-slate-500 mt-1">รองรับไฟล์ .jpg, .png ขนาดไม่เกิน 5MB</p>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                ส่งเรื่องแจ้งซ่อม
            </button>
        </form>
    </div>

    <!-- ส่วนแสดงประวัติงานซ่อมด้านล่าง (ใส่ Animation ลำดับที่ 2 ให้ลอยตามมา) -->
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg border border-slate-200 animate-fade-in-up delay-100">
        <h3 class="text-xl font-semibold mb-6 text-slate-800">ประวัติการแจ้งซ่อมของคุณ</h3>
        
        <?php if(isset($tickets) && $tickets->count() > 0): ?>
            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full bg-white">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700 whitespace-nowrap">วันที่แจ้ง</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700">อุปกรณ์</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-slate-700">อาการเสีย</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-slate-700">รูปภาพ</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-slate-700">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!-- ใส่ Hover Effect ให้แถวของตาราง -->
                        <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                            <td class="py-3 px-4 text-sm text-slate-600 whitespace-nowrap"><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="py-3 px-4 text-sm text-slate-800 font-medium"><?php echo e($ticket->device_name); ?></td>
                            <td class="py-3 px-4 text-sm text-slate-600"><?php echo e(Str::limit($ticket->problem_description, 50)); ?></td>
                            <td class="py-3 px-4 text-center">
                                <?php if($ticket->image_path): ?>
                                    <!-- ใส่ Hover เด้งขยายที่ลิงก์รูปภาพ -->
                                    <a href="<?php echo e(asset('storage/' . $ticket->image_path)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline hover:scale-110 inline-block transition-transform">ดูรูปภาพ</a>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-center text-sm min-w-[200px]">
                                <!-- สถานะแบบป้ายสี พร้อม Animation กะพริบไฟ -->
                                <?php if($ticket->status == 'pending'): ?>
                                    <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1 animate-pulse mb-2">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> รอดำเนินการ
                                    </span>
                                <?php elseif($ticket->status == 'in_progress'): ?>
                                    <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> กำลังซ่อม
                                    </span>
                                <?php else: ?>
                                    <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold shadow-sm inline-flex items-center gap-1 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span> เสร็จสมบูรณ์
                                    </span>
                                <?php endif; ?>

                                <?php if($ticket->admin_note): ?>
                                    <div class="text-xs text-left bg-white border border-slate-200 p-2 rounded shadow-sm w-full transition-shadow hover:shadow-md">
                                        <strong class="text-blue-600 block mb-1">ข้อความจากช่าง:</strong>
                                        <span class="text-slate-600 break-words"><?php echo e($ticket->admin_note); ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-slate-500 text-center py-4 bg-slate-50 rounded border border-dashed border-slate-300">ยังไม่มีประวัติการแจ้งซ่อม</p>
        <?php endif; ?>
    </div>

    <!-- ตั้งค่า SweetAlert2 สำหรับแจ้งเตือนเมื่อแจ้งซ่อมสำเร็จ -->
    <?php if(session('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'ส่งข้อมูลสำเร็จ!',
            text: '<?php echo e(session('success')); ?>',
            confirmButtonColor: '#3085d6',
        });
    </script>
    <?php endif; ?>

    <!-- ตั้งค่า SweetAlert2 สำหรับกรณีเกิด Error เช่น ลืมกรอกข้อมูล หรือไฟล์รูปใหญ่เกิน -->
    <?php if($errors->any()): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: '<?php echo e($errors->first()); ?>',
            confirmButtonColor: '#ef4444',
        });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH /home/u835268696/domains/cgracingth.com/repair_core/resources/views/repair-form.blade.php ENDPATH**/ ?>