<?php
namespace Wechat\Card;

use Cache;
use EasyWeChat\Support\Str;
use EasyWeChat\Core\AbstractAPI;

class Card extends AbstractAPI
{


    /**
     * Cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Current URI.
     *
     * @var string
     */
    protected $url;

    /**
     * Ticket cache prefix.
     */
    const TICKET_CACHE_PREFIX = 'overtrue.wechat.wx_card_ticket.';

    /**
     * Api of ticket.
     */
    const API_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    const API_CREATE = 'https://api.weixin.qq.com/card/create';
    const API_SET_WHITELIST = 'https://api.weixin.qq.com/card/testwhitelist/set';
    const API_CARD_CONSUME = 'https://api.weixin.qq.com/card/code/consume';


    public function create($card)
    {
        return $this->parseJSON('json', [self::API_CREATE, ['card' => $card]]);
    }

    public function setWhiteList($lists)
    {
        return $this->parseJSON('json', [self::API_SET_WHITELIST, $lists]);
    }

    public function cardConsume($code, $card_id = null)
    {
        $card = ['code' => $code];
        if ( ! empty($card_id)) {
            $card['card_id'] = $card_id;
        }

        return $this->parseJSON('json', [self::API_CARD_CONSUME, $card]);
    }

    public function configForCard($card_id)
    {
        $ticket = $this->ticket();
        $timestamp = (string) time();
        $nonce_str = Str::quickRandom(10);

        $array = [
            $ticket,
            $timestamp,
            $nonce_str,
            $card_id,
        ];
        \Log::info(json_encode($array));
        asort($array);
        $signature = sha1(implode('', array_values($array)));
        \Log::info($signature);

        $cardExt = [
            'timestamp' => $timestamp,
            'nonce_str' => $nonce_str,
            'signature' => $signature,
        ];

        return [
            'cardId' => $card_id,
            'cardExt' => json_encode($cardExt),
        ];
    }

    /**
     * Get jsticket.
     *
     * @return string
     */
    public function ticket()
    {
        $key = self::TICKET_CACHE_PREFIX . $this->getAccessToken()->getAppId();

        if ($ticket = Cache::get($key)) {
            return $ticket;
        }

        $result = $this->parseJSON('get', [self::API_TICKET, ['type' => 'wx_card']]);

        Cache::put($key, $result['ticket'], $result['expires_in'] - 500);

        return $result['ticket'];
    }
}