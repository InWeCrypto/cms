<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Wallet;
use App\Model\EasemobGroup;

class ApiController extends BaseController
{
    public function sendOrderMsg(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'trade_no' => 'required',
            'money' => 'required'
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $total = 0;
        $success = 0;
        $fail = 0;

        $from = $request->get('from');
        $to = $request->get('to');
        $trade_no = $request->get('trade_no');
        $money = $request->get('money');


        $fromWallet = Wallet::with(['category', 'user'])->where('address', $from)->get();
        $toWallet = Wallet::with(['category', 'user'])->where('address', $to)->get();

        // dd($fromWallet->toArray(), $toWallet->toArray());

        $type = EasemobGroup::SYS_MSG_ORDER;
        $msg_from = EasemobGroup::$types[$type];
        $exts = [
            'from' => $from,
            'to' => $to,
            'trade_no' => $trade_no
        ];

        foreach($fromWallet as $item){
            if(empty($item->user)){
                continue ;
            }
            $lang = $item->user->lang ?: 'en';
            $title = trans('custom.SYS_MSG_ORDER_TITLE', [], $lang);

            $exts['money'] = '-'.$money;
            $exts['flag'] = $item->category->name;
            $exts['wallet_name'] = $item->name;


            $send_stat = \EasemobMsg::case($type)
                            ->from($msg_from)
                            ->user($item->user_id)
                            ->title($title)
                            ->txt($title)
                            ->exts($exts)
                            ->send();
            if(! $send_stat){
                $fail++;
                \Log::info('交易信息发送失败!'. $item->user_id . '|' . json_encode($exts));
            }else{
                $success++;
            }
            $total++;
        }

        foreach($toWallet as $item){
            if(empty($item->user)){
                continue ;
            }
            $exts['money'] = '+'.$money;
            $exts['flag'] = $item->category->name;
            $exts['wallet_name'] = $item->name;

            $send_stat = \EasemobMsg::case($type)
                            ->from($msg_from)
                            ->user($item->user_id)
                            ->title($title)
                            ->txt($title)
                            ->exts($exts)
                            ->send();

            if(! $send_stat){
                $fail++;
                \Log::info('交易信息发送失败!'. $item->user_id . '|' . json_encode($exts));
            }else{
                $success++;
            }
            $total++;
        }

        $return = compact('total', 'fail', 'success');
        return $fail ? fail($return) : success($return);
    }

}
