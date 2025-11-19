<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        // You can filter certificates based on role
        $user = auth()->user();

        $query = Certificate::with(['student', 'course', 'teacher']);

        if ($user->role === 'Teacher') {
            $query->where('teacher_id', $user->id);
        } elseif ($user->role === 'Admin') {
            // show all or filter differently if needed
        } else {
            $query->where('student_id', $user->id);
        }

        $certificates = $query->paginate(10);

        return Inertia::render('Certificates/Index', [
            'certificates' => $certificates,
            'role' => $user->role,
        ]);
    }

    public function teacherApprove(Request $request, Certificate $certificate)
    {
        $user = $request->user();

        // ✅ Role check
        if ($user->role !== 'Teacher' || $user->id !== $certificate->teacher_id) {
            abort(403, 'Unauthorized.');
        }

        $certificate->update([
            'status' => 'teacher_approved',
            'teacher_approved_at' => now(),
            'teacher_approved_by' => $user->id,
        ]);

        return back()->with('success', 'Teacher approval successful.');
    }

    public function adminApprove(Request $request, Certificate $certificate)
    {
        $user = $request->user();

        // ✅ Role check
        if ($user->role !== 'Admin') {
            abort(403, 'Unauthorized.');
        }

        $certificate->update([
            'status' => 'admin_approved',
            'admin_approved_at' => now(),
            'admin_approved_by' => $user->id,
        ]);

        if ($certificate->teacher_approved_at && $certificate->admin_approved_at) {
            return $this->generateCertificate($certificate);
        }

        return back()->with('success', 'Admin approval successful.');
    }

    public function generateCertificate(Certificate $certificate)
    {
        // Only generate if both approvals exist
        if (!($certificate->teacher_approved_at && $certificate->admin_approved_at)) {
            abort(403, 'Certificate cannot be generated yet.');
        }

        $data = [
            'student' => $certificate->student,
            'course' => $certificate->course,
            'date' => now()->toFormattedDateString(),
        ];

        $pdf = Pdf::loadView('certificates.template', $data);
        $path = 'certificates/certificate-' . $certificate->id . '.pdf';
        Storage::put($path, $pdf->output());

        $certificate->update([
            'status' => 'completed',
            'certificate_generated_at' => now(),
            'certificate_path' => $path,
        ]);

        return $pdf->download('certificate-' . $certificate->id . '.pdf');
    }
}

