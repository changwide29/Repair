<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - CG Racing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Kanit', sans-serif; } </style>
</head>
<body class="min-h-screen py-12 flex items-center justify-center bg-cover bg-center bg-no-repeat relative" 
      style="background-image: url('https://i.postimg.cc/NLMQgH0z/baen-nex-r-rabb-s-xm-xith-CG-RACING.png');">
    
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm fixed"></div>

    <div class="max-w-md w-full mx-auto bg-white/95 p-8 rounded-xl shadow-2xl border border-slate-200 relative z-10 my-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-slate-800">สมัครสมาชิกใหม่</h2>

        <form action="<?php echo e(route('register.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-bold mb-2">ชื่อ-นามสกุล</label>
                <input type="text" name="name" class="w-full border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/80" required value="<?php echo e(old('name')); ?>">
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-bold mb-2">อีเมล</label>
                <input type="email" name="email" class="w-full border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/80" required value="<?php echo e(old('email')); ?>">
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-bold mb-2">รหัสผ่าน (ขั้นต่ำ 6 ตัว)</label>
                <input type="password" name="password" class="w-full border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/80" required>
            </div>
            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-bold mb-2">ยืนยันรหัสผ่านอีกครั้ง</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/80" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 mb-4">สมัครสมาชิก</button>
            
            <div class="text-center text-sm text-slate-600">
                มีบัญชีอยู่แล้ว? <a href="<?php echo e(route('login')); ?>" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold transition">เข้าสู่ระบบที่นี่</a>
            </div>
        </form>
    </div>

    <?php if($errors->any()): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'สมัครสมาชิกไม่สำเร็จ!', text: '<?php echo e($errors->first()); ?>', confirmButtonColor: '#ef4444' });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH /home/u835268696/domains/cgracingth.com/repair_core/resources/views/register.blade.php ENDPATH**/ ?>