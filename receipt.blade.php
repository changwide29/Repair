<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบรับซ่อม - {{ $ticket->device_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; }
        /* ตั้งค่าเวลาสั่ง Print */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .print-container { box-shadow: none; border: none; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8">
    <div class="max-w-3xl mx-auto bg-white p-10 rounded-xl shadow-lg border border-slate-200 print-container">
        
        <!-- หัวใบรับซ่อม -->
        <div class="flex justify-between items-start border-b-2 border-slate-800 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-blue-700 uppercase tracking-wider">CG Racing</h1>
                <p class="text-slate-600 mt-1 font-medium">ใบรับซ่อมอุปกรณ์ (Repair Receipt)</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-lg text-slate-800">รหัสงานซ่อม: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-slate-500">วันที่แจ้งซ่อม: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        
        <!-- ข้อมูลลูกค้าและอุปกรณ์ -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="font-bold text-slate-800 mb-2 border-l-4 border-blue-500 pl-2">ข้อมูลลูกค้า</h3>
                <p class="text-slate-700"><strong>ชื่อ:</strong> {{ $ticket->customer_name }}</p>
                <p class="text-slate-700"><strong>อีเมล:</strong> {{ $ticket->customer_email }}</p>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 mb-2 border-l-4 border-blue-500 pl-2">ข้อมูลอุปกรณ์</h3>
                <p class="text-slate-700"><strong>อุปกรณ์/รุ่น:</strong> {{ $ticket->device_name }}</p>
                <p class="text-slate-700"><strong>สถานะปัจจุบัน:</strong> 
                    @if($ticket->status == 'pending') รอดำเนินการ
                    @elseif($ticket->status == 'in_progress') กำลังซ่อม
                    @else เสร็จสมบูรณ์
                    @endif
                </p>
            </div>
        </div>

        <!-- อาการเสีย -->
        <div class="mb-8 border border-slate-200 p-4 rounded-lg bg-slate-50">
            <h3 class="font-bold text-slate-800 mb-2">รายละเอียดอาการเสีย:</h3>
            <p class="whitespace-pre-line text-slate-700">{{ $ticket->problem_description }}</p>
        </div>

        <!-- หมายเหตุจากช่าง (ถ้ามี) -->
        @if($ticket->admin_note)
        <div class="mb-8 border border-blue-200 p-4 rounded-lg bg-blue-50">
            <h3 class="font-bold text-blue-700 mb-2">หมายเหตุจากช่าง:</h3>
            <p class="whitespace-pre-line text-blue-800">{{ $ticket->admin_note }}</p>
        </div>
        @endif

        <!-- ลายเซ็น -->
        <div class="flex justify-between mt-20 pt-8 border-t border-slate-200 text-center">
            <div class="w-1/3">
                <p class="mb-8 text-slate-400">________________________</p>
                <p class="font-medium text-slate-800">( {{ $ticket->customer_name }} )</p>
                <p class="text-sm text-slate-500">ลูกค้าผู้แจ้งซ่อม</p>
            </div>
            <div class="w-1/3">
                <p class="mb-8 text-slate-400">________________________</p>
                <p class="font-medium text-slate-800">( เจ้าหน้าที่ CG Racing )</p>
                <p class="text-sm text-slate-500">ผู้รับเรื่อง</p>
            </div>
        </div>

        <!-- ปุ่มสั่งปริ้น (จะหายไปตอนกดปริ้น) -->
        <div class="mt-12 text-center no-print">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold shadow-md transition">
                🖨️ สั่งพิมพ์ใบรับซ่อม
            </button>
            <br>
            <a href="javascript:history.back()" class="inline-block mt-4 text-slate-500 hover:text-slate-800 hover:underline transition">กลับไปหน้าเดิม</a>
        </div>
    </div>
</body>
</html>