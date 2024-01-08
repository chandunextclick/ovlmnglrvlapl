<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\PusherSetting;
use App\Models\PushNotificationSetting;
use App\Models\SlackSetting;
use App\Models\SmtpSetting;

class NotificationSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.notificationSettings';
        $this->activeSettingMenu = 'notification_settings';
        $this->middleware(function ($request, $next) {
            abort_403((user()->permission('manage_notification_setting') !== 'all') && (!user()->is_superadmin));

            return $next($request);
        });
    }

    public function index()
    {
        $tab = request('tab');

        $this->emailSettings = email_notification_setting();

        $this->slackSettings = SlackSetting::first();
        $this->pushSettings = PushNotificationSetting::first();
        $this->pusherSettings = PusherSetting::first();

        switch ($tab) {
        case 'slack-setting':
            $this->view = 'notification-settings.ajax.slack-setting';
            break;
        case 'push-notification-setting':
            $this->view = 'notification-settings.ajax.push-notification-setting';
            break;
        case 'pusher-setting':
            $this->view = 'notification-settings.ajax.pusher-setting';
            break;
        default:
            $this->smtpSetting = SmtpSetting::first();
            $this->view = 'notification-settings.ajax.email-setting';
            break;
        }

        $this->activeTab = $tab ?: 'email-setting';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('notification-settings.index', $this->data);
    }

}
