<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentAnswer;
use App\Models\ShipmentDocument;
use App\Models\ShipmentQuestion;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
class OrdersWithShipmentsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/shipments.json');
        $questionsPath = database_path('data/questions.json');

        if (!File::exists($jsonPath) || !File::exists($questionsPath)) {
            $this->command->error("Missing JSON files.");
            return;
        }

        $ordersData = json_decode(File::get($jsonPath), true);
        $questionsJson = json_decode(File::get($questionsPath), true);

        $employees = User::role('employee')->pluck('id')->toArray();
        $managers = User::role('shipment manager')->pluck('id')->toArray();
        $accountants = User::role('accountant')->pluck('id')->toArray();
        $categories = Category::all();

        foreach ($ordersData as $orderData) {
            $order = Order::create([
                'customer_id' => rand(4,5),
                'employee_id' => Arr::random($employees),
                'shipping_manager_id' => Arr::random($managers),
                'accountant_id' => Arr::random($accountants),
                'order_number' => $orderData['order_number'],
                'status' => $orderData['status'],
                'placement' => $orderData['placement'] ?? 0,
                'has_accountant' => $orderData['has_accountant'] ?? 0,
            ]);

            foreach ($orderData['shipments'] as $shipmentData) {
                $category = $categories->random();

                $shipment = Shipment::create([
                    'order_id' => $order->id,
                    'cart_id' => null,
                    'category_id' => $category->id,
                    'number' => $shipmentData['number'],
                    'shipping_date' => $shipmentData['shipping_date'],
                    'service_type' => $shipmentData['service_type'],
                    'origin_country' => $shipmentData['origin_country'],
                    'destination_country' => $shipmentData['destination_country'],
                    'shipping_method' => $shipmentData['shipping_method'],
                    'cargo_weight' => $shipmentData['cargo_weight'],
                    'containers_size' => $shipmentData['containers_size'],
                    'containers_numbers' => $shipmentData['containers_numbers'],
                    'employee_notes' => $shipmentData['employee_notes'],
                    'customer_notes' => $shipmentData['customer_notes'],
                    'is_information_complete' => $shipmentData['is_information_complete'],
                    'is_confirm' => $shipmentData['is_confirm'],
                    'having_supplier' => $shipmentData['having_supplier'],
                    'shipped_date' => now()->subMonths(rand(1,3)),
                    'delivered_date' => now()->subDays(rand(0, 365)),
                ]);

                if ($shipmentData['having_supplier'] && isset($shipmentData['supplier'])) {
                    $supplier = Supplier::create([
                        'user_id' => Arr::random($employees),
                        'name' => $shipmentData['supplier']['name'],
                        'address' => $shipmentData['supplier']['address'],
                        'contact_email' => $shipmentData['supplier']['contact_email'],
                        'contact_phone' => $shipmentData['supplier']['contact_phone'],
                    ]);

                    $shipment->update(['supplier_id' => $supplier->id]);
                    ShipmentDocument::create([
                        'shipment_id' => $shipment->id,
                        'type' => 'supplier_document',
                        'file_path' => $shipmentData['supplier']['document'],
                        'uploaded_by' => 'system',
                        'visible_to_customer' => true,
                    ]);
                }

                // Answers
                $categoryName = $category->name_ar . ' / ' . $category->name_en;
                $questionList = $questionsJson[$categoryName] ?? [];

                foreach ($questionList as $question) {
                    $questionModel = ShipmentQuestion::where('question_ar', $question['text_ar'])->first();
                    if (!$questionModel) continue;

                    $language = App::getLocale(); // or 'ar'

                    $fakeAnswer = match ($question['type']) {
                        'text' => 'text',
                        'radio', 'select' => explode('|', $question['options'][array_rand($question['options'])])[$language === 'en' ? 1 : 0],
                        'checkbox' => implode(', ', array_map(
                            fn($opt) => explode('|', $opt)[$language === 'en' ? 1 : 0],
                            $question['options']
                        )),
                        default => 'N/A',
                    };

                    ShipmentAnswer::create([
                        'user_id' => $order->customer_id,
                        'shipment_id' => $shipment->id,
                        'shipment_question_id' => $questionModel->id,
                        'answer' => $fakeAnswer,
                    ]);
                }
            }
        }

        Order::latest()->take(3)->update([
            'employee_id' => null,
            'shipping_manager_id' => null,
            'accountant_id' => null,
        ]);
    }
}
