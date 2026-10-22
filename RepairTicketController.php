<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairTicket;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RepairTicketController extends Controller
{
    public function create()
    {
        $tickets = RepairTicket::where('customer_email', Auth::user()->email)->orderBy('created_at', 'desc')->get();
        return view('repair-form', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'device_name' => 'required|string|max:255',
            'problem_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $ticket = new RepairTicket();
        $ticket->customer_name = $request->customer_name;
        $ticket->customer_email = $request->customer_email;
        $ticket->device_name = $request->device_name;
        $ticket->problem_description = $request->problem_description;
        $ticket->status = 'pending';

        if ($request->hasFile('image')) {
            $ticket->image_path = $request->file('image')->store('repair_images', 'public');
        }
        $ticket->save();

        return back()->with('success', 'ส่งข้อมูลแจ้งซ่อมพร้อมรูปภาพเรียบร้อยแล้ว!');
    }

    // 3. ดึงข้อมูลทั้งหมดมาแสดงในหน้า Admin (เรียงจากใหม่ไปเก่า) พร้อมคำนวณสถิติ
    public function index()
    {
        $tickets = RepairTicket::orderBy('created_at', 'desc')->get();
        
        // คำนวณสถิติส่งไปให้ Chart.js สร้างกราฟ
        $totalTickets = $tickets->count();
        $pendingCount = $tickets->where('status', 'pending')->count();
        $inProgressCount = $tickets->where('status', 'in_progress')->count();
        $completedCount = $tickets->where('status', 'completed')->count();

        return view('admin', compact('tickets', 'totalTickets', 'pendingCount', 'inProgressCount', 'completedCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = RepairTicket::findOrFail($id);
        
        // บันทึกข้อมูล
        $ticket->status = $request->status;
        $ticket->admin_note = $request->admin_note;
        $ticket->save();

        // ส่งอีเมลแจ้งเตือน
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.hostinger.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'no-reply@cgracingth.com');
            $mail->Password   = env('MAIL_PASSWORD'); 
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'ssl');
            $mail->Port       = env('MAIL_PORT', 465);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'no-reply@cgracingth.com'), env('MAIL_FROM_NAME', 'CG Racing Repair'));
            $mail->addAddress($ticket->customer_email, $ticket->customer_name);

            $statusText = $ticket->status == 'pending' ? '🟡 รอดำเนินการ' : ($ticket->status == 'in_progress' ? '🔵 กำลังดำเนินการซ่อมแซม' : '🟢 ซ่อมเสร็จสมบูรณ์เรียบร้อยแล้ว');
            
            // แนบหมายเหตุลงในอีเมล (ถ้ามี)
            $noteHtml = $ticket->admin_note ? "<br><p style='padding:10px; background:#f8fafc; border-left:4px solid #3b82f6;'><strong>ข้อความจากช่าง (หมายเหตุ):</strong><br>{$ticket->admin_note}</p>" : "";

            $mail->isHTML(true);
            $mail->Subject = 'อัปเดตสถานะงานซ่อม: ' . $ticket->device_name;
            $mail->Body    = "
                <div style='font-family: sans-serif; color: #333;'>
                    <h3>เรียนคุณ {$ticket->customer_name}</h3>
                    <p>มีการอัปเดตสถานะการแจ้งซ่อมอุปกรณ์ <strong>{$ticket->device_name}</strong> ของคุณ</p>
                    <p><strong>สถานะปัจจุบัน:</strong> {$statusText}</p>
                    {$noteHtml}
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p>ขอบคุณที่ใช้บริการ,<br>ทีมงาน CG Racing</p>
                </div>
            ";
            $mail->send();
        } catch (Exception $e) {
            return back()->withErrors('อัปเดตสถานะแล้ว แต่ระบบส่งอีเมลขัดข้อง: ' . $mail->ErrorInfo);
        }

        return back()->with('success', 'อัปเดตสถานะและส่งอีเมลแจ้งลูกค้าเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->withErrors('คุณไม่มีสิทธิ์ลบข้อมูลนี้');
        }
        $ticket = RepairTicket::findOrFail($id);
        $ticket->delete();
        return back()->with('success', 'ลบข้อมูลการแจ้งซ่อมเรียบร้อยแล้ว');
    }
    // ฟังก์ชันสำหรับหน้าพิมพ์ใบรับซ่อม
    public function receipt($id)
    {
        $ticket = RepairTicket::findOrFail($id);
        return view('receipt', compact('ticket'));
    }
}