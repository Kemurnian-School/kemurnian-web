<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\EnrollmentMapper;
use Inertia\Inertia;

class EnrollmentController extends Controller
{
    public function __construct(private EnrollmentMapper $enrollmentMapper)
    {
    }

    public function enrollment()
    {
        $enrollment = Enrollment::first();

        return Inertia::render('Guest/Enrollment', [
            'enrollment' => $enrollment ? $this->enrollmentMapper->formatEnrollment($enrollment) : null,
        ]);
    }
}
