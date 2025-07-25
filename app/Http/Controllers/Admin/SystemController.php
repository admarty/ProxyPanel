<?php

namespace App\Http\Controllers\Admin;

use App\Channels\BarkChannel;
use App\Channels\DingTalkChannel;
use App\Channels\iYuuChannel;
use App\Channels\PushDeerChannel;
use App\Channels\PushPlusChannel;
use App\Channels\ServerChanChannel;
use App\Channels\TgChatChannel;
use App\Channels\WeChatChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemRequest;
use App\Models\Config;
use App\Models\Country;
use App\Models\GoodsCategory;
use App\Models\Label;
use App\Models\Level;
use App\Models\SsConfig;
use App\Notifications\Custom;
use App\Services\TelegramService;
use App\Utils\DDNS;
use App\Utils\Payments\PaymentManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use NotificationChannels\Telegram\TelegramChannel;

class SystemController extends Controller
{
    public function index(): View
    { // 系统设置
        function parseTime(string|array $inputs): array
        {
            $items = is_array($inputs) ? $inputs : json_decode($inputs, true);
            $result = [];

            foreach ($items as $key => $duration) {
                preg_match('/-(\d+)\s+(\w+)/', $duration, $m);
                $result[$key] = [
                    'num' => $m[1] ?? 1,
                    'unit' => $m[2] ?? 'hours',
                ];
            }

            return $result;
        }

        // 获取所有配置项
        $config = Config::pluck('value', 'name')->toArray();

        // 预处理复杂数据
        // 解析时间类配置
        $config['tasks_clean'] = parseTime($config['tasks_clean']);
        $config['tasks_close'] = parseTime($config['tasks_close']);

        $paymentForms = PaymentManager::getSettingsFormData();

        return view('admin.config.system', [
            'configs' => $config,
            'payments' => PaymentManager::getAvailable(),
            'paymentForms' => $paymentForms,
            'paymentTabs' => [...array_keys($paymentForms), 'manual'],
            'paymentLists' => [
                'ali' => PaymentManager::getPaymentsByMethod('ali'),
                'wechat' => PaymentManager::getPaymentsByMethod('wechat'),
                'qq' => PaymentManager::getPaymentsByMethod('qq'),
                'other' => PaymentManager::getPaymentsByMethod('other'),
            ],
            'captcha' => $this->getCaptcha(),
            'notifies' => $this->getNotifyChannels(),
            'notifyForms' => $this->getNotifyForms(),
            'ddns_labels' => (new DDNS)->getLabels(),
        ]);
    }

    private function getCaptcha(): bool
    {
        return sysConfig('captcha_secret') && sysConfig('captcha_key');
    }

    private function getNotifyChannels(): array
    {
        $configMap = [ // 支付渠道及其所需配置项映射
            'bark' => ['bark_key'],
            'dingTalk' => ['dingTalk_access_token'],
            'iYuu' => ['iYuu_token'],
            'pushDear' => ['pushDeer_key'],
            'pushPlus' => ['pushplus_token'],
            'serverChan' => ['server_chan_key'],
            'telegram' => ['telegram_token'],
            'tgChat' => ['tg_chat_token'],
            'weChat' => ['wechat_cid', 'wechat_aid', 'wechat_secret', 'wechat_token', 'wechat_encodingAESKey'],
        ];

        $channels = ['database', 'mail'];

        // 遍历映射，检查配置项是否存在
        foreach ($configMap as $channel => $configKeys) {
            $allConfigsExist = array_reduce($configKeys, static function ($carry, $configKey) {
                return $carry && sysConfig($configKey);
            }, true);

            if ($allConfigsExist) {
                $channels[] = $channel;
            }
        }

        return $channels;
    }

    private function getNotifyForms(): array
    {
        return [
            'server_chan_key' => ['test' => 'serverChan'],
            'pushDeer_key' => ['test' => 'pushDeer'],
            'iYuu_token' => ['test' => 'iYuu'],
            'bark_key' => ['test' => 'bark'],
            'telegram_token' => ['test' => 'telegram'],
            'pushplus_token' => ['test' => 'pushPlus'],
            'dingTalk_access_token' => null,
            'dingTalk_secret' => ['test' => 'dingTalk'],
            'wechat_cid' => null,
            'wechat_aid' => null,
            'wechat_secret' => ['test' => 'weChat'],
            'wechat_token' => ['url' => route('wechat.verify')],
            'wechat_encodingAESKey' => null,
            'tg_chat_token' => ['test' => 'tgChat'],
        ];
    }

    public function setExtend(Request $request): RedirectResponse  // 设置涉及到上传的设置
    {
        if ($request->hasAny(['website_home_logo', 'website_home_logo'])) { // 首页LOGO
            if ($request->hasFile('website_home_logo')) {
                $validator = validator()->make($request->all(), ['website_home_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

                if ($validator->fails()) {
                    return redirect()->route('admin.system.index', '#other')->withErrors($validator->errors());
                }
                $file = $request->file('website_home_logo');
                $file->move('uploads/logo', $file->getClientOriginalName());
                if (Config::findOrNew('website_home_logo')->update(['value' => 'uploads/logo/'.$file->getClientOriginalName()])) {
                    return redirect()->route('admin.system.index', '#other')->with('successMsg', trans('common.success_item', ['attribute' => trans('common.update')]));
                }
            }
            if ($request->hasFile('website_logo')) { // 站内LOGO
                $validator = validator()->make($request->all(), ['website_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

                if ($validator->fails()) {
                    return redirect()->route('admin.system.index', '#other')->withErrors($validator->errors());
                }
                $file = $request->file('website_logo');
                $file->move('uploads/logo', $file->getClientOriginalName());
                if (Config::findOrNew('website_logo')->update(['value' => 'uploads/logo/'.$file->getClientOriginalName()])) {
                    return redirect()->route('admin.system.index', '#other')->with('successMsg', trans('common.success_item', ['attribute' => trans('common.update')]));
                }
            }

            return redirect()->route('admin.system.index', '#other')->withErrors(trans('common.failed_item', ['attribute' => trans('common.update')]));
        }

        if ($request->hasAny(['alipay_qrcode', 'wechat_qrcode'])) {
            if ($request->hasFile('alipay_qrcode')) {
                $validator = validator()->make($request->all(), ['alipay_qrcode' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

                if ($validator->fails()) {
                    return redirect()->route('admin.system.index', '#payment')->withErrors($validator->errors());
                }
                $file = $request->file('alipay_qrcode');
                $file->move('uploads/images', $file->getClientOriginalName());
                if (Config::findOrNew('alipay_qrcode')->update(['value' => 'uploads/images/'.$file->getClientOriginalName()])) {
                    return redirect()->route('admin.system.index', '#payment')->with('successMsg', trans('common.success_item', ['attribute' => trans('common.update')]));
                }
            }

            if ($request->hasFile('wechat_qrcode')) { // 站内LOGO
                $validator = validator()->make($request->all(), ['wechat_qrcode' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

                if ($validator->fails()) {
                    return redirect()->route('admin.system.index', '#payment')->withErrors($validator->errors());
                }
                $file = $request->file('wechat_qrcode');
                $file->move('uploads/images', $file->getClientOriginalName());
                if (Config::findOrNew('wechat_qrcode')->update(['value' => 'uploads/images/'.$file->getClientOriginalName()])) {
                    return redirect()->route('admin.system.index', '#payment')->with('successMsg', trans('common.success_item', ['attribute' => trans('common.update')]));
                }
            }

            return redirect()->route('admin.system.index', '#payment')->withErrors(trans('common.failed_item', ['attribute' => trans('common.update')]));
        }

        return redirect()->route('admin.system.index');
    }

    public function setConfig(SystemRequest $request): JsonResponse // 设置某个配置项
    {
        $name = $request->input('name');
        $value = $request->input('value');

        if (empty($value) || $value === 'NaN') { // 关闭 或 空值 自动设NULL，减少系统设置存储
            $value = null;
        }

        // 支付设置判断
        if ($value !== null && in_array($name, ['is_AliPay', 'is_QQPay', 'is_WeChatPay'], true) && ! in_array($value, PaymentManager::getAvailable(), true)) {
            return response()->json(['status' => 'fail', 'message' => trans('admin.system.params_required', ['attribute' => trans('admin.system.payment.attribute')])]);
        }

        if ($value > 1 && $name === 'is_captcha' && ! $this->getCaptcha()) {
            return response()->json(['status' => 'fail', 'message' => trans('admin.system.params_required', ['attribute' => trans('auth.captcha.attribute')])]);
        }

        // 演示环境禁止修改特定配置项
        if (config('app.env') === 'demo') {
            $denyConfig = [
                'website_url',
                'is_captcha',
                'forbid_mode',
                'website_security_code',
                'website_security_code',
                'username_type',
            ];

            if (in_array($name, $denyConfig, true)) {
                return response()->json(['status' => 'fail', 'message' => trans('admin.system.demo_restriction')]);
            }
        }

        // 如果是返利比例，则需要除100
        if ($name === 'referral_percent') {
            $value /= 100;
        }

        // 设置TG机器人
        if ($name === 'telegram_token' && $value) {
            $telegramService = new TelegramService($value);
            $telegramService->getMe();
            $telegramService->setWebhook(rtrim(sysConfig('website_url'), '/').'/api/telegram/webhook?access_token='.md5($value));
        }

        // 更新配置
        if (Config::findOrFail($name)->update(['value' => $value])) {
            return response()->json(['status' => 'success', 'message' => trans('common.success_item', ['attribute' => trans('common.update')])]);
        }

        return response()->json(['status' => 'fail', 'message' => trans('common.failed_item', ['attribute' => trans('common.update')])]);
    }

    public function sendTestNotification(): JsonResponse  // 推送通知测试
    {
        $channels = [
            'serverChan' => ServerChanChannel::class,
            'bark' => BarkChannel::class,
            'telegram' => TelegramChannel::class,
            'weChat' => WeChatChannel::class,
            'tgChat' => TgChatChannel::class,
            'pushPlus' => PushPlusChannel::class,
            'iYuu' => iYuuChannel::class,
            'pushDeer' => PushDeerChannel::class,
            'dingTalk' => DingTalkChannel::class,
        ];

        $selectedChannel = request('channel');

        if (! array_key_exists($selectedChannel, $channels)) {
            return response()->json(['status' => 'fail', 'message' => trans('admin.system.notification.test.unknown_channel')]);
        }

        Notification::sendNow(auth()->user(), new Custom(trans('admin.system.notification.test.title'), sysConfig('website_name').' '.trans('admin.system.notification.test.content')), [$channels[$selectedChannel]]);

        return response()->json(['status' => 'success', 'message' => trans('admin.system.notification.test.success')]);
    }

    public function common(): View
    {
        return view('admin.config.common', [
            'methods' => SsConfig::type(1)->get(),
            'protocols' => SsConfig::type(2)->get(),
            'categories' => GoodsCategory::all(),
            'obfsList' => SsConfig::type(3)->get(),
            'countries' => Country::all(),
            'levels' => Level::all(),
            'labels' => Label::with('nodes')->get(),
        ]);
    }
}
