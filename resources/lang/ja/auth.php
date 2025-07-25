<?php

declare(strict_types=1);

return [
    'accept_term' => '内容を読み、同意します',
    'active' => [
        'attribute' => 'アクティベート',
        'error' => [
            'activated' => 'アカウントがアクティブです。今すぐログインしてください!',
            'disable' => 'このサイトはアカウントが停止されているため、直接ログインすることができます。',
            'throttle' => '起動要求の上限に達しました。後でもう一度試してください。',
        ],
        'promotion' => 'アカウントがまだアクティベートされていません、まず「:action」を行ってください！',
        'sent' => 'アクティベートリンクがメールに送信されましたので、受信トレイ（スパムフォルダも含む）を確認してください。',
    ],
    'aup' => '使用許諾ポリシー',
    'captcha' => [
        'attribute' => 'キャプチャ',
        'error' => [
            'failed' => '確認コードが正しくありません。入力し直してください!',
            'timeout' => '認証コードが期限切れです。再読み込みしてから再度お試しください。',
        ],
        'required' => 'キャプチャを完了してください！',
        'sent' => 'キャプチャがメールに送信されましたので、受信トレイ（スパムフォルダも含む）を確認してください。',
    ],
    'email' => [
        'error' => [
            'banned' => 'メールプロバイダーはサポートされていません。メールボックスを換えてください！',
            'invalid' => 'あなたのメールアドレスはこのサイトでサポートされるメールアドレスではありません!',
        ],
    ],
    'error' => [
        'account_baned' => 'あなたのアカウントは禁止されています!',
        'login_error' => 'ログインエラーが起こりました。後ほど再試行してください。',
        'login_failed' => 'ログインに失敗しました、ユーザー名とパスワードを確認してください！',
        'not_found_user' => '関連付けられているアカウントが見つかりませんでした。他の方法でサインインしてください。',
        'repeat_request' => '再度リクエストする必要はありません。リフレッシュしてからもう一度お試しください！',
        'url_timeout' => 'リンクは無効になっています、別の操作をやり直してください!',
    ],
    'failed' => '無効な資格情報です。',
    'invite' => [
        'get' => '招待コードを取得',
        'not_required' => '招待コードは不要です、直接登録できます！',
        'unavailable' => '無効な招待コードです。再試行して下さい！',
    ],
    'login' => 'ログイン',
    'logout' => 'ログアウト',
    'maintenance' => 'メンテナンス',
    'maintenance_tip' => 'システムメンテナンス中です。少々お待ち下さい。',
    'oauth' => [
        'login_failed' => 'サードパーティのログインに失敗しました！',
        'register' => 'クイック登録',
        'registered' => '既に登録されています、直接ログインしてください。',
    ],
    'one-click_login' => 'ソーシャルログイン',
    'optional' => 'オプション',
    'password' => [
        'forget' => 'パスワードを忘れた方はこちら',
        'new' => '新しいパスワードを入力',
        'original' => '現在のパスワード',
        'reset' => [
            'attribute' => 'パスワードの再設定',
            'error' => [
                'demo' => 'このデモ版では管理者のパスワードの変更が無効になっています。',
                'disabled' => '今はパスワードリセット機能を無効にしています！',
                'same' => '新しいパスワードを古いパスワードと同じにはできません。もう一度設定してください。',
                'throttle' => '24時間ごとにユーザ名のみ再設定できます。パスワードは time 回、頻繁に行わないでください。',
                'wrong' => '古いパスワードが正しくありません。再度入力してください。',
            ],
            'sent' => 'リセットリンクがメールに送信されましたので、受信トレイ（スパムフォルダも含む）を確認してください。',
            'success' => '新しいパスワードが設定されました。ログインページに移動してください。',
        ],
    ],
    'register' => [
        'attribute' => 'サインアップ',
        'code' => '登録コード',
        'error' => [
            'disable' => '申し訳ありませんが、現在新規ユーザーの受付を停止しています。',
            'throttle' => 'アンチボットシステムが作動しました！頻繁な提出は避けてください。',
        ],
        'failed' => '登録に失敗しました、後でもう一度お試しください。',
        'promotion' => 'アカウントをお持ちでない方は、まず',
    ],
    'remember_me' => 'ログイン状態を保持する',
    'request' => 'リクエスト',
    'throttle' => '試行回数が多すぎます、:seconds秒後にもう一度お試しください。',
    'tos' => '利用規約',
];
