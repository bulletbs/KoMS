<?php defined('SYSPATH') OR die('No direct script access.');
return array(
    '' => '',

    /* HTTP errors 403 */
    'Forbidden' => 'Доступ запрещен',
    'token expired' => 'Ключ авторизации просрочен, попробуйте авторизироваться повторно',
    /* HTTP errors 404 */
    'Page not found' => 'Страница не найдена',
    'The requested page not found' => 'Запрошенная Вами страница не найдена.<br> Возможно, мы удалили или переместили ее.<br> Возможно, вы пришли по устаревшей ссылке или неверно ввели адрес.',

    /* User module pages and widgets */
    'Welcome' => 'Вы вошли как',
    'Log in' => 'Вход',
    'Logout' => 'Выход',
    'Register' => 'Регистрация',
    'Last visit' => 'Последний вход',
    'Admin panel' => 'Администраторская',

    /* Admin Menu translates */
    'Backend' => 'KoMS',
    'Home' => 'Главная',
    'Pages' => 'Страницы',
    'News' => 'Новости',
    'Blogs' => 'Блоги',
    'Users' => 'Пользователи',
    'Contents' => 'Контент',
    'Logout' => 'Выйти',
    'Settings' => 'Настройки',
    'Welcome' => 'Привет',
    'Loading' => 'Загрузка',
    'Clear cache' => 'Очистить кеш',
    'Cache successfully cleared' => 'Кеш успешно очищен',

    /* CRUD translates */
    'Operations' => 'Операции',
    'Filter' => 'Фильтр',
    'Add' => 'Добавить',
    'Edit' => 'Редактировать',
    'View' => 'Отображение',
    'Delete' => 'Удалить',
    'Delete selected' => 'Удалить выбраные',
    'Are you sure?' => 'Вы уверены?',
    'Check selected' => 'Подтвердить выбраные',
    'Nothing found' => 'Ничего не найдено',
    'Save' => 'Сохранить',
    'Cancel' => 'Отмена',
    'Order' => 'Сорт.',

    /* Pages */
    'Add new page' => 'Создание новой страницы',
    'Edit page' => 'Редактирование страницы',
    'Static pages' => 'Статические страницы',
    'Add page' => 'Создать страницу',
    'Name' => 'Название',
    'Title' => 'Заголовок',
    'Alias' => 'Алиас',
    'Status' => 'Отображается',
    'Text on page' => 'Текст на странице',

    /* Pagination translate */
    'First' => 'В начало',
    'Previous' => 'Пред',
    'Next' => 'След',
    'Last' => 'В конец',

    /* Moderation */
    'Moderate' => 'Проверить',
    'Moderated' => 'Проверено',
    'Check all' => 'Отметить все как проверенные',
    'Item #:id was successfully moderated' => 'Запись #:id был успешно отмечена как проверенная',
    'All items (:count) was successfully moderated' => 'Все записи (:count) были успешно отмечены как проверенные',
    'All items (:count) was successfully deleted' => 'Все записи (:count) были успешно удалены',

    /* Validation trnslates */
    'An error occurred' => 'Возникла ошибка',
    ':field must contain only letters'                     => 'Поле «:field» должно содержать только буквы',
    ':field must contain only numbers, letters and dashes' => 'Поле «:field» должно содержать только буквы, цифры и подчеркивания',
    ':field must contain only letters and numbers'         => 'Поле «:field» должно содержать только буквы и цифры',
    ':field must be a color'                               => 'Поле «:field» должно быть кодом цвета',
    ':field must be a credit card number'                  => 'Поле «:field» должно быть номером кредитной карты',
    ':field must be a date'                                => 'Поле «:field» должно быть датой',
    ':field must be a decimal with :param2 places'  => 'Поле «:field» должно быть десятичным числом с :param2 количеством цифр',
    ':field must be a digit'                               => 'Поле «:field» должно быть целым числом',
    ':field must be a email address'                       => 'Поле «:field» должно быть адресом электронной почты',
    ':field must contain a valid email domain'        => 'Поле «:field» должно быть существующим адресом электронной почты',
    ':field must equal :param2'                            => 'Поле «:field» должно быть идентичным «:param2»',
    ':field must be exactly :param2 characters long'       => 'Поле «:field» должно быть длиной :param2 символов',
    ':field must be one of the available options'          => 'Поле «:field» должно быть одним из параметров',
    ':field must be an ip address'                         => 'Поле «:field» должно быть ip-адресом',
    ':field must be the same as :param2'                   => 'Поле «:field» должно быть таким же как «:param2»',
    ':field must be at least :param2 characters long'      => 'Поле «:field» не должно быть короче :param2 символов',
    ':field must not exceed :param2 characters long'       => 'Поле «:field» не должно быть длиннее :param2 символов',
    ':field must not be empty'                             => 'Поле «:field» не должно быть пустым',
    ':field must be numeric'                               => 'Поле «:field» должно быть числом',
    ':field must be a phone number'                        => 'Поле «:field» должно быть номером телефона',
    ':field must be within the range of :param2 to :param3'=> 'Поле «:field» должно находиться между :param2 и :param3',
    ':field does not match the required format'            => 'Поле «:field» не соответствует требуемому формату',
    ':field must be a url'                                 => 'Поле «:field» должно быть URL-адресом',
    ':field must be unique (same value exists)'            => 'Поле «:field» должно быть уникальным (такое значение уже существует)',

    ':field contain restricted words'            => 'Поле «:field» содержит запрещенные слова',
);