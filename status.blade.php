<x-mail::message>
# เรียนคุณ {{ $ticket->customer_name }}

มีการอัปเดตสถานะการแจ้งซ่อมอุปกรณ์ **{{ $ticket->device_name }}** ของคุณ

**สถานะปัจจุบัน:** 
@if($ticket->status == 'pending')
🟡 รอดำเนินการ
@elseif($ticket->status == 'in_progress')
🔵 กำลังดำเนินการซ่อมแซม
@else
🟢 ซ่อมเสร็จสมบูรณ์เรียบร้อยแล้ว
@endif

**รายละเอียดปัญหาที่แจ้งไว้:**
{{ $ticket->problem_description }}

หากมีข้อสงสัยเพิ่มเติม สามารถติดต่อสอบถามทางร้านได้เลยครับ

ขอบคุณที่ใช้บริการ,<br>
ทีมงาน {{ config('app.name') }}
</x-mail::message>