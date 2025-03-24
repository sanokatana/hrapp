<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CutiApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveApplication;
    public $showApprovalButtons;
    public $approveUrl;
    public $denyUrl;
    public $date_now;

    /**
     * Create a new message instance.
     */
    public function __construct($leaveApplication, $approveUrl, $denyUrl, $showApprovalButtons)
    {
        // Add default jenis_cuti if not set
        if (!isset($leaveApplication->jenis_cuti)) {
            $leaveApplication->jenis_cuti = 'Cuti Tahunan'; // Set default value
        }

        $this->leaveApplication = $leaveApplication;
        $this->approveUrl = $approveUrl;
        $this->denyUrl = $denyUrl;
        $this->showApprovalButtons = $showApprovalButtons;
        $this->date_now = Carbon::now()->format('Y-m-d H:i:s');
    }

    /**
     * Build the email.
     */
    public function build()
    {
        return $this->subject('Pengajuan Cuti Baru Dari ' . $this->leaveApplication->nama_karyawan . ' - ' . $this->date_now)
            ->view('emails.cuti_approval')
            ->with([
                'leaveApplication' => $this->leaveApplication,
                'showApprovalButtons' => $this->showApprovalButtons,
                'approveUrl' => $this->approveUrl,
                'denyUrl' => $this->denyUrl,
                'date_now' => $this->date_now
            ]);
    }
}
