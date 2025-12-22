<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Exception;

class SmtpTesterController extends Controller
{
    public function index()
    {
        $configs = [
            'smtp' => [
                'name' => 'System/Default Mailer (smtp)',
                'host' => Config::get('mail.mailers.smtp.host'),
                'port' => Config::get('mail.mailers.smtp.port'),
                'username' => Config::get('mail.mailers.smtp.username'),
                'encryption' => Config::get('mail.mailers.smtp.encryption'),
                'from' => Config::get('mail.from.address'),
            ],
            'support' => [
                'name' => 'Support Mailer (support)',
                'host' => Config::get('mail.mailers.support.host'),
                'port' => Config::get('mail.mailers.support.port'),
                'username' => Config::get('mail.mailers.support.username'),
                'encryption' => Config::get('mail.mailers.support.encryption'),
                'from' => Config::get('mail.mailers.support.username'), // usually same as username or configured
            ],
        ];

        return view('pages.admin.smtp-tester.index', compact('configs'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'mailer' => 'required|in:smtp,support',
            'email' => 'required|email',
        ]);

        $mailer = $request->mailer;
        $targetEmail = $request->email;


        try {
            $start = microtime(true);
            
            $fromAddress = config("mail.mailers.{$mailer}.from.address") ?? config('mail.from.address');
            $fromName = config("mail.mailers.{$mailer}.from.name") ?? config('mail.from.name');
            $mode = $request->input('mode', 'raw');

            if ($mode === 'mailable') {
                Mail::mailer($mailer)
                    ->to($targetEmail)
                    ->send(new \App\Mail\ContactReply("SMTP Connection Test (Mailable Mode)", "This is a test message sent using the actual ContactReply mailable class.\n\nTime: " . now()));
            } else {
                Mail::mailer($mailer)->raw("This is a test email from the SMTP Tester (Raw Mode).\n\nMailer: $mailer\nFrom: $fromAddress ($fromName)\nTime: " . now(), function ($message) use ($targetEmail, $fromAddress, $fromName) {
                    $message->to($targetEmail)
                        ->from($fromAddress, $fromName)
                        ->subject('SMTP Connection Test (Raw Mode) - ' . config('app.name'));
                });
            }

            $duration = round((microtime(true) - $start) * 1000, 2);

            return back()->with('success', "Test email sent successfully via '{$mailer}' (Mode: {$mode}) in {$duration}ms!")
                ->with('title', 'Test Successful');
        } catch (Exception $e) {
            return back()->with('error', "Connection Failed: " . $e->getMessage())
                ->with('title', 'Connection Failed');
        }
    }
}
