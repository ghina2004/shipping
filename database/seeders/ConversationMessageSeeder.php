<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ConversationMessageSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::whereNotNull('employee_id')
            ->orderBy('id', 'asc')
            ->take(Order::count() - 3)
            ->get();

        foreach ($orders as $order) {
            $conversation = Conversation::create([
                'order_id' => $order->id,
                'sender_id' => $order->customer_id,
                'receiver_id' => $order->employee_id,
            ]);

            $messages = [
                ['sender' => $order->customer_id, 'msg' => 'مرحباً، هل تم استلام طلبي؟'],
                ['sender' => $order->employee_id, 'msg' => 'أهلاً بك، نعم تم استلام الطلب ويتم تجهيزه حالياً.'],
                ['sender' => $order->customer_id, 'msg' => 'متى تتوقعون توصيله؟'],
                ['sender' => $order->employee_id, 'msg' => 'غالباً خلال يومين كحد أقصى.'],
                ['sender' => $order->customer_id, 'msg' => 'هل سأتمكن من تتبع الشحنة؟'],
                ['sender' => $order->employee_id, 'msg' => 'نعم، سيتم تزويدك برابط التتبع فور الإرسال.'],
            ];

            foreach ($messages as $data) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $data['sender'],
                    'message' => $data['msg'],
                ]);
            }
        }
    }
}
