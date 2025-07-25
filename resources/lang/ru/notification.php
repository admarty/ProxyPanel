<?php

declare(strict_types=1);

return [
    'account_expired' => 'Напоминание об истечении срока действия аккаунта',
    'account_expired_blade' => 'Срок действия аккаунта истекает через :days дней, пожалуйста, продлите его своевременно',
    'account_expired_content' => 'Срок действия вашего аккаунта истекает через :days дней. Пожалуйста, своевременно продлите его, чтобы продолжить пользоваться нашими услугами.',
    'active_email' => 'Пожалуйста, завершите проверку в течение 30 минут',
    'attribute' => 'Уведомление',
    'block_report' => 'Отчет о блокировке:',
    'close_ticket' => 'Тикет [ID: :id, заголовок: :title] закрыт.',
    'data_anomaly' => 'Уведомление о аномальном трафике пользователя.',
    'data_anomaly_content' => 'Пользователь [ID: :id] трафика за последний час（Загрузка: :upload, Скачивание: :download, Всего: :total）',
    'details' => 'Просмотреть подробности',
    'details_btn' => 'Пожалуйста, нажмите кнопку ниже, чтобы просмотреть подробности.',
    'ding_bot_limit' => 'Каждый бот может отправлять не более 20 сообщений в минуту. При превышении лимита будет установлена задержка в 10 минут.',
    'empty' => 'У вас нет новых сообщений',
    'error' => '[:channel] Ошибка отправки сообщения: :reason',
    'get_access_token_failed' => 'Не удалось получить токен доступа!\nС параметрами запроса: :body',
    'into_maintenance' => 'Автоматически переход в режим обслуживания',
    'new' => '{1} :num новое сообщение|[2,4] :num новых сообщения|[5,*] :num новых сообщений',
    'new_ticket' => 'Вы получили новый тикет [Заголовок: :title], пожалуйста, нажмите, чтобы просмотреть подробности.',
    'next_check_time' => 'Следующее время проверки блокировки узла: :time',
    'node' => [
        'download' => 'Скачивание',
        'total' => 'Всего',
        'upload' => 'Загрузка',
    ],
    'node_block' => 'Предупреждение о блокировке узла',
    'node_offline' => 'Предупреждение об отключении узла',
    'node_offline_content' => 'Аномальные узлы, возможно, отключены:',
    'node_renewal' => 'Напоминание о продлении узла',
    'node_renewal_blade' => 'Срок действия следующих узлов скоро истекает. Пожалуйста, продлите заранее:',
    'node_renewal_content' => 'Срок действия следующих узлов скоро истекает. Пожалуйста, продлите до истечения срока, чтобы избежать прерывания обслуживания.',
    'payment_received' => 'Платеж получен, сумма: :amount. Просмотреть детали заказа',
    'reply_ticket' => 'Ответ на тикет: :title',
    'reset_failed' => '[Ежедневная задача] Сброс трафика пользователя [ID: :uid, Пользователь: :username] не удался.',
    'serverChan_exhausted' => 'Дневной лимит исчерпан!',
    'serverChan_limit' => 'Слишком высокая частота запросов в минуту. Пожалуйста, оптимизируйте настройки уведомлений!',
    'sign_failed' => 'Проверка безопасной подписи не удалась',
    'ticket_content' => 'Содержание тикета:',
    'traffic_remain' => 'Вы использовали:percent% трафика, пожалуйста, разумно распределите оставшийся трафик.',
    'traffic_tips' => 'Пожалуйста, обратите внимание на дату сброса трафика, разумно планируйте использование или пополняйте баланс после исчерпания трафика.',
    'traffic_warning' => 'Предупреждение об использовании трафика',
    'verification' => 'Ваш код подтверждения:',
    'verification_account' => 'Подтверждение аккаунта',
    'verification_limit' => 'Пожалуйста, подтвердите в течение :minutes минут',
    'view_ticket' => 'Просмотреть тикет',
    'view_web' => 'Просмотреть веб-сайт',
];
