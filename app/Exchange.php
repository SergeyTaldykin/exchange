<?php

namespace App;

/**
 * TODO Exchange - buy/sell, по одной паре минимум
 *
 * TODO +++ 1) Пополнить баланс
 * TODO 1.1)
 *         -таблица locked_balances (id, asset_id, user_id, volume)
 *      +++ Таблица pairs (id, left_asset_id, right_asset_id, created_at, updated_at)
 *          Таблица operations (id, pair_id, user_id, qty, created_at) - рыночные ордера
 *          таблица filled_orders (id, pair_id, limit_order_id, operation_id, qty, created_at) - кого заполнили
 *         +Таблица limit_orders (id, user_id, pair_id, qty, qty_left, price, status, created_at, updated_at)
 *
 * TODO 2) Биржа
 *          Подключение через socket и взятие данных (последняя цена, последние операции, лимитные ордера)
 *          Отображение данных
 *          Кнопки buy & sell
 *          buy -
 *          sell - Проверяем баланс
 *
 *
 */
class Exchange
{
    public const OPERATION_TYPE_BUY = 1;
    public const OPERATION_TYPE_SELL = 2;

    public const ORDER_TYPE_LIMIT = 1;
    public const ORDER_TYPE_MARKET = 2;

//    Я хочу купить 2 битка лимитным ордером по 62к
//
//    61 200 - кто-то продает 3 (-1)
//    61 100 - кто-то продает 0,5
//    61 000 - кто-то продает 0,5

//
//    Я хочу купить 2 битка рыночным ордером
//
//    61 200 - кто-то продает 1
//    61 100 - кто-то продает 0,5
//    61 000 - кто-то продает 0,5
}
